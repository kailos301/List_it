<?php

namespace App\Http\Controllers\BackEnd\Car;

use App\Http\Controllers\Controller;
use App\Models\Car\CarColor;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ConditionController extends Controller
{
    public function index(Request $request)
    {
        // first, get the language info from db
        $language = Language::where('code', $request->language)->firstOrFail();
        $information['language'] = $language;

        // then, get the equipment categories of that language from db
        $information['CarColors'] = $language->CarColor()->orderByDesc('id')->get();

        // also, get all the languages from db
        $information['langs'] = Language::all();

        return view('backend.car.condition.index', $information);
    }

    public function store(Request $request)
    {
        $rules = [
            'language_id' => 'required',
            'name' => 'required|unique:car_conditions|max:255',
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

        CarColor::create($request->except('slug') + [
            'slug' => createSlug($request->name)
        ]);

        Session::flash('success', 'New Car Condition added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => [
                'required',
                'max:255',
                Rule::unique('car_conditions', 'name')->ignore($request->id, 'id')
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

        $condition = CarColor::find($request->id);

        $condition->update($request->except('slug') + [
            'slug' => createSlug($request->name)
        ]);

        Session::flash('success', 'Car Condition updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $condition = CarColor::find($id);
        $condition->delete();
        return redirect()->back()->with('success', 'Category deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $condition = CarColor::find($id);
            $condition->delete();
        }
        Session::flash('success', 'Car Conditon deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
}
