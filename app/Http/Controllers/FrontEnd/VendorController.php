<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\BasicSettings\Basic;
use App\Models\Car;
use App\Models\Car\CarColor;
use App\Models\Car\Category;
use App\Models\HomePage\Section;
use App\Models\Vendor;
use App\Models\VendorInfo;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Validator;

class VendorController extends Controller
{
    //index
    public function index(Request $request)
    {
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();
        $queryResult['language'] = $language;

        $queryResult['pageHeading'] = $misc->getPageHeading($language);

        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keywords_vendor_page', 'meta_description_vendor_page')->first();
        $name = $location = null;
        $vendorIds = [];
        if ($request->filled('name')) {
            $name = $request->name;
            $u_infos = Vendor::where('vendors.username', 'like', '%' . $name . '%')->get();
            $v_infos = VendorInfo::where([['vendor_infos.name', 'like', '%' . $name . '%'], ['language_id', $language->id]])->get();

            foreach ($u_infos as $info) {
                if (!in_array($info->id, $vendorIds)) {
                    array_push($vendorIds, $info->id);
                }
            }
            foreach ($v_infos as $v_info) {
                if (!in_array($v_info->vendor_id, $vendorIds)) {
                    array_push($vendorIds, $v_info->vendor_id);
                }
            }
        }

        if ($request->filled('location')) {
            $location = $request->location;
        }

        $secInfo = Section::query()->select('subscribe_section_status')->first();
        $queryResult['secInfo'] = $secInfo;

        if ($request->filled('location')) {
            $vendor_contents = VendorInfo::where('country', 'like', '%' . $location . '%')
                ->orWhere('city', 'like', '%' . $location . '%')
                ->orWhere('state', 'like', '%' . $location . '%')
                ->orWhere('zip_code', 'like', '%' . $location . '%')
                ->orWhere('address', 'like', '%' . $location . '%')
                ->get();
            foreach ($vendor_contents as $vendor_content) {
                if (!in_array($vendor_content->vendor_id, $vendorIds)) {
                    array_push($vendorIds, $vendor_content->vendor_id);
                }
            }
        }

        $queryResult['bgImg'] = $misc->getBreadcrumb();
        $queryResult['vendors'] = Vendor::join('memberships', 'memberships.vendor_id', 'vendors.id')
            ->where([
                ['memberships.status', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')],
            ])
            ->where('vendors.status', 1)
            ->when($name, function ($query) use ($vendorIds) {
                return $query->whereIn('vendors.id', $vendorIds);
            })
            ->when($location, function ($query) use ($vendorIds) {
                return $query->whereIn('vendors.id', $vendorIds);
            })
            ->where('vendors.id', '!=', 0)
            ->select('vendors.*', 'vendors.id as vendorId', 'memberships.*')
            ->paginate(10);
        return view('frontend.vendor.index', $queryResult);
    }
    //details 
    public function details(Request $request)
    {

        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();
        $queryResult['language'] = $language;

        $queryResult['bgImg'] = $misc->getBreadcrumb();
        $queryResult['pageHeading'] = $misc->getPageHeading($language);

        if ($request->admin == true) {
            $vendor = Admin::first();
            $vendor_id = 0;
            $queryResult['total_cars'] = Car::where('vendor_id', 0)->get()->count();
        } else {
            $vendor = Vendor::join('memberships', 'memberships.vendor_id', 'vendors.id')
                ->where([
                    ['memberships.status', 1],
                    ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                    ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')],
                ])
                ->where('vendors.username', $request->username)
                ->select('vendors.*')
                ->firstOrFail();
            $vendorInfo = VendorInfo::where([['vendor_id', $vendor->id], ['language_id', $language->id]])->first();
            $queryResult['vendorInfo'] = $vendorInfo;
            $vendor_id = $vendor->id;
        }
        $queryResult['vendor'] = $vendor;

        $queryResult['categories'] = Category::where([['language_id', $language->id], ['status', 1]])->get();

        $queryResult['all_cars'] = Car::where([['cars.vendor_id', $vendor_id], ['cars.status', 1]])
            ->orderBy('id', 'desc')
            ->get();

        $secInfo = Section::query()->select('subscribe_section_status')->first();
        $queryResult['secInfo'] = $secInfo;
        $queryResult['currencyInfo'] = $this->getCurrencyInfo();
        $queryResult['info'] = Basic::select('google_recaptcha_status')->first();
        return view('frontend.vendor.details', $queryResult);
    }

    //contact
    public function contact(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email:rfc,dns',
            'subject' => 'required',
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

        $validator = Validator::make($request->all(), $rules, $messageArray);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()], 400);
        }


        $be = Basic::select('smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')->firstOrFail();

        $c_message = nl2br($request->message);
        $msg = "<h4>Name : $request->name</h4>
            <h4>Email : $request->email</h4>
            <p>Message : </p> 
            <p>$c_message</p>
            ";

        $data = [
            'to' => $request->vendor_email,
            'subject' => $request->subject,
            'message' => $msg,
        ];

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
            Mail::send([], [], function (Message $message) use ($data, $be) {
                $fromMail = $be->from_mail;
                $fromName = $be->from_name;
                $message->to($data['to'])
                    ->subject($data['subject'])
                    ->from($fromMail, $fromName)
                    ->html($data['message'], 'text/html');
            });
            Session::flash('message', 'Message sent successfully');
            Session::flash('alert-type', 'success');
            return 'success';
        } catch (\Exception $e) {
            Session::flash('message', 'Something went wrong.');
            Session::flash('alert-type', 'error');
            return 'success';
        }
    }
}
