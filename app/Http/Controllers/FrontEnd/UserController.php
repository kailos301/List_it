<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Http\Helpers\BasicMailer;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Car\Wishlist;
use App\Models\User;
use App\Models\Car\Category;
use App\Models\Language;
use App\Rules\MatchEmailRule;
use App\Rules\MatchOldPasswordRule;
use App\Models\Car\CarImage;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Socialite\Facades\Socialite;

class UserController extends Controller
{
  public function __construct()
  {
    $bs = DB::table('basic_settings')
      ->select('facebook_app_id', 'facebook_app_secret', 'google_client_id', 'google_client_secret')
      ->first();

    Config::set('services.facebook.client_id', $bs->facebook_app_id);
    Config::set('services.facebook.client_secret', $bs->facebook_app_secret);
    Config::set('services.facebook.redirect', url('user/login/facebook/callback'));

    Config::set('services.google.client_id', $bs->google_client_id);
    Config::set('services.google.client_secret', $bs->google_client_secret);
    Config::set('services.google.redirect', url('login/google/callback'));
  }

  public function login(Request $request)
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_login', 'meta_description_login')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    // get the status of digital product (exist or not in the cart)
    if (!empty($request->input('digital_item'))) {
      $queryResult['digitalProductStatus'] = $request->input('digital_item');
    }

    $queryResult['bs'] = Basic::query()->select('google_recaptcha_status', 'facebook_login_status', 'google_login_status')->first();

