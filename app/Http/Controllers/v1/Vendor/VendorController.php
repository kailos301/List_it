<?php

namespace App\Http\Controllers\v1\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Car;
use App\Models\Language;
use App\Models\Membership;
use App\Models\Package;
use App\Models\SupportTicket;
use App\Models\Vendor;
use App\Models\VendorInfo;
use App\Rules\MatchEmailRule;
use App\Rules\MatchOldPasswordRule;
use Carbon\Carbon;
use Config;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use PHPMailer\PHPMailer\PHPMailer;

class VendorController extends Controller
{
    //signup
    public function signup()
    {
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keywords_vendor_signup', 'meta_description_vendor_signup')->first();

        $queryResult['pageHeading'] = $misc->getPageHeading($language);

        $queryResult['recaptchaInfo'] = Basic::select('google_recaptcha_status')->first();

        $queryResult['bgImg'] = $misc->getBreadcrumb();

        return view('vendors.auth.register', $queryResult);
    }
    //create
    public function create(Request $request)
    {
        //print_r($request->post()); exit;
        //echo "hello"; exit;
        $rules = [
            'username' => 'required|unique:vendors',
            'email' => 'required|email|unique:vendors',
            'password' => 'required|confirmed|min:6',
        ];

        $info = Basic::select('google_recaptcha_status')->first();
        $messages = [];
        /*if ($info->google_recaptcha_status == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        

        if ($info->google_recaptcha_status == 1) {
            $messages['g-recaptcha-response.required'] = 'Please verify that you are not a robot.';
            $messages['g-recaptcha-response.captcha'] = 'Captcha error! try again later or contact site admin.';
        }*/

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {

            //return redirect()->back()->withErrors($validator->errors());
             return response()->json(['code' => 400, 'message' => $validator->errors(),'data' =>null]); exit;
        }

        if ($request->username == 'admin') {
           // Session::flash('username_error', 'You can not use admin as a username!');
            //return redirect()->back();
            return response()->json(['code' => 400, 'message' => 'You can not use admin as a username!','data' =>null]); exit;
        }

        $in = $request->all();
        $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_email_verification', 'vendor_admin_approval')->first();

        if ($setting->vendor_email_verification == 11) {
            // first, get the mail template information from db
            $mailTemplate = MailTemplate::where('mail_type', 'verify_email')->first();

            $mailSubject = $mailTemplate->mail_subject;
            $mailBody = $mailTemplate->mail_body;

            // second, send a password reset link to user via email
            $info = DB::table('basic_settings')
                ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
                ->first();

            $token =  $request->email;

            $link = '<a href=' . url("vendor/email/verify?token=" . $token) . '>Click Here</a>';

            $mailBody = str_replace('{username}', $request->username, $mailBody);
            $mailBody = str_replace('{verification_link}', $link, $mailBody);
            $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

            $data = [
                'subject' => $mailSubject,
                'to' => $request->email,
                'body' => $mailBody,
            ];

            // if smtp status == 1, then set some value for PHPMailer
            if ($info->smtp_status == 1) {
                try {
                    $smtp = [
                        'transport' => 'smtp',
                        'host' => $info->smtp_host,
                        'port' => $info->smtp_port,
                        'encryption' => $info->encryption,
                        'username' => $info->smtp_username,
                        'password' => $info->smtp_password,
                        'timeout' => null,
                        'auth_mode' => null,
                    ];
                    Config::set('mail.mailers.smtp', $smtp);
                } catch (\Exception $e) {
                    Session::flash('error', $e->getMessage());
                    return back();
                }
            }

            // finally add other informations and send the mail
            try {
                Mail::send([], [], function (Message $message) use ($data, $info) {
                    $fromMail = $info->from_mail;
                    $fromName = $info->from_name;
                    $message->to($data['to'])
                        ->subject($data['subject'])
                        ->from($fromMail, $fromName)
                        ->html($data['body'], 'text/html');
                });

                Session::flash('success', 'A verification mail has been sent to your email address');
            } catch (\Exception $e) {
                Session::flash('message', 'Mail could not be sent!');
                Session::flash('alert-type', 'error');
                return redirect()->back();
            }

            $in['status'] = 0;
        } else {
            //Session::flash('success', 'Sign up successfully completed.Please Login Now');
            return response()->json(['code' => 200, 'message' => 'Sign up successfully completed.Please Login Now','data' =>null]); exit;
        }
        if ($setting->vendor_admin_approval == 1) {
            $in['status'] = 0;
        }

        if ($setting->vendor_admin_approval == 0 && $setting->vendor_email_verification == 0) {
            $in['status'] = 1;
        }

        $in['password'] = Hash::make($request->password);
        $in['trader'] = $request->traderstatus;
        //print_r($in); exit;
        $vendor = Vendor::create($in);


        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();

        $in['language_id'] = $language->id;
        $in['vendor_id'] = $vendor->id;
        
        VendorInfo::create($in);

        //return redirect()->route('vendor.login');
    }

