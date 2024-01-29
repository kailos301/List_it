<?php

namespace App\Http\Controllers\BackEnd\Car;

use App\Http\Controllers\Controller;
use App\Http\Requests\Car\CarStoreRequest;
use App\Models\Car;
use App\Models\Car\CarContent;
use App\Models\Car\CarImage;
use App\Models\Car\CarModel;
use App\Models\Car\CarSpecification;
use App\Models\Car\CarSpecificationContent;
use App\Models\Language;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Purifier;

class CarController extends Controller
{
    //index
    public function index(Request $request)
    {
        $information['langs'] = Language::all();

        $language = Language::where('is_default', 1)->first();
        $information['language'] = $language;

        $language_id = $language->id;
        $vendor_id = $title = null;
        if (request()->filled('vendor_id')) {
            $vendor_id = $request->vendor_id;
        }
        $carIds = [];
        if (request()->filled('title')) {
            $title = $request->title;
            $car_contents = CarContent::where([['title', 'like', '%' . $title . '%'], ['language_id', $language->id]])->get();
            foreach ($car_contents as $car_content) {
                if (!in_array($car_content->id, $carIds)) {
                    array_push($carIds, $car_content->id);
                }
            }
        }

        $information['cars'] = Car::with([
            'car_content' => function ($q) use ($language_id) {
                $q->where('language_id', $language_id);
            }, 'vendor'
        ])
            ->when($vendor_id, function ($query) use ($vendor_id) {
                if ($vendor_id == 'admin') {
                    return $query->where('vendor_id', '0');
                } else {
                    return $query->where('vendor_id', $vendor_id);
                }
            })
            ->when($title, function ($query) use ($carIds) {
                return $query->whereIn('id', $carIds);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        $information['vendors'] = Vendor::where('id', '!=', 0)->get();
        return view('backend.car.index', $information);
    }
    //create
    public function create()
    {
        $information = [];
        $languages = Language::get();
        $information['languages'] = $languages;
        $information['vendors'] = Vendor::get();
        return view('backend.car.create', $information);
    }
    public function get_brand_model(Request $request)
    {
        $data = CarModel::where('brand_id', $request->id)->where('status', 1)->get();
        return $data;
    }
    public function imagesstore(Request $request)
    {
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg', 'webp');
        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    $ext = $img->getClientOriginalExtension();
                    if (!in_array($ext, $allowedExts)) {
                        return $fail("Only png, jpg, jpeg images are allowed");
                    }
                },
            ]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $filename = uniqid() . '.jpg';

        $directory = public_path('assets/admin/img/car-gallery/');
        @mkdir($directory, 0775, true);
        $img->move($directory, $filename);

        $pi = new CarImage();
        if (!empty($request->car_id)) {
            $pi->car_id = $request->car_id;
        }
        $pi->image = $filename;
        $pi->save();
        return response()->json(['status' => 'success', 'file_id' => $pi->id]);
    }
    public function imagermv(Request $request)
    {
        $pi = CarImage::findOrFail($request->fileid);
        $image_count = CarImage::where('car_id', $pi->car_id)->get()->count();
        if ($image_count > 1) {
            @unlink(public_path('assets/admin/img/car-gallery/') . $pi->image);
            $pi->delete();
            return $pi->id;
        } else {
            return 'false';
        }
    }

    //imagedbrmv
    public function imagedbrmv(Request $request)
    {
        $pi = CarImage::findOrFail($request->fileid);
        $image_count = CarImage::where('car_id', $pi->car_id)->get()->count();
        if ($image_count > 1) {
            @unlink(public_path('assets/admin/img/car-gallery/') . $pi->image);
            $pi->delete();
            return $pi->id;
        } else {
            return 'false';
        }
    }