    return view('frontend.user.login', $queryResult);
  }

  public function user_ads()
    {
      //echo request()->route()->getActionMethod(); exit;
        $information = [];
        $languages = Language::get();
        $information['languages'] = $languages;
        $cat = new Category();
        $cat = $cat::where('parent_id', '=', 0)->get();
        $information['SubCategory'] = $cat;


        return view('frontend.user.ads.create', $information);
    }

    

  public function redirectToFacebook()
  {
    return Socialite::driver('facebook')->redirect();
  }

  public function handleFacebookCallback(Request $request)
  {
    if ($request->has('error_code')) {
      Session::flash('error', $request->error_message);
      return redirect()->route('user.login');
    }
    return $this->authenticationViaProvider('facebook');
  }

  public function redirectToGoogle()
  {
    return Socialite::driver('google')->redirect();
  }

  public function handleGoogleCallback()
  {
    return $this->authenticationViaProvider('google');
  }

  public function authenticationViaProvider($driver)
  {
    // get the url from session which will be redirect after login
    if (Session::has('redirectTo')) {
      $redirectURL = Session::get('redirectTo');
    } else {
      $redirectURL = route('user.dashboard');
    }

    $responseData = Socialite::driver($driver)->user();
    $userInfo = $responseData->user;

    $isUser = User::query()->where('email', '=', $userInfo['email'])->first();

    if (!empty($isUser)) {
      // log in
      if ($isUser->status == 1) {
        Auth::guard('web')->login($isUser);

        return redirect($redirectURL);
      } else {
        Session::flash('error', 'Sorry, your account has been deactivated.');

        return redirect()->route('user.login');
      }
    } else {
      // get user avatar and save it
      $avatar = $responseData->getAvatar();
      $fileContents = file_get_contents($avatar);

      $avatarName = $responseData->getId() . '.jpg';
      $path = public_path('assets/img/users/');

      file_put_contents($path . $avatarName, $fileContents);

      // sign up
      $user = new User();

      if ($driver == 'facebook') {
        $user->name = $userInfo['name'];
      } else {
        $user->name = $userInfo['given_name'];
      }

      $user->image = $avatarName;
      $user->username = $userInfo['id'];
      $user->email = $userInfo['email'];
      $user->email_verified_at = date('Y-m-d H:i:s');
      $user->status = 1;
      $user->provider = ($driver == 'facebook') ? 'facebook' : 'google';
      $user->provider_id = $userInfo['id'];
      $user->save();

      Auth::guard('web')->login($user);

      return redirect($redirectURL);
    }
  }

  public function loginSubmit(Request $request)
  {
    // get the url from session which will be redirect after login
    if ($request->session()->has('redirectTo')) {
      $redirectURL = $request->session()->get('redirectTo');
    } else {
      $redirectURL = null;
    }


    $rules = [
      'username' => 'required',
      'password' => 'required'
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
      return redirect()->route('user.login')->withErrors($validator->errors())->withInput();
    }

    // get the email and password which has provided by the user
    $credentials = $request->only('username', 'password');

    // login attempt
    if (Auth::guard('web')->attempt($credentials)) {
      $authUser = Auth::guard('web')->user();
      // second, check whether the user's account is active or not
      if ($authUser->email_verified_at == null) {
        Session::flash('error', 'Please verify your email address');

        // logout auth user as condition not satisfied
        Auth::guard('web')->logout();

        return redirect()->back();
      }
      if ($authUser->status == 0) {
        Session::flash('error', 'Sorry, your account has been deactivated');

        // logout auth user as condition not satisfied
        Auth::guard('web')->logout();

        return redirect()->back();
      }

      // otherwise, redirect auth user to next url
      if (is_null($redirectURL)) {
        return redirect()->route('user.dashboard');
      } else {
        // before, redirect to next url forget the session value
        $request->session()->forget('redirectTo');

        return redirect($redirectURL);
      }
    } else {
      Session::flash('error', 'Incorrect username or password');

      return redirect()->back();
    }
  }

  public function forgetPassword()
  {

    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_forget_password', 'meta_description_forget_password')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();
    $queryResult['bs'] = Basic::query()->select('google_recaptcha_status', 'facebook_login_status', 'google_login_status')->first();

    return view('frontend.user.forget-password', $queryResult);
  }

  public function forgetPasswordMail(Request $request)
  {
    $rules = [
      'email' => [
        'required',
        'email:rfc,dns',
        new MatchEmailRule('user')
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
      return redirect()->back()->withErrors($validator->errors())->withInput();
    }

    $user = User::query()->where('email', '=', $request->email)->first();

    // store user email in session to use it later
    $request->session()->put('userEmail', $user->email);

    // get the mail template information from db
    $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'reset_password')->first();
    $mailData['subject'] = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    // get the website title info from db
    $info = Basic::select('website_title')->first();

    $name = $user->username;

    $link = '<a href=' . url("user/reset-password") . '>Click Here</a>';

    $mailBody = str_replace('{customer_name}', $name, $mailBody);
    $mailBody = str_replace('{password_reset_link}', $link, $mailBody);
    $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

    $mailData['body'] = $mailBody;

    $mailData['recipient'] = $user->email;

    $mailData['sessionMessage'] = 'A mail has been sent to your email address';

    BasicMailer::sendMail($mailData);

    return redirect()->back();
  }

  public function resetPassword()
  {
    $misc = new MiscellaneousController();

    $bgImg = $misc->getBreadcrumb();

    return view('frontend.user.reset-password', compact('bgImg'));
  }

  public function resetPasswordSubmit(Request $request)
  {
    if ($request->session()->has('userEmail')) {
      // get the user email from session
      $emailAddress = $request->session()->get('userEmail');

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
        return redirect()->back()->withErrors($validator->errors());
      }

      $user = User::query()->where('email', '=', $emailAddress)->first();

      $user->update([
        'password' => Hash::make($request->new_password)
      ]);

      Session::flash('success', 'Password updated successfully.');
    } else {
      Session::flash('error', 'Something went wrong!');
    }

    return redirect()->route('user.login');
  }

  public function signup()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_signup', 'meta_description_signup')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $queryResult['recaptchaInfo'] = Basic::select('google_recaptcha_status')->first();

    return view('frontend.user.signup', $queryResult);
  }

  public function signupSubmit(Request $request)
  {
    print_r($request);
    exit();
    $info = Basic::select('google_recaptcha_status', 'website_title')->first();

    // validation start
    $rules = [
      'username' => 'required|unique:users|max:255',
      'email' => 'required|email:rfc,dns|unique:users|max:255',
      'password' => 'required|confirmed',
      'password_confirmation' => 'required'
    ];

    if ($info->google_recaptcha_status == 1) {
      $rules['g-recaptcha-response'] = 'required|captcha';
    }

    $messages = [
      'password_confirmation.required' => 'The confirm password field is required.'
    ];

    if ($info->google_recaptcha_status == 1) {
      $messages['g-recaptcha-response.required'] = 'Please verify that you are not a robot.';
      $messages['g-recaptcha-response.captcha'] = 'Captcha error! try again later or contact site admin.';
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors())->withInput();
    }
    // validation end

    $user = new User();
    $user->username = $request->username;
    $user->email = $request->email;
    $user->status = 1;
    $user->password = Hash::make($request->password);
    $user->save();

    // get the mail template information from db
    $mailTemplate = MailTemplate::query()->where('mail_type', '=', 'verify_email')->first();
    $mailData['subject'] = $mailTemplate->mail_subject;
    $mailBody = $mailTemplate->mail_body;

    $link = '<a href=' . url("user/signup-verify/" . $user->id) . '>Click Here</a>';

    $mailBody = str_replace('{username}', $user->username, $mailBody);
    $mailBody = str_replace('{verification_link}', $link, $mailBody);
    $mailBody = str_replace('{website_title}', $info->website_title, $mailBody);

    $mailData['body'] = $mailBody;

    $mailData['recipient'] = $user->email;

    $mailData['sessionMessage'] = 'A verification mail has been sent to your email address';

    BasicMailer::sendMail($mailData);

    $queryResult['authUser'] = $user;
    return back();
  }

  public function signupVerify($id)
  {
    $user = User::where('id', $id)->firstOrFail();
    $user->email_verified_at = Carbon::now();
    $user->save();
    Auth::login($user);
    return redirect()->route('user.dashboard');
  }

  public function redirectToDashboard()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['language'] = $language;

    $queryResult['bgImg'] = $misc->getBreadcrumb();
    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $user = Auth::guard('web')->user();

    $queryResult['authUser'] = $user;
    $queryResult['wishlists'] = Wishlist::where('user_id', $user->id)
      ->get();

    return view('frontend.user.dashboard', $queryResult);
  }

  public function editProfile()
  {
    $misc = new MiscellaneousController();

    $queryResult['bgImg'] = $misc->getBreadcrumb();
    $language = $misc->getLanguage();
    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['authUser'] = Auth::guard('web')->user();

    return view('frontend.user.edit-profile', $queryResult);
  }

  public function updateProfile(Request $request)
  {

    $request->validate([
      'name' => 'required',
      'username' => [
        'required',
        'alpha_dash',
        Rule::unique('users', 'username')->ignore(Auth::guard('web')->user()->id),
      ],
      'email' => [
        'required',
        'email',
        Rule::unique('users', 'email')->ignore(Auth::guard('web')->user()->id)
      ],
    ]);

    $authUser = Auth::guard('web')->user();
    $in = $request->all();
    $file = $request->file('image');
    if ($file) {
      $extension = $file->getClientOriginalExtension();
      $directory = public_path('assets/img/users/');
      $fileName = uniqid() . '.' . $extension;
      @mkdir($directory, 0775, true);
      $file->move($directory, $fileName);
      $in['image'] = $fileName;
    }

    $authUser->update($in);

    Session::flash('success', 'Your profile has been updated successfully.');

    return redirect()->back();
  }

  public function changePassword()
  {
    $misc = new MiscellaneousController();

    $bgImg = $misc->getBreadcrumb();
    $language = $misc->getLanguage();
    $pageHeading = $misc->getPageHeading($language);

    return view('frontend.user.change-password', compact('bgImg', 'pageHeading'));
  }

  public function updatePassword(Request $request)
  {
    $rules = [
      'current_password' => [
        'required',
        new MatchOldPasswordRule('user')
      ],
      'new_password' => 'required|confirmed',
      'new_password_confirmation' => 'required'
    ];

    $messages = [
      'new_password.confirmed' => 'Password confirmation failed.',
      'new_password_confirmation.required' => 'The confirm new password field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $user = Auth::guard('web')->user();

    $user->update([
      'password' => Hash::make($request->new_password)
    ]);

    Session::flash('success', 'Password updated successfully.');

    return redirect()->back();
  }

  //wishlist
  public function wishlist()
  {
    $misc = new MiscellaneousController();
    $bgImg = $misc->getBreadcrumb();
    $language = $misc->getLanguage();
    $information['language'] = $language;
    $information['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['language'] = $language;
    $user_id = Auth::guard('web')->user()->id;
    $wishlists = Wishlist::where('user_id', $user_id)
      ->get();
    $information['bgImg'] = $bgImg;
    $information['wishlists'] = $wishlists;
    return view('frontend.user.wishlist', $information);
  }
  //add_to_wishlist
  //add_to_wishlist
  public function add_to_wishlist($id)
  {
    if (Auth::guard('vendor')->check()) {
      $user_id = Auth::guard('vendor')->user()->id;
      $check = Wishlist::where('car_id', $id)->where('user_id', $user_id)->first();

      if (!empty($check)) {
        $notification = array('message' => 'You already added this event into your ads.!', 'alert-type' => 'error');
        return back()->with($notification);
      } else {
        $add = new Wishlist;
        $add->car_id = $id;
        $add->user_id = $user_id;
        $add->save();
        $notification = array('message' => 'Added to your Save ads successfully.', 'alert-type' => 'success');
        return back()->with($notification);
      }
    } else {
      return redirect()->route('vendor.login');
    }
  }
  //remove_wishlist
  public function remove_wishlist($id)
  {
    if (Auth::guard('web')->check()) {
      $remove = Wishlist::where('car_id', $id)->first();
      if ($remove) {
        $remove->delete();
        $notification = array('message' => 'Removed From ads successfully..!', 'alert-type' => 'info');
      } else {
        $notification = array('message' => 'Something went wrong', 'alert-type' => 'danger');
      }
      return back()->with($notification);
    } else {
      return redirect()->route('vendor.login');
    }
  }


  public function logoutSubmit(Request $request)
  {
    Auth::guard('web')->logout();
    Session::forget('secret_login');

    if ($request->session()->has('redirectTo')) {
      $request->session()->forget('redirectTo');
    }

    return redirect()->route('user.login');
  }
   public function get_brand_model(Request $request)
    {
        $data = CarModel::where('brand_id', $request->id)->where('status', 1)->get();
        return $data;
    }
    public function imagesstore(Request $request)
    {
      //echo "hello"; exit;
        $img = $request->file('file');
        $allowedExts = array('jpg', 'png', 'jpeg', 'svg', 'webp');
        $rules = [
            'file' => [
                function ($attribute, $value, $fail) use ($img, $allowedExts) {
                    $ext = $img->getClientOriginalExtension();
                    if (!in_array($ext, $allowedExts)) {
                        return $fail("Only png, jpg, jpeg images are allowed");
                    }
                },
            ]
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $validator->getMessageBag()->add('error', 'true');
            return response()->json($validator->errors());
        }
        $filename = uniqid() . '.jpg';
        $img->move(public_path('assets/admin/img/car-gallery/'), $filename);
        $pi = new CarImage();
        if (!empty($request->car_id)) {
            $pi->car_id = $request->car_id;
        }
        $pi->image = $filename;
        $pi->save();
        return response()->json(['status' => 'success', 'file_id' => $pi->id]);
    }
    public function imagermv(Request $request)
    {
        $pi = CarImage::findOrFail($request->fileid);
        $imageCount = CarImage::where('car_id', $pi->car_id)->get()->count();
        if ($imageCount > 1) {
            @unlink(public_path('assets/admin/img/car-gallery/') . $pi->image);
            $pi->delete();
            return $pi->id;
        } else {
            return 'false';
        }
    }

    //imagedbrmv
    public function imagedbrmv(Request $request)
    {
        $pi = CarImage::findOrFail($request->fileid);
        $imageCount = CarImage::where('car_id', $pi->car_id)->get()->count();
        if ($imageCount > 1) {
            @unlink(public_path('assets/admin/img/car-gallery/') . $pi->image);
            $pi->delete();
            return $pi->id;
        } else {
            return 'false';
        }
    }
    //store
   

}
