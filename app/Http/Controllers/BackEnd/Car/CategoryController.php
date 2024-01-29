<?php

namespace App\Http\Controllers\BackEnd\Car;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\Car\Category;
use App\Models\Car\formFields;
use App\Models\Car\formOptions;
use App\Models\Car\formData;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // first, get the language info from db
        //echo "<pre>";
        //print_r($request);
        //exit;

       
        $language = Language::where('code', $request->language)->firstOrFail();
        $information['language'] = $language;

        // then, get the equipment categories of that language from db
        if($request->pid>0) {
            $pid = $request->pid;
        }else{
           $pid = 0; 
        }

        $parr =array();
        if($request->pid>0) {
        $parent = $pid;
        repeat:
        $category = Category::find($parent);
        if ($category->parent_id != 0) {
             $parent = $category->parent_id;
             $parr[$category->id] = $category->name;
             //array_push($parr, $category->name);
             goto repeat;
        } else{
           $parr[$category->parent_id] = $category->name; 
        }
    }
        //print_r($parr);

        $information['categories'] = $language->carCategory()->where('parent_id', $pid)->orderByDesc('id')->get();

        // also, get all the languages from db
        $information['langs'] = Language::all();
        $information['breadcrumb'] = array_reverse($parr);
        $information['hiddeninput'] = $request->pid;

        return view('backend.car.category.index', $information);
    }

    public function formView()
    {

        return view('backend.car.category.formfields');
    }
    public function categoryDataAJAX()
    {
        $cat = new Category();
        $cat = $cat::where('parent_id', '=', 0)->get();
        return response($cat);
    }
    public function fetchSubCategoryDataAJAX(Request $request)
    {
        $cat = new Category();
        $cat = $cat::where('parent_id', '=', $request->cid)->get();
        return response()->json($cat);
    }

    public function saveFormStructureAJAX(Request $request)
    {
        $cid = $request->cid;
        foreach ($request->form as $data) {
            if ($data['type'] == 'select' || $data['type'] == 'radio' || $data['type'] == 'checkbox') {
                //return response()->json($data['type']); exit;
                $id = DB::table('form_fields')->insertGetId(
                    [
                        'label' => $data['label'],
                        'type' => $data['type'],
                        'category_field_id' => $cid,
                    ]
                );
                foreach ($data['option'] as $option) {
                    $newOption = new formOptions();
                    $newOption->value = $option;
                    $newOption->form_fields_id = $id;
                    $result = $newOption->save();
                    if (!$result) {
                        return response()->json('fail');
                    }
                }
            }

            if ($data['type'] == 'input' || $data['type'] == 'textarea') {
                $field = new formFields();
                $field->label = $data['label'];
                $field->type = $data['type'];
                $field->category_field_id = $cid;
                $result = $field->save();
                if (!$result) {
                    return response()->json('fail');
                }
            }
        }
        return response()->json('success');
    }
    
    public function store(Request $request)
    {
        $img = $request->file('image');
        $basicInfo = Basic::select('theme_version')->first();
        if ($basicInfo->theme_version == 1) {
            $width = '360';
            $height = '160';
        } elseif ($basicInfo->theme_version == 2) {
            $width = '40';
            $height = '40';
        } else {
            $width = '245';
            $height = '185';
        }

        $rules = [
            'language_id' => 'required',
            'name' => 'required|unique:categories|max:255',
            'image' => "required|",
            'image' => [
                "required",
                "dimensions:width=$width,height=$height",
            ],
            'status' => 'required|numeric',
            'serial_number' => 'required|numeric'
        ];

        $message = [
            'language_id.required' => 'The language field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $filename = uniqid() . '.jpg';
        $directory = public_path('assets/admin/img/car-category/');
        @mkdir($directory, 0775, true);
        $img->move($directory, $filename);

        $in = $request->all();
        $in['image'] = $filename;
        $in['slug'] = createSlug($request->name);

        Category::create($in);

        Session::flash('success', 'New Car category added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $basicInfo = Basic::select('theme_version')->first();
        if ($basicInfo->theme_version == 1) {
            $width = '360';
            $height = '160';
        } elseif ($basicInfo->theme_version == 2) {
            $width = '40';
            $height = '40';
        } else {
            $width = '245';
            $height = '185';
        }
        $rules = [
            'name' => [
                'required',
                'max:255',
                Rule::unique('categories', 'name')->ignore($request->id, 'id')
            ],
            'status' => 'required|numeric',
            'serial_number' => 'required|numeric'
        ];
        if ($request->hasFile('image')) {
            $rules['image'] = "dimensions:width=$width,height=$height";
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $category = Category::find($request->id);

        $in = $request->all();

        if ($request->hasFile('image')) {
            @unlink(public_path('assets/admin/img/car-category/') . $category->image);
            $img = $request->file('image');
            $filename = uniqid() . '.jpg';
            $directory = public_path('assets/admin/img/car-category/');
            @mkdir($directory, 0775, true);
            $img->move($directory, $filename);
            $in['image'] = $filename;
        }


        $in['slug'] = createSlug($request->name);

        $category->update($in);

        Session::flash('success', 'Car category updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        $car_contents = $category->car_contents()->get();
        foreach ($car_contents as $car_content) {
            $car_content->delete();
        }
        @unlink(public_path('assets/admin/img/car-category/') . $category->image);
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $category = Category::find($id);
            $car_contents = $category->car_contents()->get();
            foreach ($car_contents as $car_content) {
                $car_content->delete();
            }
            @unlink(public_path('assets/admin/img/car-category/') . $category->image);
            $category->delete();
        }
        Session::flash('success', 'Car categories deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
     public function fetchFormAJAX(Request $request)
    {
        $cid = $request->id;
        $formFields = formFields::where('category_field_id', '=', $cid)->get();
        $fval = 0;
        $html = '';
        foreach ($formFields as $fields) {
            $fval++;
            if ($fields->type == 'select') {
                $html .= '<div class="row mb-2" id="div' . $fval . '" name="select"><input type="hidden" value="' . $fval . '" id="fdropdownvalue' . $fval . '"><div class="col-md-2 col-3"><h6 class="mt-2">Dropdown <i class="ml-4 fa text-danger fa-trash delete" id="' . $fval . '"></i> </h6></div><div class="col-8"><div class="input-group mb-3"><input type="text" name="dropdown-label[]" class="form-control" placeholder="Enter a Label" value="' . $fields->label . '"><div class="input-group-append"><span class="input-group-text"><input name="textfield-chk[]" type="checkbox" class="" style="margin-right:5px;"> Required field</span></div></div></div><div class="col-md-2 col-1"></div><div class="row div' . $fval . '" id="divdropdown' . $fval . '">';
                $fval2 = 0;
                foreach (formOptions::where('form_fields_id', '=', $fields->form_field_id)->get() as $option) {
                    $fval2++;
                    $html .= '<div class="row div' . $fval . '" id="div' . $fval . 'dropdownrow' . $fval2 . '" style="margin-left: -5px;"><div class="col-md-2 col-3"></div><div class="col-7 mb-3"><input data-count="' . $fval2 . '" data-of="' . $fval . '" type="text" name="dropdown-child-label[]" class="form-control" value="' . $option->value . '"></div><div class="col-md-3 col-1"><i class="ml-4 fa text-danger fa-trash childdelete" id="' . $fval . 'dropdownrow' . $fval2 . '" style="margin-top:10px;"></i></div></div>';
                }
                $html .= '</div><div class="row div' . $fval . '"><div class="col-md-2 col-3"></div><div class="col-4"><button type="button" data="' . $fval . '" class="btn btn-outline-secondary btn-sm moredropdown mb-2"> <i class="fa fa-plus"></i> Drop Down</button></div></div><hr id="hr' . $fval . '" style="margin:10px;"></div>';
            } else if ($fields->type == 'input') {
                $html .= '<div class="row mb-2" id="div' . $fval . '" name="input">
                <div class="col-md-2 col-3"><h6 class="mt-2">Text Field  <i class="ml-4 fa text-danger fa-trash delete" id="' .
                    $fval . '"></i> </h6></div><div class="col-8"><div class="input-group mb-3"><input type="text" name="textfield-label[]" class="form-control" placeholder="Enter a Label" value="' . $fields->label . '"><div class="input-group-append"><span class="input-group-text"><input name="textfield-chk[]" type="checkbox" class="" style="margin-right:5px;"> Required field</span></div></div></div><div class="col-md-2 col-1"></div><hr id="hr' . $fval . '" style="margin:10px;"></div>';
            } else if ($fields->type == 'checkbox') {
                $html .= '<div class="row mb-2" id="div' . $fval . '" name="checkbox"><input type="hidden" value="' . $fval . '" id="fchkvalue' . $fval . '"><div class="col-md-2 col-3"><h6 class="mt-2">Checkbox <i class="ml-4 fa text-danger fa-trash delete" id="' . $fval . '"></i> </h6></div><div class="col-8"><div class="input-group mb-3"><input type="text" name="checkbox-label[]" class="form-control" value="' . $fields->label . '"><div class="input-group-append"><span class="input-group-text"><input name="textfield-chk[]" type="checkbox" class="" style="margin-right:5px;"> Required field</span></div></div></div><div class="col-md-2 col-1"></div><div class="row div' . $fval . '" id="divchk' . $fval . '">';
                $fval2 = 0;
                foreach (formOptions::where('form_fields_id', '=', $fields->form_field_id)->get() as $option) {
                    $fval2++;
                    $html .= '<div class="row div' . $fval . '" id="div' . $fval . 'chkrow' . $fval2 . '" style="margin-left: -5px;"><div class="col-md-2 col-3"></div><div class="col-7 mb-3"><input type="text" name="checkbox-child-label[]" class="form-control" value="' . $option->value . '"></div><div class="col-md-3 col-1"><i class="ml-4 fa text-danger fa-trash childdelete" id="' . $fval . 'chkrow' . $fval2 . '" style="margin-top:10px;"></i></div></div>';
                }
                $html .= '</div><div class="row div' . $fval . '"><div class="col-md-2 col-3"></div><div class="col-4"><button type="button" data="' . $fval . '" class="btn btn-outline-secondary btn-sm morechk mb-2"> <i class="fa fa-plus"></i> Checkbox</button></div></div><hr id="hr' . $fval . '" style="margin:10px;"></div>';
            } else if ($fields->type == 'textarea') {
                $html .= '<div class="row mb-2" id="div' . $fval . '"  name="textarea"><div class="col-md-2 col-3"><h6 class="mt-2">Text Area  <i class="ml-4 fa text-danger fa-trash delete" id=""></i> </h6></div><div class="col-8"><div class="input-group mb-3"><input type="text" name="textarea-label[]" class="form-control" placeholder="Enter a Label" value="' . $fields->label . '"><div class="input-group-append"><span class="input-group-text"><input name="textarea-chk[]" type="checkbox" class="" style="margin-right:5px;"> Required field</span></div></div></div><div class="col-md-2 col-1"></div><hr id="hr" style="margin:10px;"></div>';
            } else if ($fields->type == 'radio') {
                $html .= '<div class="row mb-2" id="div' . $fval . '" name="radio"><input type="hidden" value="' . $fval . '" id="fradiovalue' . $fval . '"><div class="col-md-2 col-3"><h6 class="mt-2">Radio Button <i class="ml-4 fa text-danger fa-trash delete" id="' . $fval . '"></i> </h6></div><div class="col-8"><div class="input-group mb-3"><input type="text" name="radio-label[]" class="form-control" value="' . $fields->label . '" value=""><div class="input-group-append"><span class="input-group-text"><input name="textfield-chk[]" type="checkbox" class="" style="margin-right:5px;"> Required field</span></div></div></div><div class="col-md-2 col-1"></div><div class="row div' . $fval . '" id="divradio' . $fval . '">';
                $fval2 = 0;
                foreach (formOptions::where('form_fields_id', '=', $fields->form_field_id)->get() as $option) {
                    $fval2++;
                    $html .= '<div class="row div' . $fval . '" id="div' . $fval . 'radiorow' . $fval2 . '" style="margin-left: -5px;"><div class="col-md-2 col-3"></div><div class="col-7 mb-3"><input type="text" name="radio-child-label[]" class="form-control" value="' . $option->value . '"></div><div class="col-md-3 col-1"><i class="ml-4 fa text-danger fa-trash childdelete" id="' . $fval . 'radiorow' . $fval2 . '"" style="margin-top:10px;"></i></div></div>';
                }
                $html .= '</div><div class="row div' . $fval . '"><div class="col-md-2 col-3"></div><div class="col-4"><button type="button" data="' . $fval . '" class="btn btn-outline-secondary btn-sm moreradio mb-2"> <i class="fa fa-plus"></i> Radio Button</button></div></div><hr id="hr' . $fval . '" style="margin:10px;"></div>';
            }
        }
        return response()->json(['html' => $html, 'divlen' => $fval]);
    }
}