    //store
    public function store(CarStoreRequest $request)
    {

        DB::transaction(function () use ($request) {

            $featuredImgURL = $request->feature_image;

            $languages = Language::all();
            $in = $request->all();

            $featuredImgExt = $featuredImgURL->getClientOriginalExtension();

            // set a name for the featured image and store it to local storage
            $featuredImgName = time() . '.' . $featuredImgExt;
            $featuredDir = public_path('assets/admin/img/car/');

            if (!file_exists($featuredDir)) {
                mkdir($featuredDir, 0777, true);
            }

            copy($featuredImgURL, $featuredDir . $featuredImgName);

            $in['feature_image'] = $featuredImgName;

            if (!empty($request->label)) {
                $specification = [];
                foreach ($request->label as $key => $varName) {
                    $specification[] = [
                        'label' => $varName,
                        'value' => $request->value[$key]
                    ];
                }
                $in['specification'] = json_encode($specification);
            }

            $car = Car::create($in);

            $slders = $request->slider_images;
            if ($slders) {
                $pis = CarImage::findOrFail($slders);
                foreach ($pis as $key => $pi) {
                    $pi->car_id = $car->id;
                    $pi->save();
                }
            }

            foreach ($languages as $language) {
                $carContent = new CarContent();
                $carContent->language_id = $language->id;
                $carContent->car_id = $car->id;
                $carContent->title = $request[$language->code . '_title'];
                $carContent->slug = createSlug($request[$language->code . '_title']);
                $carContent->category_id = $request[$language->code . '_category_id'];
                $carContent->car_condition_id = $request[$language->code . '_car_condition_id'];
                $carContent->brand_id = $request[$language->code . '_brand_id'];
                $carContent->car_model_id = $request[$language->code . '_car_model_id'];
                $carContent->fuel_type_id = $request[$language->code . '_fuel_type_id'];
                $carContent->transmission_type_id = $request[$language->code . '_transmission_type_id'];
                $carContent->address = $request[$language->code . '_address'];

                $carContent->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
                $carContent->meta_keyword = $request[$language->code . '_meta_keyword'];
                $carContent->meta_description = $request[$language->code . '_meta_description'];
                $carContent->save();

                if (!empty($request[$language->code . '_label'])) {
                    $label_datas = $request[$language->code . '_label'];
                    foreach ($label_datas as $key => $data) {
                        $car_specification = CarSpecification::where([['car_id', $car->id], ['key', $key]])->first();
                        if (is_null($car_specification)) {
                            $car_specification = new CarSpecification();
                            $car_specification->car_id = $car->id;
                            $car_specification->key  = $key;
                            $car_specification->save();
                        }
                        $car_specification_content = new CarSpecificationContent();
                        $car_specification_content->language_id = $language->id;
                        $car_specification_content->car_specification_id = $car_specification->id;
                        $car_specification_content->label = $data;
                        $car_specification_content->value = $request[$language->code . '_value'][$key];
                        $car_specification_content->save();
                    }
                }
            }
        });
        Session::flash('success', 'New car added successfully!');

        return Response::json(['status' => 'success'], 200);
    }
    public function updateFeatured(Request $request)
    {
        $car = Car::findOrFail($request->carId);

        if ($request->is_featured == 1) {
            $car->update(['is_featured' => 1]);

            Session::flash('success', 'Car featured successfully!');
        } else {
            $car->update(['is_featured' => 0]);

            Session::flash('success', 'Car Unfeatured successfully!');
        }

        return redirect()->back();
    }
    public function updateStatus(Request $request)
    {
        $car = Car::findOrFail($request->carId);

        if ($request->status == 1) {
            $car->update(['status' => 1]);

            Session::flash('success', 'Car Active successfully!');
        } else {
            $car->update(['status' => 0]);

            Session::flash('success', 'Car Deactive successfully!');
        }

        return redirect()->back();
    }
    public function edit($id)
    {
        $car = Car::with('galleries')->findOrFail($id);
        $information['car'] = $car;

        // get all the languages from db
        $information['languages'] = Language::all();

        $information['vendors'] = Vendor::get();


        $specifications = CarSpecification::where('car_id', $car->id)->get();
        $information['specifications'] = $specifications;

        return view('backend.car.edit', $information);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'price' => 'required',
            'speed' => 'required',
            'year' => 'required',
            'mileage' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ];

        $featuredImgURL = $request->thumbnail;

        $allowedExts = array('jpg', 'png', 'jpeg', 'svg');
        if ($request->hasFile('feature_image')) {
            $rules['feature_image'] = [
                'required',
                function ($attribute, $value, $fail) use ($featuredImgURL, $allowedExts) {
                    $ext = $featuredImgURL->getClientOriginalExtension();
                    if (!in_array($ext, $allowedExts)) {
                        return $fail("Only png, jpg, jpeg images are allowed");
                    }
                },
            ];
        }

        $languages = Language::all();


