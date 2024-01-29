<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Http\Helpers\MegaMailer;
use App\Http\Helpers\VendorPermissionHelper;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\Car;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\SupportTicket;
use App\Models\Vendor;
use App\Models\VendorInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VendorManagementController extends Controller
{
    public function settings()
    {
        $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_email_verification', 'vendor_admin_approval', 'admin_approval_notice')->first();
        return view('backend.end-user.vendor.settings', compact('setting'));
    }
    //update_setting
    public function update_setting(Request $request)
    {
        if ($request->vendor_email_verification) {
            $vendor_email_verification = 1;
        } else {
            $vendor_email_verification = 0;
        }
        if ($request->vendor_admin_approval) {
            $vendor_admin_approval = 1;
        } else {
            $vendor_admin_approval = 0;
        }
        // finally, store the favicon into db
        DB::table('basic_settings')->updateOrInsert(
            ['uniqid' => 12345],
            [
                'vendor_email_verification' => $vendor_email_verification,
                'vendor_admin_approval' => $vendor_admin_approval,
                'admin_approval_notice' => $request->admin_approval_notice,
            ]
        );

        Session::flash('success', 'Update Settings Successfully!');
        return back();
    }
    public function index(Request $request)
    {
        $searchKey = null;

        if ($request->filled('info')) {
            $searchKey = $request['info'];
        }

        $vendors = Vendor::when($searchKey, function ($query, $searchKey) {
            return $query->where('username', 'like', '%' . $searchKey . '%')
                ->orWhere('email', 'like', '%' . $searchKey . '%');
        })
            ->where('id', '!=', 0)
            ->orderBy('id', 'desc')
            ->paginate(10);


        return view('backend.end-user.vendor.index', compact('vendors'));
    }

    //add
    public function add(Request $request)
    {
        // first, get the language info from db
        $language = Language::query()->where('code', '=', $request->language)->first();
        $information['language'] = $language;
        $information['languages'] = Language::get();
        return view('backend.end-user.vendor.create', $information);
    }
    public function create(Request $request)
    {
        $admin = Admin::select('username')->first();
        $admin_username = $admin->username;
        $rules = [
            'username' => "required|unique:vendors|not_in:$admin_username",
            'email' => 'required|email|unique:vendors',
            'password' => 'required|min:6',
        ];


        $languages = Language::get();
        foreach ($languages as $language) {
            $rules[$language->code . '_name'] = 'required';
        }
        $messages = [];
        foreach ($languages as $language) {
            $messages[$language->code . '_name.required'] = 'The name feild is required';
        }



        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }

        $in = $request->all();
        $in['password'] = Hash::make($request->password);
        $in['status'] = 1;

        $file = $request->file('photo');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $directory = public_path('assets/admin/img/vendor-photo/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $file->move($directory, $fileName);
            $in['photo'] = $fileName;
        }
        $in['email_verified_at'] = Carbon::now();
        $vendor = Vendor::create($in);

        $vendor_id = $vendor->id;
        foreach ($languages as $language) {
            $vendorInfo = new VendorInfo();
            $vendorInfo->language_id = $language->id;
            $vendorInfo->vendor_id = $vendor_id;
            $vendorInfo->name = $request[$language->code . '_name'];
            $vendorInfo->country = $request[$language->code . '_country'];
            $vendorInfo->city = $request[$language->code . '_city'];
            $vendorInfo->state = $request[$language->code . '_state'];
            $vendorInfo->zip_code = $request[$language->code . '_zip_code'];
            $vendorInfo->address = $request[$language->code . '_address'];
            $vendorInfo->details = $request[$language->code . '_details'];
            $vendorInfo->save();
        }


        Session::flash('success', 'Add Vendor Successfully!');
        return Response::json(['status' => 'success'], 200);
    }

    public function show($id)
    {

        $information['langs'] = Language::all();

        $currency_info = $this->getCurrencyInfo();
        $information['currency_info'] = $currency_info;

        $language = Language::where('code', request()->input('language'))->firstOrFail();
        $information['language'] = $language;
        $vendor = Vendor::with([
            'vendor_info' => function ($query) use ($language) {
                return $query->where('language_id', $language->id);
            }
        ])->where('id', $id)->firstOrFail();
        $information['vendor'] = $vendor;

        $information['langs'] = Language::all();
        $information['packages'] = Package::query()->where('status', '1')->get();
        $online = OnlineGateway::query()->where('status', 1)->get();
        $offline = OfflineGateway::where('status', 1)->get();
        $information['gateways'] = $online->merge($offline);

        $information['cars'] = Car::with([
            'car_content' => function ($q) use ($language) {
                $q->where('language_id', $language->id);
            },
        ])->where('vendor_id', $id)
            ->orderBy('id', 'desc')
            ->get();

        return view('backend.end-user.vendor.details', $information);
    }
    public function updateAccountStatus(Request $request, $id)
    {

        $user = Vendor::find($id);
        if ($request->account_status == 1) {
            $user->update(['status' => 1]);
        } else {
            $user->update(['status' => 0]);
        }
        Session::flash('success', 'Account status updated successfully!');

        return redirect()->back();
    }

    public function updateEmailStatus(Request $request, $id)
    {
        $vendor = Vendor::find($id);
        if ($request->email_status == 1) {
            $vendor->update(['email_verified_at' => now()]);
        } else {
            $vendor->update(['email_verified_at' => NULL]);
        }
        Session::flash('success', 'Email status updated successfully!');

        return redirect()->back();
    }
    public function changePassword($id)
    {
        $userInfo = Vendor::findOrFail($id);

        return view('backend.end-user.vendor.change-password', compact('userInfo'));
    }
    public function updatePassword(Request $request, $id)
    {
        $rules = [
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ];

        $messages = [
            'new_password.confirmed' => 'Password confirmation does not match.',
            'new_password_confirmation.required' => 'The confirm new password field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $user = Vendor::find($id);

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        Session::flash('success', 'Password updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }

    public function edit($id)
    {
        $information['languages'] = Language::get();
        $vendor = Vendor::where('id', $id)->firstOrFail();
        $information['vendor'] = $vendor;
        $information['currencyInfo'] = $this->getCurrencyInfo();
        return view('backend.end-user.vendor.edit', $information);
    }

    //update
    public function update(Request $request, $id, Vendor $vendor)
    {
        $rules = [

            'username' => [
                'required',
                'not_in:admin',
                Rule::unique('vendors', 'username')->ignore($id),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('vendors', 'email')->ignore($id)
            ]
        ];

        if ($request->hasFile('photo')) {
            $rules['photo'] = 'mimes:png,jpeg,jpg|dimensions:min_width=80,max_width=80,min_width=80,min_height=80';
        }

        $languages = Language::get();
        foreach ($languages as $language) {
            $rules[$language->code . '_name'] = 'required';
        }

        $messages = [];

        foreach ($languages as $language) {
            $messages[$language->code . '_name.required'] = 'The name field is required.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()
            ], 400);
        }


        $in = $request->all();
        $vendor  = Vendor::where('id', $id)->first();
        $file = $request->file('photo');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $directory = public_path('assets/admin/img/vendor-photo/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $file->move($directory, $fileName);

            @unlink(public_path('assets/admin/img/vendor-photo/') . $vendor->photo);
            $in['photo'] = $fileName;
        }


        if ($request->show_email_addresss) {
            $in['show_email_addresss'] = 1;
        } else {
            $in['show_email_addresss'] = 0;
        }
        if ($request->show_phone_number) {
            $in['show_phone_number'] = 1;
        } else {
            $in['show_phone_number'] = 0;
        }
        if ($request->show_contact_form) {
            $in['show_contact_form'] = 1;
        } else {
            $in['show_contact_form'] = 0;
        }



        $vendor->update($in);

        $languages = Language::get();
        $vendor_id = $vendor->id;
        foreach ($languages as $language) {
            $vendorInfo = VendorInfo::where('vendor_id', $vendor_id)->where('language_id', $language->id)->first();
            if ($vendorInfo == NULL) {
                $vendorInfo = new VendorInfo();
            }
            $vendorInfo->language_id = $language->id;
            $vendorInfo->vendor_id = $vendor_id;
            $vendorInfo->name = $request[$language->code . '_name'];
            $vendorInfo->country = $request[$language->code . '_country'];
            $vendorInfo->city = $request[$language->code . '_city'];
            $vendorInfo->state = $request[$language->code . '_state'];
            $vendorInfo->zip_code = $request[$language->code . '_zip_code'];
            $vendorInfo->address = $request[$language->code . '_address'];
            $vendorInfo->details = $request[$language->code . '_details'];
            $vendorInfo->save();
        }
        Session::flash('success', 'Vendor updated successfully!');

        return Response::json(['status' => 'success'], 200);
    }


    public function sendMail($memb, $package, $paymentMethod, $vendor, $bs, $mailType, $replacedPackage = NULL, $removedPackage = NULL)
    {

        if ($mailType != 'admin_removed_current_package' && $mailType != 'admin_removed_next_package') {
            $transaction_id = VendorPermissionHelper::uniqidReal(8);
            $activation = $memb->start_date;
            $expire = $memb->expire_date;
            $info['start_date'] = $activation->toFormattedDateString();
            $info['expire_date'] = $expire->toFormattedDateString();
            $info['payment_method'] = $paymentMethod;
            $lastMemb = $vendor->memberships()->orderBy('id', 'DESC')->first();

            $file_name = $this->makeInvoice($info, "membership", $vendor, NULL, $package->price, "Stripe", $vendor->phone, $bs->base_currency_symbol_position, $bs->base_currency_symbol, $bs->base_currency_text, $transaction_id, $package->title, $lastMemb);
        }

        $mailer = new MegaMailer();
        $data = [
            'toMail' => $vendor->email,
            'toName' => $vendor->username,
            'username' => $vendor->username,
            'website_title' => $bs->website_title,
            'templateType' => $mailType
        ];

        if ($mailType != 'admin_removed_current_package' && $mailType != 'admin_removed_next_package') {
            $data['package_title'] = $package->title;
            $data['package_price'] = ($bs->base_currency_text_position == 'left' ? $bs->base_currency_text . ' ' : '') . $package->price . ($bs->base_currency_text_position == 'right' ? ' ' . $bs->base_currency_text : '');
            $data['activation_date'] = $activation->toFormattedDateString();
            $data['expire_date'] = Carbon::parse($expire->toFormattedDateString())->format('Y') == '9999' ? 'Lifetime' : $expire->toFormattedDateString();
            $data['membership_invoice'] = $file_name;
        }
        if ($mailType != 'admin_removed_current_package' || $mailType != 'admin_removed_next_package') {
            $data['removed_package_title'] = $removedPackage;
        }

        if (!empty($replacedPackage)) {
            $data['replaced_package'] = $replacedPackage;
        }

        $mailer->mailFromAdmin($data);
        @unlink(public_path('assets/front/invoices/' . $file_name));
    }

    public function addCurrPackage(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::where('id', $vendor_id)->first();
        $bs = Basic::first();

        $selectedPackage = Package::find($request->package_id);

        // calculate expire date for selected package
        if ($selectedPackage->term == 'monthly') {
            $exDate = Carbon::now()->addMonth()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'yearly') {
            $exDate = Carbon::now()->addYear()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'lifetime') {
            $exDate = Carbon::maxValue()->format('d-m-Y');
        }
        // store a new membership for selected package
        $selectedMemb = Membership::create([
            'price' => $selectedPackage->price,
            'currency' => $bs->base_currency_text,
            'currency_symbol' => $bs->base_currency_symbol,
            'payment_method' => $request->payment_method,
            'transaction_id' => uniqid(),
            'status' => 1,
            'receipt' => NULL,
            'transaction_details' => NULL,
            'settings' => null,
            'package_id' => $selectedPackage->id,
            'vendor_id' => $vendor_id,
            'start_date' => Carbon::parse(Carbon::now()->format('d-m-Y')),
            'expire_date' => Carbon::parse($exDate),
            'is_trial' => 0,
            'trial_days' => 0,
        ]);

        $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $vendor, $bs, 'admin_added_current_package');

        Session::flash('success', 'Current Package has been added successfully!');
        return back();
    }


    public function changeCurrPackage(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::findOrFail($vendor_id);
        $currMembership = VendorPermissionHelper::currMembOrPending($vendor_id);
        $nextMembership = VendorPermissionHelper::nextMembership($vendor_id);

        $bs = Basic::first();

        $selectedPackage = Package::find($request->package_id);

        // if the vendor has a next package to activate & selected package is 'lifetime' package
        if (!empty($nextMembership) && $selectedPackage->term == 'lifetime') {
            Session::flash('warning', 'To add a Lifetime package as Current Package, You have to remove the next package');
            return back();
        }

        // expire the current package
        $currMembership->expire_date = Carbon::parse(Carbon::now()->subDay()->format('d-m-Y'));
        $currMembership->modified = 1;
        if ($currMembership->status == 0) {
            $currMembership->status = 2;
        }
        $currMembership->save();

        // calculate expire date for selected package
        if ($selectedPackage->term == 'monthly') {
            $exDate = Carbon::now()->addMonth()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'yearly') {
            $exDate = Carbon::now()->addYear()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'lifetime') {
            $exDate = Carbon::maxValue()->format('d-m-Y');
        }
        // store a new membership for selected package
        $selectedMemb = Membership::create([
            'price' => $selectedPackage->price,
            'currency' => $bs->base_currency_text,
            'currency_symbol' => $bs->base_currency_symbol,
            'payment_method' => $request->payment_method,
            'transaction_id' => uniqid(),
            'status' => 1,
            'receipt' => NULL,
            'transaction_details' => NULL,
            'settings' => null,
            'package_id' => $selectedPackage->id,
            'vendor_id' => $vendor_id,
            'start_date' => Carbon::parse(Carbon::now()->format('d-m-Y')),
            'expire_date' => Carbon::parse($exDate),
            'is_trial' => 0,
            'trial_days' => 0,
        ]);

        // if the user has a next package to activate & selected package is not 'lifetime' package
        if (!empty($nextMembership) && $selectedPackage->term != 'lifetime') {
            $nextPackage = Package::find($nextMembership->package_id);

            // calculate & store next membership's start_date
            $nextMembership->start_date = Carbon::parse(Carbon::parse($exDate)->addDay()->format('d-m-Y'));

            // calculate & store expire date for next membership
            if ($nextPackage->term == 'monthly') {
                $exDate = Carbon::parse(Carbon::parse(Carbon::parse($exDate)->addDay()->format('d-m-Y'))->addMonth()->format('d-m-Y'));
            } elseif ($nextPackage->term == 'yearly') {
                $exDate = Carbon::parse(Carbon::parse(Carbon::parse($exDate)->addDay()->format('d-m-Y'))->addYear()->format('d-m-Y'));
            } else {
                $exDate = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
            }
            $nextMembership->expire_date = $exDate;
            $nextMembership->save();
        }

        $currentPackage = Package::select('title')->findOrFail($currMembership->package_id);
        $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $vendor, $bs, 'admin_changed_current_package', $currentPackage->title);


        Session::flash('success', 'Current Package changed successfully!');
        return back();
    }

    public function removeCurrPackage(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::where('id', $vendor_id)->firstOrFail();
        $currMembership = VendorPermissionHelper::currMembOrPending($vendor_id);
        $currPackage = Package::select('title')->findOrFail($currMembership->package_id);
        $nextMembership = VendorPermissionHelper::nextMembership($vendor_id);
        $bs = Basic::first();

        $today = Carbon::now();

        // just expire the current package
        $currMembership->expire_date = $today->subDay();
        $currMembership->modified = 1;
        if ($currMembership->status == 0) {
            $currMembership->status = 2;
        }
        $currMembership->save();

        // if next package exists
        if (!empty($nextMembership)) {
            $nextPackage = Package::find($nextMembership->package_id);

            $nextMembership->start_date = Carbon::parse(Carbon::today()->format('d-m-Y'));
            if ($nextPackage->term == 'monthly') {
                $nextMembership->expire_date = Carbon::parse(Carbon::today()->addMonth()->format('d-m-Y'));
            } elseif ($nextPackage->term == 'yearly') {
                $nextMembership->expire_date = Carbon::parse(Carbon::today()->addYear()->format('d-m-Y'));
            } elseif ($nextPackage->term == 'lifetime') {
                $nextMembership->expire_date = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
            }
            $nextMembership->save();
        }

        $this->sendMail(NULL, NULL, $request->payment_method, $vendor, $bs,  'admin_removed_current_package', NULL, $currPackage->title);

        Session::flash('success', 'Current Package removed successfully!');
        return back();
    }

    public function addNextPackage(Request $request)
    {
        $vendor_id = $request->vendor_id;

        $hasPendingMemb = VendorPermissionHelper::hasPendingMembership($vendor_id);
        if ($hasPendingMemb) {
            Session::flash('warning', 'This user already has a Pending Package. Please take an action (change / remove / approve / reject) for that package first.');
            return back();
        }

        $currMembership = VendorPermissionHelper::userPackage($vendor_id);
        $currPackage = Package::find($currMembership->package_id);
        $vendor = Vendor::where('id', $vendor_id)->first();
        $bs = Basic::first();

        $selectedPackage = Package::find($request->package_id);

        if ($currMembership->is_trial == 1) {
            Session::flash('warning', 'If your current package is trial package, then you have to change / remove the current package first.');
            return back();
        }


        // if current package is not lifetime package
        if ($currPackage->term != 'lifetime') {
            // calculate expire date for selected package
            if ($selectedPackage->term == 'monthly') {
                $exDate = Carbon::parse($currMembership->expire_date)->addDay()->addMonth()->format('d-m-Y');
            } elseif ($selectedPackage->term == 'yearly') {
                $exDate = Carbon::parse($currMembership->expire_date)->addDay()->addYear()->format('d-m-Y');
            } elseif ($selectedPackage->term == 'lifetime') {
                $exDate = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
            }
            // store a new membership for selected package
            $selectedMemb = Membership::create([
                'price' => $selectedPackage->price,
                'currency' => $bs->base_currency_text,
                'currency_symbol' => $bs->base_currency_symbol,
                'payment_method' => $request->payment_method,
                'transaction_id' => uniqid(),
                'status' => 1,
                'receipt' => NULL,
                'transaction_details' => NULL,
                'settings' => null,
                'package_id' => $selectedPackage->id,
                'vendor_id' => $vendor_id,
                'start_date' => Carbon::parse(Carbon::parse($currMembership->expire_date)->addDay()->format('d-m-Y')),
                'expire_date' => Carbon::parse($exDate),
                'is_trial' => 0,
                'trial_days' => 0,
            ]);

            $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $vendor, $bs, 'admin_added_next_package');
        } else {
            Session::flash('warning', 'If your current package is lifetime package, then you have to change / remove the current package first.');
            return back();
        }


        Session::flash('success', 'Next Package has been added successfully!');
        return back();
    }

    public function changeNextPackage(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::where('id', $vendor_id)->first();
        $bs = Basic::first();
        $nextMembership = VendorPermissionHelper::nextMembership($vendor_id);
        $nextPackage = Package::find($nextMembership->package_id);
        $selectedPackage = Package::find($request->package_id);

        $prevStartDate = $nextMembership->start_date;
        // set the start_date to unlimited
        $nextMembership->start_date = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
        $nextMembership->modified = 1;
        $nextMembership->save();

        // calculate expire date for selected package
        if ($selectedPackage->term == 'monthly') {
            $exDate = Carbon::parse($prevStartDate)->addMonth()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'yearly') {
            $exDate = Carbon::parse($prevStartDate)->addYear()->format('d-m-Y');
        } elseif ($selectedPackage->term == 'lifetime') {
            $exDate = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
        }

        // store a new membership for selected package
        $selectedMemb = Membership::create([
            'price' => $selectedPackage->price,
            'currency' => $bs->base_currency_text,
            'currency_symbol' => $bs->base_currency_symbol,
            'payment_method' => $request->payment_method,
            'transaction_id' => uniqid(),
            'status' => 1,
            'receipt' => NULL,
            'transaction_details' => NULL,
            'settings' => json_encode($bs),
            'package_id' => $selectedPackage->id,
            'vendor_id' => $vendor_id,
            'start_date' => Carbon::parse($prevStartDate),
            'expire_date' => Carbon::parse($exDate),
            'is_trial' => 0,
            'trial_days' => 0,
        ]);

        $this->sendMail($selectedMemb, $selectedPackage, $request->payment_method, $vendor, $bs, 'admin_changed_next_package', $nextPackage->title);

        Session::flash('success', 'Next Package changed successfully!');
        return back();
    }

    public function removeNextPackage(Request $request)
    {
        $vendor_id = $request->vendor_id;
        $vendor = Vendor::where('id', $vendor_id)->first();
        $bs = Basic::first();
        $nextMembership = VendorPermissionHelper::nextMembership($vendor_id);
        // set the start_date to unlimited
        $nextMembership->start_date = Carbon::parse(Carbon::maxValue()->format('d-m-Y'));
        $nextMembership->modified = 1;
        $nextMembership->save();

        $nextPackage = Package::select('title')->findOrFail($nextMembership->package_id);


        $this->sendMail(NULL, NULL, $request->payment_method, $vendor, $bs, 'admin_removed_next_package', NULL, $nextPackage->title);

        Session::flash('success', 'Next Package removed successfully!');
        return back();
    }

    //secrtet login
    public function secret_login($id)
    {
        Session::put('secret_login', 1);
        $vendor = Vendor::where('id', $id)->first();
        Auth::guard('vendor')->login($vendor);
        return redirect()->route('vendor.dashboard');
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        // vendor memeberships
        $memberships = $vendor->memberships()->get();
        foreach ($memberships as $membership) {
            @unlink(public_path('assets/front/img/membership/receipt/') . $membership->receipt);
            $membership->delete();
        }
        //vendor infos 
        $vendor_infos = $vendor->vendor_infos()->get();
        foreach ($vendor_infos as $info) {
            $info->delete();
        }
        //delete vendor cars
        $cars = Car::where('vendor_id', $vendor->id)->get();
        foreach ($cars as $car) {

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
        //delete all vendor's support ticket
        $support_tickets = SupportTicket::where([['user_id', $vendor->id], ['user_type', 'vendor']])->get();
        foreach ($support_tickets as $support_ticket) {
            //delete conversation 
            $messages = $support_ticket->messages()->get();
            foreach ($messages as $message) {
                @unlink(public_path('assets/admin/img/support-ticket/' . $message->file));
                $message->delete();
            }
            @unlink(public_path('assets/admin/img/support-ticket/attachment/') . $support_ticket->attachment);
            $support_ticket->delete();
        }

        //finally delete the vendor
        @unlink(public_path('assets/admin/img/vendor-photo/') . $vendor->photo);
        $vendor->delete();

        return redirect()->back()->with('success', 'Vendor info deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;

        foreach ($ids as $id) {
            $vendor = Vendor::findOrFail($id);
            // vendor memeberships
            $memberships = $vendor->memberships()->get();
            foreach ($memberships as $membership) {
                @unlink(public_path('assets/front/img/membership/receipt/') . $membership->receipt);
                $membership->delete();
            }
            //vendor infos 
            $vendor_infos = $vendor->vendor_infos()->get();
            foreach ($vendor_infos as $info) {
                $info->delete();
            }
            //delete vendor cars
            $cars = Car::where('vendor_id', $vendor->id)->get();
            foreach ($cars as $car) {

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
            //delete all vendor's support ticket
            $support_tickets = SupportTicket::where([['user_id', $vendor->id], ['user_type', 'vendor']])->get();
            foreach ($support_tickets as $support_ticket) {
                //delete conversation 
                $messages = $support_ticket->messages()->get();
                foreach ($messages as $message) {
                    @unlink(public_path('assets/admin/img/support-ticket/' . $message->file));
                    $message->delete();
                }
                @unlink(public_path('assets/admin/img/support-ticket/attachment/') . $support_ticket->attachment);
                $support_ticket->delete();
            }

            //finally delete the vendor
            @unlink(public_path('assets/admin/img/vendor-photo/') . $vendor->photo);
            $vendor->delete();
        }
        Session::flash('success', 'Vendors info deleted successfully!');

        return Response::json(['status' => 'success'], 200);
    }
}
