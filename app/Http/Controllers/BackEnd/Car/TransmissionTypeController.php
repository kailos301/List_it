<?php

namespace App\Http\Controllers\BackEnd\Car;

use App\Http\Controllers\Controller;
use App\Models\Car\TransmissionType;
use Illuminate\Http\Request;
use App\Models\Language;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TransmissionTypeController extends Controller
{
    public function index(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)->firstOrFail();
        $information['language'] = $language;

        // then, get the equipment categories of that language from db
        $information['carTransmissionTypes'] = $language->TransmissionType()->orderByDesc('id')->get();

        // also, get all the languages from db
        $information['langs'] = Language::all();

        return view('backend.car.transmission.index', $information);
    }

    public function store(Request $request)
    {
        $rules = [
            'language_id' => 'required',
            'name' => 'required|unique:transmission_types|max:255',
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

        TransmissionType::create($request->except('slug') + [
            'slug' => createSlug($request->name)
        ]);

        Session::flash('success', 'New Car Transmission Type added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => [
                'required',
                'max:255',
                Rule::unique('transmission_types', 'name')->ignore($request->id, 'id')
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

        $transmission_types = TransmissionType::find($request->id);

        $transmission_types->update($request->except('slug') + [
            'slug' => createSlug($request->name)
        ]);

        Session::flash('success', 'Car Transmission Type updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $transmission_types = TransmissionType::find($id);
        $transmission_types->delete();
        return redirect()->back()->with('success', 'Car Transmission Type deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $transmission_types = TransmissionType::find($id);
            $transmission_types->delete();
        }
        Session::flash('success', 'Car Transmission Types deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
}
