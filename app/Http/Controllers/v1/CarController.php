<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Car;
use App\Models\Car\Brand;
use App\Models\Car\CarColor;
use App\Models\Car\CarContent;
use App\Models\Car\CarModel;
use App\Models\Car\CarSpecification; 
use App\Models\Car\CarSpecificationContent;
use App\Models\Car\Category;
use App\Models\Car\FuelType;
use App\Models\Car\BodyType;
use App\Models\Car\TransmissionType;
use App\Models\Vendor;
use App\Models\Visitor;
use App\Models\CountryArea;
use App\Models\CarYear;
use App\Models\AdsPrice;
use Carbon\Carbon;
use Config;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Session;

class CarController extends Controller
{   
    public function __construct()
    {
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();       
    }

    public function index(Request $request)
    {
        
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();
       // $information['seoInfo'] = $language->seoInfo()->select('meta_keyword_cars', 'meta_description_cars')->first();
        if ($request->filled('type')) {
            Session::put('car_view_type', $request->type);
        }
        $view_type = Session::get('car_view_type');

        $category = $title = $location = $brands = $models = $fuel_type = $transmission = $condition = $min = $max =  null;

        $carIds = [];
        if ($request->filled('title')) {
            $title = $request->title;
            $car_contents = CarContent::where('language_id', $language->id)
                ->where('title', 'like', '%' . $title . '%')
                ->get()
                ->pluck('car_id');
            foreach ($car_contents as $car_content) {
                if (!in_array($car_content, $carIds)) {
                    array_push($carIds, $car_content);
                }
            }
        }

        // ------- Get car categories ---------
        $category_carIds = [];
        if ($request->filled('category')) {
            $category = $request->category;
            $category_content = Category::where([['language_id', $language->id], ['slug', $category]])->first();
            if (!empty($category_content)) {
                $category_id = $category_content->id;
                $contents = CarContent::where('language_id', $language->id)
                    ->where('category_id', $category_id)
                    ->get()
                    ->pluck('car_id');
                foreach ($contents as $content) {
                    if (!in_array($content, $category_carIds)) {
                        array_push($category_carIds, $content);
                    }
                }
            }
        }
        // --- end ---

        // ----- Car location -----

        $locationIds = [];
        if ($request->filled('location')) {
            $location = $request->location;
            $contents = CarContent::where('language_id', $language->id)
                ->where('address', 'like', '%' . $location . '%')
                ->get()
                ->pluck('car_id');
            foreach ($contents as $content) {
                if (!in_array($content, $locationIds)) {
                    array_push($locationIds, $content);
                }
            }
        }
        // ---  end location------

        // --- Start brands ------

        $brandIds = [];
        if ($request->filled('brands')) {
            $brands = $request->brands;
            $b_ids = [];

            if (is_array($brands)) {
                foreach ($brands as $brand) {
                    if (!is_null($brand)) {
                        $brand_car_contents = Brand::where([['language_id', $language->id], ['slug', $brand]])->first();
                        if (!empty($brand_car_contents)) {
                            if (!in_array($brand_car_contents->id, $b_ids)) {
                                array_push($b_ids, $brand_car_contents->id);
                            }
                        }
                    }
                }
            } else {
                $brand_car_contents = Brand::where([['language_id', $language->id], ['slug', $brands]])->first();
                if (!in_array($brand_car_contents->id, $b_ids)) {
                    array_push($b_ids, $brand_car_contents->id);
                }
            }

            $contents = CarContent::where('language_id', $language->id)
                ->whereIn('brand_id', $b_ids)
                ->get()
                ->pluck('car_id');
            foreach ($contents as $content) {
                if (!in_array($content, $brandIds)) {
                    array_push($brandIds, $content);
                }
            }
        }
        // ---- end brands -----
        // ---- Start models ----

        $modelIds = [];
        if ($request->filled('models')) {
            $models = $request->models;
            $m_ids = [];
            if (is_array($models)) {
                foreach ($models as $model) {
                    $model_car_contents = CarModel::where([['language_id', $language->id], ['slug', $model]])->where('status', 1)->first();
                    if (!in_array($model_car_contents->id, $m_ids)) {
                        array_push($m_ids, $model_car_contents->id);
                    }
                }
            } else {
                $model_car_contents = CarModel::where([['language_id', $language->id], ['slug', $models]])->where('status', 1)->first();
                if (!in_array($model_car_contents->id, $m_ids)) {
                    array_push($m_ids, $model_car_contents->id);
                }
            }

            $contents = CarContent::where('language_id', $language->id)
                ->whereIn('car_model_id', $m_ids)
                ->get();
            foreach ($contents as $content) {
                if (!in_array($content->car_id, $modelIds)) {
                    array_push($modelIds, $content->car_id);
                }
            }
        }
        // ---- End models -----
        //---- Start fuel type ----

        $fuel_type_id = [];
        if ($request->filled('fuel_type')) {
            $fuel_type = $request->fuel_type;
            $fuel_type_content = FuelType::where([['language_id', $language->id], ['slug', $fuel_type]])->first();
            if (!empty($fuel_type_content)) {
                $f_id = $fuel_type_content->id;
                $contents = CarContent::where('language_id', $language->id)
                    ->where('fuel_type_id', $f_id)
                    ->get()
                    ->pluck('car_id');
                foreach ($contents as $content) {
                    if (!in_array($content, $fuel_type_id)) {
                        array_push($fuel_type_id, $content);
                    }
                }
            }
        }
        // ---- end fuel types----
        // ----- Start trnsmission ----

        $transmission_type_id = [];
        if ($request->filled('transmission')) {
            $transmission = $request->transmission;
            $transmission_content = TransmissionType::where([['language_id', $language->id], ['slug', $transmission]])->first();
            if (!empty($transmission_content)) {
                $t_id = $transmission_content->id;
                $contents = CarContent::where('language_id', $language->id)
                    ->where('transmission_type_id', $t_id)
                    ->get()
                    ->pluck('car_id');
                foreach ($contents as $content) {
                    if (!in_array($content, $transmission_type_id)) {
                        array_push($transmission_type_id, $content);
                    }
                }
            }
        }
        // ---- transmission ----
        //---- Start condition -----
        $condition_id = [];
        if ($request->filled('transmission')) {
            $condition = $request->condition;
            $condition_content = CarColor::where([['language_id', $language->id], ['slug', $condition]])->first();
            if (!empty($condition_content)) {
                $c_id = $condition_content->id;
                $contents = CarContent::where('language_id', $language->id)
                    ->where('car_condition_id', $c_id)
                    ->get()
                    ->pluck('car_id');
                foreach ($contents as $content) {
                    if (!in_array($content, $condition_id)) {
                        array_push($condition_id, $content);
                    }
                }
            }
        }
       // print_r($content);
        //----- end condition -----
        // ---- Start prices -----
        

        $priceIds = [];
        if ($request->filled('min') && $request->filled('max')) {
            $min = intval($request->min);
            $max = intval(($request->max));
            $price_cars = DB::table('cars')
                ->select('*')
                ->where('price', '>=', $min)
                ->where('price', '<=', DB::raw($max))
                ->get();
            foreach ($price_cars as $car) {
                if (!in_array($car->id, $priceIds)) {
                    array_push($priceIds, $car->id);
                }
            }
        }


        // ------ Price end  ------
        // ---- Start sorting ----
        if ($request->filled('sort')) {
            if ($request['sort'] == 'new') {
                $order_by_column = 'cars.id';
                $order = 'desc';
            } elseif ($request['sort'] == 'old') {
                $order_by_column = 'cars.id';
                $order = 'asc';
            } elseif ($request['sort'] == 'high-to-low') {
                $order_by_column = 'cars.price';
                $order = 'desc';
            } elseif ($request['sort'] == 'low-to-high') {
                $order_by_column = 'cars.price';
                $order = 'asc';
            } else {
                $order_by_column = 'cars.id';
                $order = 'desc';
            }
        } else {
            $order_by_column = 'cars.id';
            $order = 'desc';
        }
        // ---- end sorting -----


        $car_contents = Car::join('car_contents', 'cars.id', 'car_contents.car_id')
            ->join('memberships', 'cars.vendor_id', '=', 'memberships.vendor_id')
            ->join('vendors', 'cars.vendor_id', '=', 'vendors.id')
            ->where([
                ['memberships.status', '=', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
            ])
            ->where([['vendors.status', 1], ['cars.status', 1]])
            ->when($title, function ($query) use ($carIds) {
                return $query->whereIn('cars.id', $carIds);
            })
            ->when($category, function ($query) use ($category_carIds) {
                return $query->whereIn('car_id', $category_carIds);
            })
            ->when($location, function ($query) use ($locationIds) {
                return $query->whereIn('car_id', $locationIds);
            })
            ->when($brands, function ($query) use ($brandIds) {
                return $query->whereIn('cars.id', $brandIds);
            })
            ->when($models, function ($query) use ($modelIds) {
                return $query->whereIn('cars.id', $modelIds);
            })
            ->when($fuel_type, function ($query) use ($fuel_type_id) {
                return $query->whereIn('cars.id', $fuel_type_id);
            })
            ->when($transmission, function ($query) use ($transmission_type_id) {
                return $query->whereIn('cars.id', $transmission_type_id);
            })
            ->when($condition, function ($query) use ($condition_id) {
                return $query->whereIn('cars.id', $condition_id);
            })
            ->when($min && $max, function ($query) use ($priceIds) {
                return $query->whereIn('cars.id', $priceIds);
            })
            ->where('car_contents.language_id', $language->id)
            ->select('cars.*', 'car_contents.title','car_contents.brand_id', 'car_contents.car_model_id', 'car_contents.slug', 'car_contents.category_id', 'car_contents.description')
            ->orderBy($order_by_column, $order)
            ->paginate(9);

        $total_cars = Car::join('car_contents', 'cars.id', 'car_contents.car_id')
            ->join('memberships', 'cars.vendor_id', '=', 'memberships.vendor_id')
            ->join('vendors', 'cars.vendor_id', '=', 'vendors.id')
            ->where([
                ['memberships.status', '=', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
            ])
            ->where([['vendors.status', 1], ['cars.status', 1]])
            ->when($title, function ($query) use ($carIds) {
                return $query->whereIn('cars.id', $carIds);
            })
            ->when($category, function ($query) use ($category_carIds) {
                return $query->whereIn('car_id', $category_carIds);
            })
            ->when($location, function ($query) use ($locationIds) {
                return $query->whereIn('car_id', $locationIds);
            })
            ->when($brands, function ($query) use ($brandIds) {
                return $query->whereIn('cars.id', $brandIds);
            })
            ->when($models, function ($query) use ($modelIds) {
                return $query->whereIn('cars.id', $modelIds);
            })
            ->when($fuel_type, function ($query) use ($fuel_type_id) {
                return $query->whereIn('cars.id', $fuel_type_id);
            })
            ->when($transmission, function ($query) use ($transmission_type_id) {
                return $query->whereIn('cars.id', $transmission_type_id);
            })
            ->when($condition, function ($query) use ($condition_id) {
                return $query->whereIn('cars.id', $condition_id);
            })
            ->when($min && $max, function ($query) use ($priceIds) {
                return $query->whereIn('cars.id', $priceIds);
            })
            ->where('car_contents.language_id', $language->id)
            ->select('cars.*', 'car_contents.title',  'car_contents.slug', 'car_contents.category_id', 'car_contents.description')
            ->orderBy($order_by_column, $order)
            ->get()->count();

        $information['car_contents'] = $car_contents;
        $min = Car::min('price');
        $max = Car::max('price');

        $information['min'] = intval($min);
        $information['max'] = intval($max);

        $information['total_cars'] = $total_cars;

       // $information['categories'] = Category::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();

       // $information['brands'] = Brand::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();

        //$information['car_conditions'] = CarColor::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();

        //$information['fuel_types'] = FuelType::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();

        //$information['transmission_types'] = TransmissionType::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();

       // $information['bgImg'] = $misc->getBreadcrumb();
       // $information['pageHeading'] = $misc->getPageHeading($language);

        /*if ($view_type == 'grid') {
            return view('frontend.car.cars_grid', $information);
        } else {
            return view('frontend.car.cars_list', $information);
        }*/
        return response()->json(['code' => 200, 'message' => 'successful.','data' =>$information]); exit;
    }

    public function filters(Request $request)
    {
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();
        //$information['seoInfo'] = $language->seoInfo()->select('meta_keyword_cars', 'meta_description_cars')->first();
        if ($request->filled('type')) {
            Session::put('car_view_type', $request->type);
        }
        $view_type = Session::get('car_view_type');

        $category = $title = $location = $brands = $models = $fuel_type = $transmission = $condition = $min = $max =  null;

        $carIds = [];
        if ($request->filled('title')) {
            $title = $request->title;
            $car_contents = CarContent::where('language_id', $language->id)
                ->where('title', 'like', '%' . $title . '%')
                ->get()
                ->pluck('car_id');
            foreach ($car_contents as $car_content) {
                if (!in_array($car_content, $carIds)) {
                    array_push($carIds, $car_content);
                }
            }
        }

        // ------- Get car categories ---------
        $category_carIds = [];
        if ($request->filled('category')) {
            $category = $request->category;
            $category_content = Category::where([['language_id', $language->id], ['slug', $category]])->first();
            if (!empty($category_content)) {
                $category_id = $category_content->id;
                $contents = CarContent::where('language_id', $language->id)
                    ->where('category_id', $category_id)
                    ->get()
                    ->pluck('car_id');
                foreach ($contents as $content) {
                    if (!in_array($content, $category_carIds)) {
                        array_push($category_carIds, $content);
                    }
                }
            }
        }
        // --- end ---

        // ----- Car location -----

        $locationIds = [];
        if ($request->filled('location')) {
            $location = $request->location;
            $contents = CarContent::where('language_id', $language->id)
                ->where('address', 'like', '%' . $location . '%')
                ->get()
                ->pluck('car_id');
            foreach ($contents as $content) {
                if (!in_array($content, $locationIds)) {
                    array_push($locationIds, $content);
                }
            }
        }
        // ---  end location------

        // --- Start brands ------

        $brandIds = [];
        if ($request->filled('brands')) {
            $brands = $request->brands;
            $b_ids = [];

            if (is_array($brands)) {
                foreach ($brands as $brand) {
                    if (!is_null($brand)) {
                        $brand_car_contents = Brand::where([['language_id', $language->id], ['slug', $brand]])->first();
                        if (!empty($brand_car_contents)) {
                            if (!in_array($brand_car_contents->id, $b_ids)) {
                                array_push($b_ids, $brand_car_contents->id);
                            }
                        }
                    }
                }
            } else {
                $brand_car_contents = Brand::where([['language_id', $language->id], ['slug', $brands]])->first();
                if (!in_array($brand_car_contents->id, $b_ids)) {
                    array_push($b_ids, $brand_car_contents->id);
                }
            }

            $contents = CarContent::where('language_id', $language->id)
                ->whereIn('brand_id', $b_ids)
                ->get()
                ->pluck('car_id');
            foreach ($contents as $content) {
                if (!in_array($content, $brandIds)) {
                    array_push($brandIds, $content);
                }
            }
        }
        // ---- end brands -----
        // ---- Start models ----

        $modelIds = [];
        if ($request->filled('models')) {
            $models = $request->models;
            $m_ids = [];
            if (is_array($models)) {
                foreach ($models as $model) {
                    $model_car_contents = CarModel::where([['language_id', $language->id], ['slug', $model]])->where('status', 1)->first();
                    if (!in_array($model_car_contents->id, $m_ids)) {
                        array_push($m_ids, $model_car_contents->id);
                    }
                }
            } else {
                $model_car_contents = CarModel::where([['language_id', $language->id], ['slug', $models]])->where('status', 1)->first();
                if (!in_array($model_car_contents->id, $m_ids)) {
                    array_push($m_ids, $model_car_contents->id);
                }
            }

            $contents = CarContent::where('language_id', $language->id)
                ->whereIn('car_model_id', $m_ids)
                ->get();
            foreach ($contents as $content) {
                if (!in_array($content->car_id, $modelIds)) {
                    array_push($modelIds, $content->car_id);
                }
            }
        }
        // ---- End models -----
        //---- Start fuel type ----

        $fuel_type_id = [];
        if ($request->filled('fuel_type')) {
            $fuel_type = $request->fuel_type;
            $fuel_type_content = FuelType::where([['language_id', $language->id], ['slug', $fuel_type]])->first();
            if (!empty($fuel_type_content)) {
                $f_id = $fuel_type_content->id;
                $contents = CarContent::where('language_id', $language->id)
                    ->where('fuel_type_id', $f_id)
                    ->get()
                    ->pluck('car_id');
                foreach ($contents as $content) {
                    if (!in_array($content, $fuel_type_id)) {
                        array_push($fuel_type_id, $content);
                    }
                }
            }
        }
        // ---- end fuel types----
        // ----- Start trnsmission ----

        $transmission_type_id = [];
        if ($request->filled('transmission')) {
            $transmission = $request->transmission;
            $transmission_content = TransmissionType::where([['language_id', $language->id], ['slug', $transmission]])->first();
            if (!empty($transmission_content)) {
                $t_id = $transmission_content->id;
                $contents = CarContent::where('language_id', $language->id)
                    ->where('transmission_type_id', $t_id)
                    ->get()
                    ->pluck('car_id');
                foreach ($contents as $content) {
                    if (!in_array($content, $transmission_type_id)) {
                        array_push($transmission_type_id, $content);
                    }
                }
            }
        }
        // ---- transmission ----
        //---- Start condition -----
        $condition_id = [];
        if ($request->filled('transmission')) {
            $condition = $request->condition;
            $condition_content = CarColor::where([['language_id', $language->id], ['slug', $condition]])->first();
            if (!empty($condition_content)) {
                $c_id = $condition_content->id;
                $contents = CarContent::where('language_id', $language->id)
                    ->where('car_condition_id', $c_id)
                    ->get()
                    ->pluck('car_id');
                foreach ($contents as $content) {
                    if (!in_array($content, $condition_id)) {
                        array_push($condition_id, $content);
                    }
                }
            }
        }
       // print_r($content);
        //----- end condition -----
        // ---- Start prices -----
        

        $priceIds = [];
        if ($request->filled('min') && $request->filled('max')) {
            $min = intval($request->min);
            $max = intval(($request->max));
            $price_cars = DB::table('cars')
                ->select('*')
                ->where('price', '>=', $min)
                ->where('price', '<=', DB::raw($max))
                ->get();
            foreach ($price_cars as $car) {
                if (!in_array($car->id, $priceIds)) {
                    array_push($priceIds, $car->id);
                }
            }
        }


        // ------ Price end  ------
        // ---- Start sorting ----
        if ($request->filled('sort')) {
            if ($request['sort'] == 'new') {
                $order_by_column = 'cars.id';
                $order = 'desc';
            } elseif ($request['sort'] == 'old') {
                $order_by_column = 'cars.id';
                $order = 'asc';
            } elseif ($request['sort'] == 'high-to-low') {
                $order_by_column = 'cars.price';
                $order = 'desc';
            } elseif ($request['sort'] == 'low-to-high') {
                $order_by_column = 'cars.price';
                $order = 'asc';
            } else {
                $order_by_column = 'cars.id';
                $order = 'desc';
            }
        } else {
            $order_by_column = 'cars.id';
            $order = 'desc';
        }
        // ---- end sorting -----


        $car_contents = Car::join('car_contents', 'cars.id', 'car_contents.car_id')
            ->join('memberships', 'cars.vendor_id', '=', 'memberships.vendor_id')
            ->join('vendors', 'cars.vendor_id', '=', 'vendors.id')
            ->where([
                ['memberships.status', '=', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
            ])
            ->where([['vendors.status', 1], ['cars.status', 1]])
            ->when($title, function ($query) use ($carIds) {
                return $query->whereIn('cars.id', $carIds);
            })
            ->when($category, function ($query) use ($category_carIds) {
                return $query->whereIn('car_id', $category_carIds);
            })
            ->when($location, function ($query) use ($locationIds) {
                return $query->whereIn('car_id', $locationIds);
            })
            ->when($brands, function ($query) use ($brandIds) {
                return $query->whereIn('cars.id', $brandIds);
            })
            ->when($models, function ($query) use ($modelIds) {
                return $query->whereIn('cars.id', $modelIds);
            })
            ->when($fuel_type, function ($query) use ($fuel_type_id) {
                return $query->whereIn('cars.id', $fuel_type_id);
            })
            ->when($transmission, function ($query) use ($transmission_type_id) {
                return $query->whereIn('cars.id', $transmission_type_id);
            })
            ->when($condition, function ($query) use ($condition_id) {
                return $query->whereIn('cars.id', $condition_id);
            })
            ->when($min && $max, function ($query) use ($priceIds) {
                return $query->whereIn('cars.id', $priceIds);
            })
            ->where('car_contents.language_id', $language->id)
            ->select('cars.*', 'car_contents.title','car_contents.brand_id', 'car_contents.car_model_id', 'car_contents.slug', 'car_contents.category_id', 'car_contents.description')
            ->orderBy($order_by_column, $order)
            ->paginate(9);

        $total_cars = Car::join('car_contents', 'cars.id', 'car_contents.car_id')
            ->join('memberships', 'cars.vendor_id', '=', 'memberships.vendor_id')
            ->join('vendors', 'cars.vendor_id', '=', 'vendors.id')
            ->where([
                ['memberships.status', '=', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
            ])
            ->where([['vendors.status', 1], ['cars.status', 1]])
            ->when($title, function ($query) use ($carIds) {
                return $query->whereIn('cars.id', $carIds);
            })
            ->when($category, function ($query) use ($category_carIds) {
                return $query->whereIn('car_id', $category_carIds);
            })
            ->when($location, function ($query) use ($locationIds) {
                return $query->whereIn('car_id', $locationIds);
            })
            ->when($brands, function ($query) use ($brandIds) {
                return $query->whereIn('cars.id', $brandIds);
            })
            ->when($models, function ($query) use ($modelIds) {
                return $query->whereIn('cars.id', $modelIds);
            })
            ->when($fuel_type, function ($query) use ($fuel_type_id) {
                return $query->whereIn('cars.id', $fuel_type_id);
            })
            ->when($transmission, function ($query) use ($transmission_type_id) {
                return $query->whereIn('cars.id', $transmission_type_id);
            })
            ->when($condition, function ($query) use ($condition_id) {
                return $query->whereIn('cars.id', $condition_id);
            })
            ->when($min && $max, function ($query) use ($priceIds) {
                return $query->whereIn('cars.id', $priceIds);
            })
            ->where('car_contents.language_id', $language->id)
            ->select('cars.*', 'car_contents.title',  'car_contents.slug', 'car_contents.category_id', 'car_contents.description')
            ->orderBy($order_by_column, $order)
            ->get()->count();

        //$information['car_contents'] = $car_contents;
        $min = Car::min('price');
        $max = Car::max('price');

        $information['min'] = intval($min);
        $information['max'] = intval($max);

        //$information['total_cars'] = $total_cars;

        $information['categories'] = Category::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();

        $information['brands'] = Brand::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();

        $information['car_conditions'] = CarColor::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();

        $information['fuel_types'] = FuelType::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();

        $information['transmission_types'] = TransmissionType::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();

       // $information['bgImg'] = $misc->getBreadcrumb();
       // $information['pageHeading'] = $misc->getPageHeading($language);

        /*if ($view_type == 'grid') {
            return view('frontend.car.cars_grid', $information);
        } else {
            return view('frontend.car.cars_list', $information);
        }*/
        return response()->json(['code' => 200, 'message' => 'successful.','data' =>$information]); exit;
    }

    // ------ Get Modal based on Make ------
    public function getcarModel(Request $request)
      {
        $slug = $request->id;

        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();

        $car_brand = Brand::where([['language_id', $language->id], ['slug', $slug]])->first();
        if ($car_brand) {
          $models = CarModel::select('id', 'brand_id','name','slug','status')->where([['brand_id', $car_brand->id], ['status', 1], ['language_id', $language->id]])->orderBy('serial_number', 'asc')->get();
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>$models]); exit;
          //return $models;
        } else {
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>null]); exit;
        }
      }
 // ------ Get all Make  ------
      public function getcarMake(Request $request)
      {
        

        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();

        $car_brand = Brand::select('id', 'name','slug','status')->where([['language_id', $language->id], ['status', 1]])->orderBy('serial_number', 'asc')->get();
        if ($car_brand) {
         
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>$car_brand]); exit;
          //return $models;
        } else {
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>null]); exit;
        }
      }
    // ---- get fuel Type -----
      public function getFuelType(Request $request)
      {
       
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();

        $car_fType = FuelType::select('id', 'name','slug','status')->where([['language_id', $language->id], ['status', 1]])->orderBy('serial_number', 'asc')->get();
        if ($car_fType) {
         
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>$car_fType]); exit;
          //return $models;
        } else {
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>null]); exit;
        }
      }

      // ---- get Transmission Type -----
      public function getTransmissionType(Request $request)
      {
       
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();

        $tData = TransmissionType::select('id', 'name','slug','status')->where([['language_id', $language->id], ['status', 1]])->orderBy('serial_number', 'asc')->get();
        if ($tData) {
         
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>$tData]); exit;
          //return $models;
        } else {
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>null]); exit;
        }
      }

      // ---- Get Colour -------
      public function getColorType(Request $request)
      {
        $CarColorData = CarColor::select('id', 'name')->where([['status', 1]])->orderBy('serial_number', 'asc')->get();
        if ($CarColorData) {
         
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>$CarColorData]); exit;
          //return $models;
        } else {
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>null]); exit;
        }
     }
     // ---- Get year -------
      public function getCarYear(Request $request)
      {
        $CarYearData = CarYear::select('id', 'name')->where([['status', 1]])->orderBy('name', 'desc')->get();
        if ($CarYearData) {
         
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>$CarYearData]); exit;
          //return $models;
        } else {
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>null]); exit;
        }
     }
     // ---- Get car locations -------
      public function getLocation(Request $request)
      {
        $getLocationData = CountryArea::where('status', 1)->orderBy('name', 'asc')->get();
        if ($getLocationData) {
         
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>$getLocationData]); exit;
          //return $models;
        } else {
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>null]); exit;
        }
     }

     // ---- Get prices -------
      public function getPrices(Request $request)
      {
        $getPricesData = AdsPrice::where('status', 1)->orderBy('name', 'desc')->get();
        if ($getPricesData) {
         
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>$getPricesData]); exit;
          //return $models;
        } else {
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>null]); exit;
        }
     }
      // ---- get Bod Type -----
      public function getBodyType(Request $request)
      {
       
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();

        $tData = BodyType::select('id', 'name','slug','status')->where([['status', 1]])->orderBy('serial_number', 'asc')->get();
        //print_r($tData);  exit;
        if ($tData) {
         
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>$tData]); exit;
          //return $models;
        } else {
          return response()->json(['code' => 200, 'message' => 'successful.','data' =>null]); exit;
        }
      }
    public function getCarData(Request $request){

     //return response()->json(['code' => 200, 'message' => 'successful.','data' =>null]); exit;
        $apiarray = [];
      // Init cURL session
        $curl = curl_init();

        // Set API Key
        $ApiKey = "3486bd09-5f3d-4be3-a7ce-580de4749564";
        $vehReg = $request->vehiclereg;
        // Construct URL String
        $url = "https://uk1.ukvehicledata.co.uk/api/datapackage/%s?v=2&api_nullitems=1&key_vrm=%s&auth_apikey=%s";
        $url = sprintf($url, "VehicleDataIRL", $vehReg, $ApiKey); // Syntax: sprintf($url, "PackageName", "VRM", ApiKey);
        // Note your package name here. There are 5 standard packagenames. Please see your control panel > weblookup or contact your account manager

        // Create array of options for the cURL session
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET"
        ));

        // Execute cURL session and store the response in $response
        $response = curl_exec($curl);

        // If the operation failed, store the error message in $error
        $error = curl_error($curl);

        // Close cURL session
        curl_close($curl);
