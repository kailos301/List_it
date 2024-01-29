<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\Basic;
use App\Models\BasicSettings\MailTemplate;
use App\Models\Car;
use App\Models\Car\Brand;
use App\Models\Car\CarColor;
use App\Models\Car\CarContent;
use App\Models\Car\CarModel;
use App\Models\Car\CarSpecification;
use App\Models\Car\Category;
use App\Models\Car\FuelType;
use App\Models\Car\TransmissionType;
use App\Models\Vendor;
use App\Models\Visitor;
use Carbon\Carbon;
use Config;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Session;

class CarController extends Controller
{

    public function index(Request $request)
    {
        //print_r(request()->all()); exit;

        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();
        $information['seoInfo'] = $language->seoInfo()->select('meta_keyword_cars', 'meta_description_cars')->first();
        
        if ($request->filled('type')) {
            Session::put('car_view_type', $request->type);
        }
        $view_type = Session::get('car_view_type');
        //echo $view_type; exit;
        $category = $title = $location = $brands = $models = $fuel_type = $transmission = $condition = $min = $max =  null;

        $carIds = [];
        if ($request->filled('title')) {
            $title = $request->title;
            $car_contents = CarContent::where('language_id', $language->id)
                ->where('title', 'like', '%' . $title . '%')
                ->get()
                ->pluck('car_id');
            foreach ($car_contents as $car_content) {
                if (!in_array($car_content, $carIds)) {
                    array_push($carIds, $car_content);
                }
            }
        }
        $category_carIds = [];
        if ($request->filled('category')) {
            $category = $request->category;
            $category_content = Category::where([['language_id', $language->id], ['slug', $category]])->first();
            if (!empty($category_content)) {
                $category_id = $category_content->id;
                $contents = CarContent::where('language_id', $language->id)
                    ->where('category_id', $category_id)
                    ->get()
                    ->pluck('car_id');
                foreach ($contents as $content) {
                    if (!in_array($content, $category_carIds)) {
                        array_push($category_carIds, $content);
                    }
                }
            }
        }

        $locationIds = [];
        if ($request->filled('location')) {
            $location = $request->location;
            $contents = CarContent::where('language_id', $language->id)
                ->where('address', 'like', '%' . $location . '%')
                ->get()
                ->pluck('car_id');
            foreach ($contents as $content) {
                if (!in_array($content, $locationIds)) {
                    array_push($locationIds, $content);
                }
            }
        }

        $brandIds = [];
        if ($request->filled('brands')) {
            $brands = $request->brands;
            $b_ids = [];

            if (is_array($brands)) {
                foreach ($brands as $brand) {
                    if (!is_null($brand)) {
                        $brand_car_contents = Brand::where([['language_id', $language->id], ['slug', $brand]])->first();
                        if (!empty($brand_car_contents)) {
                            if (!in_array($brand_car_contents->id, $b_ids)) {
                                array_push($b_ids, $brand_car_contents->id);
                            }
                        }
                    }
                }
            } else {
                $brand_car_contents = Brand::where([['language_id', $language->id], ['slug', $brands]])->first();
                if (!in_array($brand_car_contents->id, $b_ids)) {
                    array_push($b_ids, $brand_car_contents->id);
                }
            }

            $contents = CarContent::where('language_id', $language->id)
                ->whereIn('brand_id', $b_ids)
                ->get()
                ->pluck('car_id');
            foreach ($contents as $content) {
                if (!in_array($content, $brandIds)) {
                    array_push($brandIds, $content);
                }
            }
        }

        $modelIds = [];
        if ($request->filled('models')) {
            $models = $request->models;
            $m_ids = [];
            if (is_array($models)) {
                foreach ($models as $model) {
                    $model_car_contents = CarModel::where([['language_id', $language->id], ['slug', $model]])->where('status', 1)->first();
                    if (!in_array($model_car_contents->id, $m_ids)) {
                        array_push($m_ids, $model_car_contents->id);
                    }
                }
            } else {
                $model_car_contents = CarModel::where([['language_id', $language->id], ['slug', $models]])->where('status', 1)->first();
                if (!in_array($model_car_contents->id, $m_ids)) {
                    array_push($m_ids, $model_car_contents->id);
                }
            }

            $contents = CarContent::where('language_id', $language->id)
                ->whereIn('car_model_id', $m_ids)
                ->get();
            foreach ($contents as $content) {
                if (!in_array($content->car_id, $modelIds)) {
                    array_push($modelIds, $content->car_id);
                }
            }
        }

        $fuel_type_id = [];
        if ($request->filled('fuel_type')) {
            $fuel_type = $request->fuel_type;
            $fuel_type_content = FuelType::where([['language_id', $language->id], ['slug', $fuel_type]])->first();
            if (!empty($fuel_type_content)) {
                $f_id = $fuel_type_content->id;
                $contents = CarContent::where('language_id', $language->id)
                    ->where('fuel_type_id', $f_id)
                    ->get()
                    ->pluck('car_id');
                foreach ($contents as $content) {
                    if (!in_array($content, $fuel_type_id)) {
                        array_push($fuel_type_id, $content);
                    }
                }
            }
        }

        $transmission_type_id = [];
        if ($request->filled('transmission')) {
            $transmission = $request->transmission;
            $transmission_content = TransmissionType::where([['language_id', $language->id], ['slug', $transmission]])->first();
            if (!empty($transmission_content)) {
                $t_id = $transmission_content->id;
                $contents = CarContent::where('language_id', $language->id)
                    ->where('transmission_type_id', $t_id)
                    ->get()
                    ->pluck('car_id');
                foreach ($contents as $content) {
                    if (!in_array($content, $transmission_type_id)) {
                        array_push($transmission_type_id, $content);
                    }
                }
            }
        }
        $condition_id = [];
        if ($request->filled('transmission')) {
            $condition = $request->condition;
            $condition_content = CarColor::where([['language_id', $language->id], ['slug', $condition]])->first();
            if (!empty($condition_content)) {
                $c_id = $condition_content->id;
                $contents = CarContent::where('language_id', $language->id)
                    ->where('car_condition_id', $c_id)
                    ->get()
                    ->pluck('car_id');
                foreach ($contents as $content) {
                    if (!in_array($content, $condition_id)) {
                        array_push($condition_id, $content);
                    }
                }
            }
        }

        $priceIds = [];
        if ($request->filled('min') && $request->filled('max')) {
            $min = intval($request->min);
            $max = intval(($request->max));
            $price_cars = DB::table('cars')
                ->select('*')
                ->where('price', '>=', $min)
                ->where('price', '<=', DB::raw($max))
                ->get();
            foreach ($price_cars as $car) {
                if (!in_array($car->id, $priceIds)) {
                    array_push($priceIds, $car->id);
                }
            }
        }

        if ($request->filled('sort')) {
            if ($request['sort'] == 'new') {
                $order_by_column = 'cars.id';
                $order = 'desc';
            } elseif ($request['sort'] == 'old') {
                $order_by_column = 'cars.id';
                $order = 'asc';
            } elseif ($request['sort'] == 'high-to-low') {
                $order_by_column = 'cars.price';
                $order = 'desc';
            } elseif ($request['sort'] == 'low-to-high') {
                $order_by_column = 'cars.price';
                $order = 'asc';
            } else {
                $order_by_column = 'cars.id';
                $order = 'desc';
            }
        } else {
            $order_by_column = 'cars.id';
            $order = 'desc';
        }


        $car_contents = Car::join('car_contents', 'cars.id', 'car_contents.car_id')

            ->join('memberships', 'cars.vendor_id', '=', 'memberships.vendor_id')
            ->join('vendors', 'cars.vendor_id', '=', 'vendors.id')
            ->where([
                ['memberships.status', '=', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
            ])
            ->where([['vendors.status', 1], ['cars.status', 1]])
            ->when($title, function ($query) use ($carIds) {
                return $query->whereIn('cars.id', $carIds);
            })
            ->when($category, function ($query) use ($category_carIds) {
                return $query->whereIn('car_id', $category_carIds);
            })
            ->when($location, function ($query) use ($locationIds) {
                return $query->whereIn('car_id', $locationIds);
            })
            ->when($brands, function ($query) use ($brandIds) {
                return $query->whereIn('cars.id', $brandIds);
            })
            ->when($models, function ($query) use ($modelIds) {
                return $query->whereIn('cars.id', $modelIds);
            })
            ->when($fuel_type, function ($query) use ($fuel_type_id) {
                return $query->whereIn('cars.id', $fuel_type_id);
            })
            ->when($transmission, function ($query) use ($transmission_type_id) {
                return $query->whereIn('cars.id', $transmission_type_id);
            })
            ->when($condition, function ($query) use ($condition_id) {
                return $query->whereIn('cars.id', $condition_id);
            })
            ->when($min && $max, function ($query) use ($priceIds) {
                return $query->whereIn('cars.id', $priceIds);
            })
            ->where('car_contents.language_id', $language->id)
            ->select('cars.*', 'car_contents.title','car_contents.brand_id', 'car_contents.car_model_id', 'car_contents.slug', 'car_contents.category_id', 'car_contents.description')
            ->orderBy($order_by_column, $order)
            ->paginate(15);

        $total_cars = Car::join('car_contents', 'cars.id', 'car_contents.car_id')
            ->join('memberships', 'cars.vendor_id', '=', 'memberships.vendor_id')
            ->join('vendors', 'cars.vendor_id', '=', 'vendors.id')
            ->where([
                ['memberships.status', '=', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
            ])
            ->where([['vendors.status', 1], ['cars.status', 1]])
            ->when($title, function ($query) use ($carIds) {
                return $query->whereIn('cars.id', $carIds);
            })
            ->when($category, function ($query) use ($category_carIds) {
                return $query->whereIn('car_id', $category_carIds);
            })
            ->when($location, function ($query) use ($locationIds) {
                return $query->whereIn('car_id', $locationIds);
            })
            ->when($brands, function ($query) use ($brandIds) {
                return $query->whereIn('cars.id', $brandIds);
            })
            ->when($models, function ($query) use ($modelIds) {
                return $query->whereIn('cars.id', $modelIds);
            })
            ->when($fuel_type, function ($query) use ($fuel_type_id) {
                return $query->whereIn('cars.id', $fuel_type_id);
            })
            ->when($transmission, function ($query) use ($transmission_type_id) {
                return $query->whereIn('cars.id', $transmission_type_id);
            })
            ->when($condition, function ($query) use ($condition_id) {
                return $query->whereIn('cars.id', $condition_id);
            })
            ->when($min && $max, function ($query) use ($priceIds) {
                return $query->whereIn('cars.id', $priceIds);
            })
            ->where('car_contents.language_id', $language->id)
            ->select('cars.*', 'car_contents.title',  'car_contents.slug', 'car_contents.category_id', 'car_contents.description')
            ->orderBy($order_by_column, $order)
            ->get()->count();

        $information['car_contents'] = $car_contents;
        //echo "<pre>";
        //print_r($car_contents); exit;
        $min = Car::min('price');
        $max = Car::max('price');

        $information['min'] = intval($min);
        $information['max'] = intval($max);

        $information['total_cars'] = $total_cars;

        $information['categories'] = Category::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();

        $information['brands'] = Brand::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();

        $information['car_conditions'] = CarColor::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();

        $information['fuel_types'] = FuelType::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();

        $information['transmission_types'] = TransmissionType::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();

        $information['bgImg'] = $misc->getBreadcrumb();
        $information['pageHeading'] = $misc->getPageHeading($language);
       // print_r($request);
       // echo $view_type; exit;
        if ($view_type == 'list') {
            return view('frontend.car.cars_list', $information);
        } else {
            return view('frontend.car.cars_grid', $information);
        }
    }

    public function details($slug, $id)
    {
        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();
        $information['car'] = Car::with(['car_content' => function ($query) use ($language) {
            return $query->where('language_id', $language->id);
        }, 'galleries'])
            ->join('memberships', 'cars.vendor_id', '=', 'memberships.vendor_id')
            ->where([
                ['memberships.status', '=', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
            ])
            ->select('cars.*')
            ->where('cars.id', $id)->firstOrFail();
        $information['bgImg'] = $misc->getBreadcrumb();

        $car_content = CarContent::where('language_id', $language->id)->where('car_id', $id)->first();
        if (is_null($car_content)) {
            Session::flash('message', 'No car information found for ' . $language->name . ' language');
            Session::flash('alert-type', 'warning');
            return redirect()->route('index');
        }
        $category_id = $car_content->category_id;
        $information['language'] = $language;

        $information['specifications'] = CarSpecification::where('car_id', $id)->get();

        $information['related_cars'] = Car::join('car_contents', 'car_contents.car_id', 'cars.id')
            ->join('memberships', 'cars.vendor_id', '=', 'memberships.vendor_id')
            ->where([
                ['memberships.status', '=', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
            ])
            ->where('car_contents.language_id', $language->id)
            ->where('car_contents.category_id', $category_id)
            ->where('cars.id', '!=', $id)
            ->select('cars.*', 'car_contents.slug', 'car_contents.title', 'car_contents.language_id', 'car_contents.brand_id', 'car_contents.car_model_id')
            ->limit(8)->get();

        $information['latest_cars'] = Car::join('car_contents', 'car_contents.car_id', 'cars.id')
            ->join('memberships', 'cars.vendor_id', '=', 'memberships.vendor_id')
            ->where([
                ['memberships.status', '=', 1],
                ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
                ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
            ])
            ->where('car_contents.language_id', $language->id)
            ->where('cars.id', '!=', $id)
            ->orderBy('id', 'desc')
            ->select('cars.*', 'car_contents.slug', 'car_contents.language_id', 'car_contents.title', 'car_contents.brand_id', 'car_contents.car_model_id')
            ->limit(4)->get();

        $information['info'] = Basic::select('google_recaptcha_status')->first();

        return view('frontend.car.details', $information);
    }


    //contact
    public function contact(Request $request)
    {
        $mail_template = MailTemplate::where('mail_type', 'inquiry_about_car')->first();

        $rules = [
            'name' => 'required',
            'car_id' => 'required',
            'email' => 'required|email:rfc,dns',
            'phone' => 'required',
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

        $be = Basic::select('smtp_status', 'smtp_host', 'smtp_port', 'encryption', 'smtp_username', 'smtp_password', 'from_mail', 'from_name', 'to_mail', 'website_title')->firstOrFail();

        $misc = new MiscellaneousController();

        $language = $misc->getLanguage();
        $car = Car::with(['car_content' => function ($query) use ($language) {
            return $query->where('language_id', $language->id);
        }])->where('id', $request->car_id)->first();

        $car_name = $car->car_content->title;
        $slug = $car->car_content->slug;
        $url = route('frontend.car.details', ['slug' => $slug, 'id' => $request->car_id]);


        if ($car->vendor_id != 0) {
            $vendor = Vendor::where('id', $car->vendor_id)->select('email', 'username')->first();
            $send_email_address = $vendor->email;
            $user_name = $vendor->username;
        } else {
            $send_email_address = $be->to_mail;
            $user_name = 'Admin';
        }

        $request->validate($rules, $messageArray);



        if ($be->smtp_status == 111) {
            $subject = 'Inquiry about ' . $car_name;

            $body = $mail_template->mail_body;
            $body = preg_replace("/{username}/", $user_name, $body);
            $body = preg_replace("/{car_name}/", "<a href=" . $url . ">$car_name</a>", $body);
            $body = preg_replace("/{enquirer_name}/", $request->name, $body);
            $body = preg_replace("/{enquirer_email}/", $request->email, $body);
            $body = preg_replace("/{enquirer_phone}/", $request->phone, $body);
            $body = preg_replace("/{enquirer_message}/", nl2br($request->message), $body);
            $body = preg_replace("/{website_title}/", $be->website_title, $body);

            // if smtp status == 1, then set some value for PHPMailer
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
                $data = [
                    'to' => $send_email_address,
                    'subject' => $subject,
                    'body' => $body,
                ];

                Mail::send([], [], function (Message $message) use ($data, $be) {
                    $fromMail = $be->from_mail;
                    $fromName = $be->from_name;
                    $message->to($data['to'])
                        ->subject($data['subject'])
                        ->from($fromMail, $fromName)
                        ->html($data['body'], 'text/html');
                });

                Session::flash('success', 'Message sent successfully');
                return back();
            } catch (Exception $e) {
                Session::flash('error', $e);
                return back();
            }
        }
    }

    public function store_visitor(Request $request)
    {
        $request->validate([
            'car_id'
        ]);
        $ipAddress = \Request::ip();
        $check = Visitor::where([['car_id', $request->car_id], ['ip_address', $ipAddress], ['date', Carbon::now()->format('y-m-d')]])->first();

        $car = Car::where('id', $request->car_id)->first();
        if ($car) {
            if (!$check) {
                $visitor = new Visitor();
                $visitor->car_id = $request->car_id;
                $visitor->ip_address = $ipAddress;
                $visitor->vendor_id = $car->vendor_id;
                $visitor->date = Carbon::now()->format('y-m-d');
                $visitor->save();
            }
        }
    }
}
