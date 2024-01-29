<?php

namespace App\Http\Controllers\BackEnd\Car;

use App\Http\Controllers\Controller;
use App\Models\Car\FuelType;
use Illuminate\Http\Request;
use App\Models\Language;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FuelTypeController extends Controller
{
    public function index(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)->firstOrFail();
        $information['language'] = $language;

        // then, get the equipment categories of that language from db
        $information['carFuelTypes'] = $language->fuelType()->orderByDesc('id')->get();

        // also, get all the languages from db
        $information['langs'] = Language::all();

        return view('backend.car.fuel-type.index', $information);
    }

    public function store(Request $request)
    {
        $rules = [
            'language_id' => 'required',
            'name' => 'required|unique:fuel_types|max:255',
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

        FuelType::create($request->except('slug') + [
            'slug' => createSlug($request->name)
        ]);

        Session::flash('success', 'New Car Fuel Type added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => [
                'required',
                'max:255',
                Rule::unique('fuel_types', 'name')->ignore($request->id, 'id')
            ],
            'status' => 'required|numeric',
            'serial_number' => 'required|numeric'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $fuel_types = FuelType::find($request->id);

        $fuel_types->update($request->except('slug') + [
            'slug' => createSlug($request->name)
        ]);

        Session::flash('success', 'Car Fuel Type updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $fuel_types = FuelType::find($id);
        $fuel_types->delete();
        return redirect()->back()->with('success', 'Car Fuel Type deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $fuel_types = FuelType::find($id);
            $fuel_types->delete();
        }
        Session::flash('success', 'Car Fuel Types deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
}