//echo "cURL Error: " . $error; exit;
        // If there was an error, print it to screen. Otherwise, unserialize response and print to screen.
        if ($error) {
          echo "cURL Error: " . $error;
        } else {
          $data = json_decode($response, true);
          // check if Brand exist.
          $vMake = ucfirst(strtolower($data['Response']['DataItems']['RoiVehicleDetails']['Make']));
           $car_brand = Brand::where('name', $vMake)->first();
           if ($car_brand) {
              $makeId = $car_brand->id;
           } else {
           $create = Brand::create([
            'name' => $vMake,
            'slug' => createSlug($vMake),
            'language_id' => 20,
            'status' => 1,
            'serial_number' => 1
            
        ]);
           $makeId = $create->id;

           }

           $vModel = ucfirst(strtolower($data['Response']['DataItems']['RoiVehicleDetails']['Model']));
           $car_model = CarModel::where('brand_id', $makeId)->first();
           if ($car_model) {
              $modelId = $car_model->id;
           } else {
           $createModel = CarModel::create([
            'name' => $vModel,
            'slug' => createSlug($vModel),
            'language_id' => 20,
            'status' => 1,
            'brand_id' => $makeId,
            'serial_number' => 1
            
        ]);
           $modelId = $createModel->id;

           }
      $apiarray["response"] = $data['Response']['StatusCode'] ;
     foreach($data['Response']['DataItems']['RoiVehicleDetails'] as $key=>$val){
        if($key =="Transmission"){
            $tData = TransmissionType::select('id', 'name')->where([['name', $val], ['status', 1]])->orderBy('serial_number', 'asc')->first();
          $apiarray[$key] =  $tData;
        }else if($key =="BodyType") {
            $BodyTypeData = BodyType::select('id', 'name')->where([['name', $val], ['status', 1]])->orderBy('serial_number', 'asc')->first();
            $apiarray[$key] =  $BodyTypeData;
        } 
        else if($key =="FuelType") {
            $FuelTypeData = FuelType::select('id', 'name')->where([['name', $val], ['status', 1]])->orderBy('serial_number', 'asc')->first();
            $apiarray[$key] =  $FuelTypeData;
        }
        else if($key =="Colour") {
            $CarColorData = CarColor::select('id', 'name')->where([['name', $val], ['status', 1]])->orderBy('serial_number', 'asc')->first();
            $apiarray[$key] =  $CarColorData;
        }  

        else{
        $apiarray[$key] = $val;
        }
      }
      $apiarray["makeID"] = $makeId;
      $apiarray["modelID"] = $modelId;
      //fajb hkdg pnnb igwo 

       
    return response()->json(['code' => 200, 'message' => 'successful.','data' =>$apiarray]); exit;
   // echo json_encode($cardata);

          
        }
  }
    public function details(Request $request)
    {
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();
        $information['ads'] = Car::with(['car_content' , 
        'galleries'])
            ->join('memberships', 'cars.vendor_id', '=', 'memberships.vendor_id')
            ->where([
                ['memberships.status', '=', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
            ])
            ->select('cars.*')
            ->where('cars.id', $request->id)->firstOrFail();
       // $information['bgImg'] = $misc->getBreadcrumb();
       // echo "<pre>";
       // print_r( $information); exit;
        $car_content = CarContent::where('language_id', $language->id)->where('car_id', $request->id)->first();
        //echo "<pre>";
       // print_r( $car_content); exit;
        if (is_null($car_content)) {
            Session::flash('message', 'No car information found for ' . $language->name . ' language');
            Session::flash('alert-type', 'warning');
           // return redirect()->route('index');
        }
        $category_id = $car_content->category_id;
        $information['language'] = $language;

        $information['specifications'] = CarSpecification::where('car_id', $request->id)->get();

        $information['related_ads'] = Car::join('car_contents', 'car_contents.car_id', 'cars.id')
            ->join('memberships', 'cars.vendor_id', '=', 'memberships.vendor_id')
            ->where([
                ['memberships.status', '=', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
            ])
            ->where('car_contents.language_id', $language->id)
            ->where('car_contents.category_id', $category_id)
            ->where('cars.id', '!=', $request->id)
            ->select('cars.*', 'car_contents.slug', 'car_contents.title', 'car_contents.language_id', 'car_contents.brand_id', 'car_contents.car_model_id')
            ->limit(8)->get();

        $information['latest_ads'] = Car::join('car_contents', 'car_contents.car_id', 'cars.id')
            ->join('memberships', 'cars.vendor_id', '=', 'memberships.vendor_id')
            ->where([
                ['memberships.status', '=', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
            ])
            ->where('car_contents.language_id', $language->id)
            ->where('cars.id', '!=', $request->id)
            ->orderBy('id', 'desc')
            ->select('cars.*', 'car_contents.slug', 'car_contents.language_id', 'car_contents.title', 'car_contents.brand_id', 'car_contents.car_model_id')
            ->limit(4)->get();

        $information['info'] = Basic::select('google_recaptcha_status')->first();
        $payload['adscommon'] = array(
            'title'=> $car_content->title,
            'price'=> $information['ads']['price'], 
            'feature_image'=>$information['ads']['feature_image'],
            'Seller'=> $information['ads']['vendor']['username'],
            'Seller email'=> $information['ads']['vendor']['email'],

            
            'description'=> strip_tags($car_content->description),
            'brnd'=> optional($car_content->brand)->name);
        //$otherfea = array();
        $otherfea = array(
            'Brand'=> optional($car_content->brand)->name,
            'Model'=> optional($car_content->model)->name,
            'Fuel Type'=> optional($car_content->fuel_type)->name,
            'Transmission Type'=> optional($car_content->transmission_type)->name,
           // 'Body Type'=> optional($car_content->body_type)->name,
            'Year'=> $information['ads']['year'],
            'mileage'=> $information['ads']['mileage'],
            

        );
         $payload['otherfeature'] = array($otherfea);
         $specArray = array();
    if (count($information['specifications']) > 0){

        foreach($information['specifications'] as $specification){
            $car_specification_content = CarSpecificationContent::where([['car_specification_id', $specification->id], ['language_id', $language->id]])->first();
            $specArray[$car_specification_content->label] =$car_specification_content->value;

               } 

        }
        $payload['specification'] = array($specArray);
        
        $payload['gallery'] = $information['ads']['galleries'];
        $payload['relatedAds'] = array($information['related_ads']);
        $payload['latestAds'] = array($information['latest_ads']);
       

        //print_r($information); 
            return response()->json(['code' => 200, 'message' => 'successful.','data' =>$payload]); exit;
       // return view('frontend.car.details', $information);
    }

    public function fetchCategory(Request $request)
    {
        $cat = new Category();
        $cat = $cat::where('parent_id', '=', $request->parent_id)->get();
        return response()->json(['code' => 200, 'message' => 'successful.','data' =>$cat]); exit;
    }
    //contact
    public function contact(Request $request)
    {
        $mail_template = MailTemplate::where('mail_type', 'inquiry_about_car')->first();

        $rules = [
            'name' => 'required',
            'car_id' => 'required',
            'email' => 'required|email:rfc,dns',
            'phone' => 'required',
            'message' => 'required'
        ];

        $info = Basic::select('google_recaptcha_status')->first();
        if ($info->google_recaptcha_status == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $messageArray = [];

        if ($info->google_recaptcha_status == 1) {
            $messageArray['g-recaptcha-response.required'] = 'Please verify that you are not a robot.';
            $messageArray['g-recaptcha-response.captcha'] = 'Captcha error! try again later or contact site admin.';
        }

        $be = Basic::select('smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name', 'to_mail', 'website_title')->firstOrFail();

        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();
        $car = Car::with(['car_content' => function ($query) use ($language) {
            return $query->where('language_id', $language->id);
        }])->where('id', $request->car_id)->first();

        $car_name = $car->car_content->title;
        $slug = $car->car_content->slug;
        $url = route('frontend.car.details', ['slug' => $slug, 'id' => $request->car_id]);


        if ($car->vendor_id != 0) {
            $vendor = Vendor::where('id', $car->vendor_id)->select('email', 'username')->first();
            $send_email_address = $vendor->email;
            $user_name = $vendor->username;
        } else {
            $send_email_address = $be->to_mail;
            $user_name = 'Admin';
        }

        $request->validate($rules, $messageArray);



        if ($be->smtp_status == 1) {
            $subject = 'Inquiry about ' . $car_name;

            $body = $mail_template->mail_body;
            $body = preg_replace("/{username}/", $user_name, $body);
            $body = preg_replace("/{car_name}/", "<a href=" . $url . ">$car_name</a>", $body);
            $body = preg_replace("/{enquirer_name}/", $request->name, $body);
            $body = preg_replace("/{enquirer_email}/", $request->email, $body);
            $body = preg_replace("/{enquirer_phone}/", $request->phone, $body);
            $body = preg_replace("/{enquirer_message}/", nl2br($request->message), $body);
            $body = preg_replace("/{website_title}/", $be->website_title, $body);

            // if smtp status == 1, then set some value for PHPMailer
            if ($be->smtp_status == 1) {
                try {
                    $smtp = [
                        'transport' => 'smtp',
                        'host' => $be->smtp_host,
                        'port' => $be->smtp_port,
                        'encryption' => $be->encryption,
                        'username' => $be->smtp_username,
                        'password' => $be->smtp_password,
                        'timeout' => null,
                        'auth_mode' => null,
                    ];
                    Config::set('mail.mailers.smtp', $smtp);
                } catch (\Exception $e) {
                    Session::flash('error', $e->getMessage());
                    return back();
                }
            }
            try {
                $data = [
                    'to' => $send_email_address,
                    'subject' => $subject,
                    'body' => $body,
                ];

                Mail::send([], [], function (Message $message) use ($data, $be) {
                    $fromMail = $be->from_mail;
                    $fromName = $be->from_name;
                    $message->to($data['to'])
                        ->subject($data['subject'])
                        ->from($fromMail, $fromName)
                        ->html($data['body'], 'text/html');
                });

                Session::flash('success', 'Message sent successfully');
                return back();
            } catch (Exception $e) {
                Session::flash('error', $e);
                return back();
            }
        }
    }

    public function store_visitor(Request $request)
    {
        $request->validate([
            'car_id'
        ]);
        $ipAddress = \Request::ip();
        $check = Visitor::where([['car_id', $request->car_id], ['ip_address', $ipAddress], ['date', Carbon::now()->format('y-m-d')]])->first();

        $car = Car::where('id', $request->car_id)->first();
        if ($car) {
            if (!$check) {
                $visitor = new Visitor();
                $visitor->car_id = $request->car_id;
                $visitor->ip_address = $ipAddress;
                $visitor->vendor_id = $car->vendor_id;
                $visitor->date = Carbon::now()->format('y-m-d');
                $visitor->save();
            }
        }
    }
}