        foreach ($languages as $language) {
            $rules[$language->code . '_title'] = 'required|max:255';
            $rules[$language->code . '_address'] = 'required';

            $rules[$language->code . '_category_id'] = 'required';
            $rules[$language->code . '_brand_id'] = 'required';
            $rules[$language->code . '_car_model_id'] = 'required';
            $rules[$language->code . '_fuel_type_id'] = 'required';
            $rules[$language->code . '_transmission_type_id'] = 'required';

            $rules[$language->code . '_description'] = 'required|min:15';

            $messages[$language->code . '_title.required'] = 'The title field is required for ' . $language->name . ' language';
            $messages[$language->code . '_address.required'] = 'The address field is required for ' . $language->name . ' language';

            $messages[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language';

            $messages[$language->code . '_category_id.required'] = 'The category field is required for ' . $language->name . ' language';
            $messages[$language->code . '_brand_id.required'] = 'The brand field is required for ' . $language->name . ' language';
            $messages[$language->code . '_car_model_id.required'] = 'The model field is required for ' . $language->name . ' language';
            $messages[$language->code . '_fuel_type_id.required'] = 'The fuel type field is required for ' . $language->name . ' language';
            $messages[$language->code . '_transmission_type_id.required'] = 'The transmission  type field is required for ' . $language->name . ' language';


            $messages[$language->code . '_description.required'] = 'The description field is required for ' . $language->name . ' language';

            $messages[$language->code . '_description.min'] = 'The description field atleast have 15 characters for ' . $language->name . ' language';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $in = $request->all();
        $car = Car::findOrFail($request->car_id);
        if ($request->hasFile('thumbnail')) {
            $featuredImgExt = $featuredImgURL->getClientOriginalExtension();

            // set a name for the featured image and store it to local storage
            $featuredImgName = time() . '.' . $featuredImgExt;
            $featuredDir = public_path('assets/admin/img/car/');

            if (!file_exists($featuredDir)) {
                mkdir($featuredDir, 0777, true);
            }
            copy($featuredImgURL, $featuredDir . $featuredImgName);
            @unlink(public_path('assets/admin/img/car/') . $car->feature_image);

            $in['feature_image'] = $featuredImgName;
        }
        $car = $car->update($in);

        $slders = $request->slider_images;
        if ($slders) {
            $pis = CarImage::findOrFail($slders);
            foreach ($pis as $key => $pi) {
                $pi->car_id = $request->car_id;
                $pi->save();
            }
        }

        $d_car_specifications = CarSpecification::where('car_id', $request->car_id)->get();
        foreach ($d_car_specifications as $d_car_specification) {
            $d_car_specification_contents = CarSpecificationContent::where('car_specification_id', $d_car_specification->id)->get();
            foreach ($d_car_specification_contents as $d_car_specification_content) {
                $d_car_specification_content->delete();
            }
            $d_car_specification->delete();
        }

        foreach ($languages as $language) {
            $carContent =  CarContent::where('car_id', $request->car_id)->where('language_id', $language->id)->first();
            if (empty($carContent)) {
                $carContent = new CarContent();
            }
            $carContent->language_id = $language->id;
            $carContent->title = $request[$language->code . '_title'];
            $carContent->slug = createSlug($request[$language->code . '_title']);
            $carContent->category_id = $request[$language->code . '_category_id'];
            $carContent->car_condition_id = $request[$language->code . '_car_condition_id'];
            $carContent->brand_id = $request[$language->code . '_brand_id'];
            $carContent->car_model_id = $request[$language->code . '_car_model_id'];
            $carContent->fuel_type_id = $request[$language->code . '_fuel_type_id'];
            $carContent->transmission_type_id = $request[$language->code . '_transmission_type_id'];
            $carContent->address = $request[$language->code . '_address'];

            $carContent->description = Purifier::clean($request[$language->code . '_description'], 'youtube');
            $carContent->meta_keyword = $request[$language->code . '_meta_keyword'];
            $carContent->meta_description = $request[$language->code . '_meta_description'];
            $carContent->save();

            if (!empty($request[$language->code . '_label'])) {
                $label_datas = $request[$language->code . '_label'];
                foreach ($label_datas as $key => $data) {
                    $car_specification = CarSpecification::where([['car_id', $request->car_id], ['key', $key]])->first();
                    if (is_null($car_specification)) {
                        $car_specification = new CarSpecification();
                        $car_specification->car_id = $request->car_id;
                        $car_specification->key  = $key;
                        $car_specification->save();
                    }
                    $car_specification_content = new CarSpecificationContent();
                    $car_specification_content->language_id = $language->id;
                    $car_specification_content->car_specification_id = $car_specification->id;
                    $car_specification_content->label = $data;
                    $car_specification_content->value = $request[$language->code . '_value'][$key];
                    $car_specification_content->save();
                }
            }
        }

        Session::flash('success', 'Car Updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }
    //delete
    public function delete(Request $request)
    {
        $car = Car::findOrFail($request->car_id);

        // first, delete all the contents of this package
        $contents = $car->car_content()->get();

        foreach ($contents as $content) {
            $content->delete();
        }

        // third, delete feature_image image of this package
        if (!is_null($car->feature_image)) {
            @unlink(public_path('assets/admin/img/car/') . $car->feature_image);
        }

        // first, delete all the contents of this package
        $galleries = $car->galleries()->get();

        foreach ($galleries as $gallery) {
            @unlink(public_path('assets/admin/img/car-gallery/') . $gallery->image);
            $gallery->delete();
        }

        // finally, delete this package
        $car->delete();

        Session::flash('success', 'Car deleted successfully!');

        return redirect()->back();
    }
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $car = Car::findOrFail($id);

            // first, delete all the contents of this package
            $contents = $car->car_content()->get();

            foreach ($contents as $content) {
                $content->delete();
            }

            // third, delete feature_image image of this package
            if (!is_null($car->feature_image)) {
                @unlink(public_path('assets/admin/img/car/') . $car->feature_image);
            }

            // first, delete all the contents of this package
            $galleries = $car->galleries()->get();

            foreach ($galleries as $gallery) {
                @unlink(public_path('assets/admin/img/car-gallery/') . $gallery->image);
                $gallery->delete();
            }

            // finally, delete this package
            $car->delete();
        }

        Session::flash('success', 'Car deleted successfully!');

        /**
         * this 'success' is returning for ajax call.
         * if return == 'success' then ajax will reload the page.
         */
        return response()->json(['status' => 'success'], 200);
    }
}
