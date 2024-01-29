<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Package\PackageStoreRequest;
use App\Http\Requests\Package\PackageUpdateRequest;
use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class PackageController extends Controller
{
    public function settings()
    {
        $data['abe'] = Basic::first();
        return view('backend.packages.settings', $data);
    }

    public function updateSettings(Request $request)
    {
        $be = Basic::first();
        $be->expiration_reminder = $request->expiration_reminder;
        $be->save();

        Session::flash('success', 'Settings updated successfully!');
        return back();
    }

    /**
     * Display a listing of the resource.
     *
     *
     */
    public function index(Request $request)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $search = $request->search;
        $data['bex'] = $currentLang->basic_extended;
        $data['packages'] = Package::query()->when($search, function ($query, $search) {
            return $query->where('title', 'like', '%' . $search . '%');
        })->orderBy('created_at', 'DESC')->get();
        return view('backend.packages.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     */
    public function store(PackageStoreRequest $request)
    {
        try {
            if (!isset($request->featured)) $request["featured"] = "0";
            return DB::transaction(function () use ($request) {
                Package::create($request->all());
                Session::flash('success', "Package Created Successfully");
                return Response::json(['status' => 'success'], 200);
            });
        } catch (\Throwable $e) {
            return $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return
     */
    public function edit($id)
    {
        if (session()->has('lang')) {
            $currentLang = Language::where('code', session()->get('lang'))->first();
        } else {
            $currentLang = Language::where('is_default', 1)->first();
        }
        $data['bex'] = $currentLang->basic_extended;
        $data['package'] = Package::query()->findOrFail($id);
        return view("backend.packages.edit", $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     */
    public function update(PackageUpdateRequest $request)
    {
        try {
            if (!array_key_exists('is_trial', $request->all())) {
                $request['is_trial'] = "0";
                $request['trial_days'] = 0;
            }
            if (!isset($request->featured)) $request["featured"] = "0";
            return DB::transaction(function () use ($request) {
                Package::query()->findOrFail($request->package_id)
                    ->update($request->all());
                Session::flash('success', "Package Update Successfully");
                return Response::json(['status' => 'success'], 200);
            });
        } catch (\Throwable $e) {
            return $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $package = Package::query()->findOrFail($request->package_id);
                if ($package->memberships()->count() > 0) {
                    foreach ($package->memberships as $key => $membership) {
                        @unlink(public_path('assets/front/img/membership/receipt/') . $membership->receipt);
                        $membership->delete();
                    }
                }
                $admin_membership = Membership::where('vendor_id', 0)->first();
                if ($admin_membership) {
                    if ($admin_membership->package_id == $package->id) {
                        $lifetime_package = Package::where('term', 'lifetime')->first();
                        if (!$lifetime_package) {
                            $lifetime_package = Package::first();
                        }
                        $admin_membership->package_id = $lifetime_package->id;
                        $admin_membership->save();
                    }
                }
                $package->delete();
                Session::flash('success', 'Package deleted successfully!');
                return back();
            });
        } catch (\Throwable $e) {
            return $e;
        }
    }

    public function bulkDelete(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $ids = $request->ids;
                foreach ($ids as $id) {
                    $package = Package::query()->findOrFail($id);
                    if ($package->memberships()->count() > 0) {
                        foreach ($package->memberships as $key => $membership) {
                            @unlink(public_path('assets/front/img/membership/receipt/') . $membership->receipt);
                            $membership->delete();
                        }
                    }
                    //if admin delete admin
                    $admin_membership = Membership::where('vendor_id', 0)->first();
                    if ($admin_membership) {
                        if ($admin_membership->package_id == $package->id) {
                            $lifetime_package = Package::where('term', 'lifetime')->first();
                            if (!$lifetime_package) {
                                $lifetime_package = Package::first();
                            }
                            $admin_membership->package_id = $lifetime_package->id;
                            $admin_membership->save();
                        }
                    }
                    $package->delete();
                }
                Session::flash('success', 'Package bulk deletion is successful!');
                return "success";
            });
        } catch (\Throwable $e) {
            return $e;
        }
    }
}
