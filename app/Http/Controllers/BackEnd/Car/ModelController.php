<?php

namespace App\Http\Controllers\BackEnd\Car;

use App\Http\Controllers\Controller;
use App\Models\Car\CarModel;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ModelController extends Controller
{
    public function index(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)->firstOrFail();
        $information['language'] = $language;

        // then, get the equipment categories of that language from db
        $information['carModels'] = $language->carModel()->with('brand')->orderByDesc('id')->get();

        $information['brands'] = $language->carBrand()->orderByDesc('id')->get();

        // also, get all the languages from db
        $information['langs'] = Language::all();

        return view('backend.car.car_model.index', $information);
    }

    public function store(Request $request)
    {
        $rules = [
            'm_language_id' => 'required',
            'brand_id' => 'required',
            'name' => 'required|unique:car_models|max:255',
            'status' => 'required|numeric',
            'serial_number' => 'required|numeric'
        ];

        $message = [
            'm_language_id.required' => 'The language field is required.'
        ];
        $message = [
            'brand_id.required' => 'The language field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $message);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        CarModel::create($request->except('slug') + [
            'language_id' => $request->m_language_id,
            'slug' => createSlug($request->name)
        ]);

        Session::flash('success', 'New Car Model added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => [
                'required',
                'max:255',
                Rule::unique('car_models', 'name')->ignore($request->id, 'id')
            ],
            'brand_id' => 'required',
            'status' => 'required|numeric',
            'serial_number' => 'required|numeric'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $model = CarModel::find($request->id);

        $model->update($request->except('slug') + [
            'slug' => createSlug($request->name)
        ]);

        Session::flash('success', 'Car Model updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $model = CarModel::find($id);
        $model->delete();
        return redirect()->back()->with('success', 'Car Model deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $model = CarModel::find($id);
            $model->delete();
        }
        Session::flash('success', 'Car Model deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
}