    //login
    public function login()
    {
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keywords_vendor_login', 'meta_description_vendor_login')->first();

        $queryResult['pageHeading'] = $misc->getPageHeading($language);

        $queryResult['bgImg'] = $misc->getBreadcrumb();

        $queryResult['bs'] = Basic::query()->select('google_recaptcha_status', 'facebook_login_status', 'google_login_status')->first();
        return view('vendors.auth.login', $queryResult);
    }

    //authenticate
    public function authentication(Request $request)
    {
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];
        

        $messages = [];
        $payload = [];

        

        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()) {
           // return redirect()->back()->withErrors($validator->errors());
             return response()->json(['code' => 400, 'message' => 'username or password is required','data' =>null]); exit;
        }

        if (Auth::guard('vendor')->attempt([
            'username' => $request->username,
            'password' => $request->password
        ])) {
            $authAdmin = Auth::guard('vendor')->user();
            echo $authAdmin->status;

            $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_email_verification', 'vendor_admin_approval')->first();

            // check whether the admin's account is active or not
            if ($setting->vendor_email_verification == 1 && $authAdmin->email_verified_at == NULL && $authAdmin->status == 0) {
               // Session::flash('error', 'Please verify your email address');

                // logout auth admin as condition not satisfied
               // Auth::guard('vendor')->logout();
                 return response()->json(['code' => 400, 'message' => 'Please verify your email address','data' =>null]); exit;
               // return redirect()->back();
            } else{
                 $payload =array(
                    "id"=>$authAdmin->id,
                    "name"=>"",
                    "username"=>$authAdmin->username, 
                    "email"=>$authAdmin->email, 
                    "status"=>$authAdmin->status);

                return response()->json(['code' => 200, 'message' => 'Authentication successful.','data' =>$payload]); exit;

            }

            /*elseif ($setting->vendor_email_verification == 0 && $setting->vendor_admin_approval == 1) {
                Session::put('secret_login', 0);
                return redirect()->route('vendor.dashboard');
            } else {
                Session::put('secret_login', 0);
                return redirect()->route('vendor.dashboard');
            }*/
        } else {
            //return redirect()->back()->with('error', 'Incorrect username or password');
            return response()->json(['code' => 400, 'message' => 'Incorrect username or password','data' =>null]); exit;
        }
    }
    //confirm_email'
    public function confirm_email()
    {
        $email = request()->input('token');
        $user = Vendor::where('email', $email)->first();
        $user->email_verified_at = now();
        $setting = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_admin_approval')->first();
        if ($setting->vendor_admin_approval != 1) {
            $user->status = 1;
        }
        $user->save();
        Auth::guard('vendor')->login($user);
        return redirect()->route('vendor.dashboard');
    }
    public function logout(Request $request)
    {
        Auth::guard('vendor')->logout();
        Session::forget('secret_login');
        return redirect()->route('vendor.login');
    }

    public function dashboard()
    {
        $vendor_id = Auth::guard('vendor')->user()->id;
        $information['totalCars'] = Car::query()->where('vendor_id', $vendor_id)->count();

        $information['admin_setting'] = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_admin_approval', 'admin_approval_notice')->first();

        $support_status = DB::table('support_ticket_statuses')->first();
        if ($support_status->support_ticket_status == 'active') {
            $total_support_tickets = SupportTicket::where([['user_id', Auth::guard('vendor')->user()->id], ['user_type', 'vendor']])->get()->count();
            $information['total_support_tickets'] = $total_support_tickets;
        }
        $information['support_status'] = $support_status;
        $information['admin_setting'] = DB::table('basic_settings')->where('uniqid', 12345)->select('vendor_admin_approval', 'admin_approval_notice')->first();

        //total car posts
        $totalCars = DB::table('cars')
            ->select(DB::raw('month(created_at) as month'), DB::raw('count(id) as total'))
            ->groupBy('month')
            ->where('vendor_id', $vendor_id)
            ->whereYear('created_at', '=', date('Y'))
            ->get();
        //total car visitors
        $totalVisitors = DB::table('visitors')
            ->select(DB::raw('month(date) as month'), DB::raw('count(id) as total'))
            ->groupBy('month')
            ->where('vendor_id', $vendor_id)
            ->whereYear('date', '=', date('Y'))
            ->get();


        $months = [];
        $totalCarArr = [];
        $totalVisitorArr = [];


        //event icome calculation
        for ($i = 1; $i <= 12; $i++) {
            // get all 12 months name
            $monthNum = $i;
            $dateObj = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('M');
            array_push($months, $monthName);

            // get all 12 months's car posts
            $carFound = false;
            foreach ($totalCars as $totalCar) {
                if ($totalCar->month == $i) {
                    $carFound = true;
                    array_push($totalCarArr, $totalCar->total);
                    break;
                }
            }
            if ($carFound == false) {
                array_push($totalCarArr, 0);
            }

            // get all 12 months's visitors
            $visitorFound = false;
            foreach ($totalVisitors as $totalVisitor) {
                if ($totalVisitor->month == $i) {
                    $visitorFound = true;
                    array_push($totalVisitorArr, $totalVisitor->total);
                    break;
                }
            }
            if ($visitorFound == false) {
                array_push($totalVisitorArr, 0);
            }
        }

        $payment_logs = Membership::where('vendor_id', $vendor_id)->get()->count();

        //package start
        $nextPackageCount = Membership::query()->where([
            ['vendor_id', Auth::guard('vendor')->user()->id],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->count();
        //current package
        $information['current_membership'] = Membership::query()->where([
            ['vendor_id', Auth::guard('vendor')->user()->id],
            ['start_date', '<=', Carbon::now()->toDateString()],
            ['expire_date', '>=', Carbon::now()->toDateString()]
        ])->where('status', 1)->whereYear('start_date', '<>', '9999')->first();
        if ($information['current_membership'] != null) {
            $countCurrMem = Membership::query()->where([
                ['vendor_id', Auth::guard('vendor')->user()->id],
                ['start_date', '<=', Carbon::now()->toDateString()],
                ['expire_date', '>=', Carbon::now()->toDateString()]
            ])->where('status', 1)->whereYear('start_date', '<>', '9999')->count();
            if ($countCurrMem > 1) {
                $information['next_membership'] = Membership::query()->where([
                    ['vendor_id', Auth::guard('vendor')->user()->id],
                    ['start_date', '<=', Carbon::now()->toDateString()],
                    ['expire_date', '>=', Carbon::now()->toDateString()]
                ])->where('status', '<>', 2)->whereYear('start_date', '<>', '9999')->orderBy('id', 'DESC')->first();
            } else {
                $information['next_membership'] = Membership::query()->where([
                    ['vendor_id', Auth::guard('vendor')->user()->id],
                    ['start_date', '>', $information['current_membership']->expire_date]
                ])->whereYear('start_date', '<>', '9999')->where('status', '<>', 2)->first();
            }
            $information['next_package'] = $information['next_membership'] ? Package::query()->where('id', $information['next_membership']->package_id)->first() : null;
        } else {
            $information['next_package'] = null;
        }
        $information['current_package'] = $information['current_membership'] ? Package::query()->where('id', $information['current_membership']->package_id)->first() : null;
        $information['package_count'] = $nextPackageCount;
        //package start end

        $information['monthArr'] = $months;
        $information['totalCarsArr'] = $totalCarArr;
        $information['visitorArr'] = $totalVisitorArr;
        $information['payment_logs'] = $payment_logs;

        return view('vendors.index', $information);
    }

    //change_password
    public function change_password()
    {
        return view('vendors.auth.change-password');
    }

    //update_password
    public function updated_password(Request $request)
    {
        $rules = [
            'current_password' => [
                'required',
                new MatchOldPasswordRule('vendor')

            ],
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

        $vendor = Auth::guard('vendor')->user();

        $vendor->update([
            'password' => Hash::make($request->new_password)
        ]);

        Session::flash('success', 'Password updated successfully!');

        return response()->json(['status' => 'success'], 200);
    }

    //edit_profile
    public function edit_profile()
    {
        $information = [];
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();

        $information['language'] = $language;
        $information['languages'] = Language::get();

        $vendor_id = Auth::guard('vendor')->user()->id;
        $information['vendor'] = Vendor::with('vendor_info')->where('id', $vendor_id)->first();
        return view('vendors.auth.edit-profile', $information);
    }
    //update_profile
    public function update_profile(Request $request, Vendor $vendor)
    {
        $id = Auth::guard('vendor')->user()->id;
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
            $messages[$language->code . '_name.required'] = 'The Name field is required.';
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

    public function changeTheme(Request $request)
    {
        Session::put('vendor_theme_version', $request->vendor_theme_version);
        return redirect()->back();
    }
    //forget_passord
    public function forget_passord()
    {
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keywords_vendor_forget_password', 'meta_descriptions_vendor_forget_password')->first();

        $queryResult['pageHeading'] = $misc->getPageHeading($language);

        $queryResult['bgImg'] = $misc->getBreadcrumb();
        $queryResult['bs'] = Basic::query()->select('google_recaptcha_status', 'facebook_login_status', 'google_login_status')->first();
        return view('vendors.auth.forget-password', $queryResult);
    }
    //forget_mail
    public function forget_mail(Request $request)
    {
        $rules = [
            'email' => [
                'required',
                'email:rfc,dns',
                new MatchEmailRule('vendor')
            ]
        ];

        $info = Basic::select('google_recaptcha_status')->first();
        if ($info->google_recaptcha_status == 1) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $messages = [];

        if ($info->google_recaptcha_status == 1) {
            $messages['g-recaptcha-response.required'] = 'Please verify that you are not a robot.';
            $messages['g-recaptcha-response.captcha'] = 'Captcha error! try again later or contact site admin.';
        }

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = Vendor::where('email', $request->email)->first();

        // first, get the mail template information from db
        $mailTemplate = MailTemplate::where('mail_type', 'reset_password')->first();
        $mailSubject = $mailTemplate->mail_subject;
        $mailBody = $mailTemplate->mail_body;

        // second, send a password reset link to user via email
        $info = DB::table('basic_settings')
            ->select('website_title', 'smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name')
            ->first();

        $name = $user->username;
        $token =  Str::random(32);
        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $token,
        ]);

        $link = '<a href=' . url("vendor/reset-password?token=" . $token) . '>Click Here</a>';

        $mailBody = str_replace('{customer_name}', $name, $mailBody);
        $mailBody = str_replace('{password_reset_link}', $link, $mailBody);
        $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

        $data = [
            'to' => $request->email,
            'subject' => $mailSubject,
            'body' => $mailBody,
        ];

        // if smtp status == 1, then set some value for PHPMailer
        if ($info->smtp_status == 1) {
            try {
                $smtp = [
                    'transport' => 'smtp',
                    'host' => $info->smtp_host,
                    'port' => $info->smtp_port,
                    'encryption' => $info->encryption,
                    'username' => $info->smtp_username,
                    'password' => $info->smtp_password,
                    'timeout' => null,
                    'auth_mode' => null,
                ];
                Config::set('mail.mailers.smtp', $smtp);
            } catch (\Exception $e) {
                Session::flash('error', $e->getMessage());
                return back();
            }
        }

        // finally add other informations and send the mail
        try {
            Mail::send([], [], function (Message $message) use ($data, $info) {
                $fromMail = $info->from_mail;
                $fromName = $info->from_name;
                $message->to($data['to'])
                    ->subject($data['subject'])
                    ->from($fromMail, $fromName)
                    ->html($data['body'], 'text/html');
            });

            Session::flash('success', 'A mail has been sent to your email address');
        } catch (\Exception $e) {
            Session::flash('error', 'Mail could not be sent!');
        }

        // store user email in session to use it later
        $request->session()->put('userEmail', $user->email);
        return redirect()->back();
    }
    //reset_password
    public function reset_password()
    {
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();

        $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keywords_vendor_forget_password', 'meta_descriptions_vendor_forget_password')->first();

        $queryResult['bgImg'] = $misc->getBreadcrumb();
        return view('vendors.auth.reset-password', $queryResult);
    }
    //update_password
    public function update_password(Request $request)
    {
        $rules = [
            'new_password' => 'required|confirmed',
            'new_password_confirmation' => 'required'
        ];

        $messages = [
            'new_password.confirmed' => 'Password confirmation failed.',
            'new_password_confirmation.required' => 'The confirm new password field is required.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $reset = DB::table('password_resets')->where('token', $request->token)->first();
        $email = $reset->email;

        $vendor = Vendor::where('email',  $email)->first();

        $vendor->update([
            'password' => Hash::make($request->new_password)
        ]);
        DB::table('password_resets')->where('token', $request->token)->delete();
        Session::flash('success', 'Reset Your Password Successfully Completed.Please Login Now');

        return redirect()->route('vendor.login');
    }
    public function payment_log(Request $request)
    {
        $search = $request->search;
        $data['memberships'] = Membership::query()->when($search, function ($query, $search) {
            return $query->where('transaction_id', 'like', '%' . $search . '%');
        })
            ->where('vendor_id', Auth::guard('vendor')->user()->id)
            ->orderBy('id', 'DESC')
            ->paginate(10);
        return view('vendors.payment_log', $data);
    }
}
