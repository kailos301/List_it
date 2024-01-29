<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\Instrument\Location;
use App\Models\Language;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        // first, get the language info from db
        $language = Language::query()->where('code', '=', $request->language)->first();
        $information['language'] = $language;

        // then, get the locations of that language from db
        $information['locations'] = $language->location()->where('vendor_id', Auth::guard('vendor')->user()->id)->orderByDesc('id')->get();

        $information['currencyInfo'] = $this->getCurrencyInfo();

        // also, get all the languages from db
        $information['langs'] = Language::all();

        $information['twoWayDeliveryStatus'] = Vendor::query()->where('id', Auth::guard('vendor')->user()->id)->pluck('two_way_delivery_status')->first();

        return view('vendors.location.index', $information);
    }

    public function store(Request $request)
    {
        $twoWayDeliveryStatus = Vendor::query()->where('id', Auth::guard('vendor')->user()->id)->pluck('two_way_delivery_status')->first();

        $rules = [
            'language_id' => 'required',
            'name' => 'required',
            'charge' => $twoWayDeliveryStatus == 1 ? 'required' : '',
            'serial_number' => 'required'
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
        $in = $request->all();

        $in['vendor_id'] = Auth::guard('vendor')->user()->id;

        Location::create($in);

        $request->session()->flash('success', 'New location added successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $twoWayDeliveryStatus = Vendor::query()->where('id', Auth::guard('vendor')->user()->id)->pluck('two_way_delivery_status')->first();

        $rules = [
            'name' => 'required',
            'charge' => $twoWayDeliveryStatus == 1 ? 'required' : '',
            'serial_number' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $location = Location::query()->find($request->id);

        $in = $request->all();
        $in['vendor_id'] = Auth::guard('vendor')->user()->id;

        $location->update($in);

        $request->session()->flash('success', 'Location updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function destroy($id)
    {
        $location = Location::query()->find($id);

        $location->delete();

        return redirect()->back()->with('success', 'Location deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $location = Location::query()->find($id);

            $location->delete();
        }

        $request->session()->flash('success', 'Locations deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
}
