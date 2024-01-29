<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\Basic;
use App\Http\Requests\Car\CarStoreRequest;
use App\Models\Car;
use App\Models\Car\Brand;
use App\Models\Car\CarColor;
use App\Models\Car\CarContent;
use App\Models\Car\CarModel;
use App\Models\Car\CarSpecification;
use App\Models\Car\CarSpecificationContent;
use App\Models\Car\Category;
use App\Models\Language;
use App\Models\Car\CarImage;
use App\Models\CounterSection;
use App\Models\HomePage\Banner;
use App\Models\HomePage\CategorySection;
use App\Models\HomePage\Section;
use App\Models\Journal\Blog;
use App\Models\HomePage\Partner;
use App\Models\Prominence\FeatureSection;
use App\Models\CountryArea;
use App\Models\CarYear;
use App\Models\AdsPrice;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Purifier;

class HomeController extends Controller
{
  public function index()
  {
    $themeVersion = Basic::query()->pluck('theme_version')->first();

    $secInfo = Section::query()->first();

    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['language'] = $language;

    $queryResult['car_categories'] = Category::where('language_id', $language->id)->where('status', 1)->where('parent_id', 24)->orderBy('serial_number', 'asc')->get();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_home', 'meta_description_home')->first();


    $queryResult['sliderInfos'] = $language->sliderInfo()->orderByDesc('id')->get();

    if ($secInfo->about_section_status == 1) {
      $queryResult['aboutSectionImage'] = Basic::query()->pluck('about_section_image')->first();
      $queryResult['aboutSecInfo'] = $language->aboutSection()->first();
    }
    if ($themeVersion == 2) {
      $queryResult['categorySectionImage'] = Basic::query()->pluck('category_section_background')->first();
    }
    $queryResult['catgorySecInfo'] = CategorySection::where('language_id', $language->id)->first();
    $queryResult['featuredSecInfo'] = FeatureSection::where('language_id', $language->id)->first();

    if ($themeVersion == 1) {
      $queryResult['banners'] = Banner::where('language_id', $language->id)->get();
    }

    if ($secInfo->work_process_section_status == 1 && $themeVersion == 2) {
      $queryResult['workProcessSecInfo'] = $language->workProcessSection()->first();
      $queryResult['processes'] = $language->workProcess()->orderBy('serial_number', 'asc')->get();
    }

    if ($secInfo->counter_section_status == 1) {
      $queryResult['counterSectionImage'] = Basic::query()->pluck('counter_section_image')->first();
      $queryResult['counterSectionInfo'] = CounterSection::where('language_id', $language->id)->first();
      $queryResult['counters'] = $language->counterInfo()->orderByDesc('id')->get();
    }

    $queryResult['currencyInfo'] = $this->getCurrencyInfo();

    $min = Car::min('price');
    $max = Car::max('price');

    $queryResult['min'] = intval($min);
    $queryResult['max'] = intval($max);

    if ($secInfo->testimonial_section_status == 1) {
      $queryResult['testimonialSecInfo'] = $language->testimonialSection()->first();
      $queryResult['testimonials'] = $language->testimonial()->orderByDesc('id')->get();
      $queryResult['testimonialSecImage'] = Basic::query()->pluck('testimonial_section_image')->first();
    }

    if ($themeVersion != 1 && $secInfo->call_to_action_section_status == 1) {
      $queryResult['callToActionSectionImage'] = Basic::query()->pluck('call_to_action_section_image')->first();
      $queryResult['callToActionSecInfo'] = $language->callToActionSection()->first();
    }

    if ($secInfo->blog_section_status == 1) {
      $queryResult['blogSecInfo'] = $language->blogSection()->first();

      $queryResult['blogs'] = Blog::query()->join('blog_informations', 'blogs.id', '=', 'blog_informations.blog_id')
        ->join('blog_categories', 'blog_categories.id', '=', 'blog_informations.blog_category_id')
        ->where('blog_informations.language_id', '=', $language->id)
        ->select('blogs.image', 'blog_categories.name AS categoryName', 'blog_categories.slug AS categorySlug', 'blog_informations.title', 'blog_informations.slug', 'blog_informations.author', 'blogs.created_at', 'blog_informations.content')
        ->orderBy('blogs.serial_number', 'desc')
        ->limit(3)
        ->get();
    }

    $queryResult['cars'] = Car::join('car_contents', 'car_contents.car_id', 'cars.id')
      ->join('memberships', 'cars.vendor_id', '=', 'memberships.vendor_id')
      ->join('vendors', 'cars.vendor_id', '=', 'vendors.id')
      ->where([
        ['memberships.status', '=', 1],
        ['memberships.start_date', '<=', Carbon::now()->format('Y-m-d')],
        ['memberships.expire_date', '>=', Carbon::now()->format('Y-m-d')]
      ])
      ->where([['vendors.status', 1], ['cars.is_featured', 1], ['cars.status', 1]])
      ->where('car_contents.language_id', $language->id)
      ->inRandomOrder()
      ->limit(8)
      ->select('cars.*', 'car_contents.slug', 'car_contents.title', 'car_contents.car_model_id', 'car_contents.brand_id')
      ->get();

    $queryResult['car_conditions'] = CarColor::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();

    $categories = Category::has('car_contents')->where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();
    $queryResult['categories'] = $categories;
    $queryResult['carlocation'] = CountryArea::where('status', 1)->orderBy('name', 'asc')->get();
     $queryResult['caryear'] = CarYear::where('status', 1)->orderBy('name', 'desc')->get();
     $queryResult['adsprices'] = AdsPrice::where('status', 1)->orderBy('name', 'asc')->get();


    $queryResult['brands'] = Brand::where('language_id', $language->id)->where('status', 1)->orderBy('serial_number', 'asc')->get();

    $queryResult['secInfo'] = $secInfo;

    if ($themeVersion == 1) {
      return view('frontend.home.index-v1', $queryResult);
    } elseif ($themeVersion == 2) {
      return view('frontend.home.index-v2', $queryResult);
    } elseif ($themeVersion == 3) {
      return view('frontend.home.index-v3', $queryResult);
    }
  }

  public function get_model(Request $request)
  {
    $slug = $request->id;

    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();

    $car_brand = Brand::where([['language_id', $language->id], ['slug', $slug]])->first();
    if ($car_brand) {
      $models = CarModel::where([['brand_id', $car_brand->id], ['status', 1], ['language_id', $language->id]])->orderBy('serial_number', 'asc')->get();
      return $models;
    } else {
      return [];
    }
  }
  public function suggestions(Request $request) {
    $searchTerm = $request->keyword;
    $cars = CarContent::where('title', 'LIKE', '%' . $searchTerm . '%')
    ->selectRaw('category_id, count(*) as total')
    ->with('category')
    ->groupBy('category_id')
    ->limit(25)->get();
    $html = view('frontend.home.autocomplete', compact('searchTerm','cars'))->render();
    
    return response()->json(['code' => 200, 'message' => 'successful.','data' =>$html]); 
  }
  public function vehicleData(Request $request){

    //echo "VehicleData"; exit;
        $apiarray = [];
      // Init cURL session
        $curl = curl_init();

        // Set API Key
        $ApiKey = "3486bd09-5f3d-4be3-a7ce-580de4749564";
        $vehReg = $request->vehiclereg;
        // Construct URL String
        $url = "https://uk1.ukvehicledata.co.uk/api/datapackage/%s?v=2&api_nullitems=1&key_vrm=%s&auth_apikey=%s";
        $url = sprintf($url, "VehicleDataIRL", $vehReg, $ApiKey); // Syntax: sprintf($url, "PackageName", "VRM", ApiKey);
        // Note your package name here. There are 5 standard packagenames. Please see your control panel > weblookup or contact your account manager

        // Create array of options for the cURL session
        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET"
        ));

        // Execute cURL session and store the response in $response
        $response = curl_exec($curl);

        // If the operation failed, store the error message in $error
        $error = curl_error($curl);

        // Close cURL session
        curl_close($curl);
//echo "cURL Error: " . $error; exit;
        // If there was an error, print it to screen. Otherwise, unserialize response and print to screen.
        if ($error) {
          echo "cURL Error: " . $error;
        } else {
          $data = json_decode($response, true);
          // check if Brand exist.
          $vMake = ucfirst(strtolower($data['Response']['DataItems']['RoiVehicleDetails']['Make']));
           $car_brand = Brand::where('name', $vMake)->first();
           if ($car_brand) {
              $makeId = $car_brand->id;
           } else {
           $create = Brand::create([
            'name' => $vMake,
            'slug' => createSlug($vMake),
            'language_id' => 20,
            'status' => 1,
            'serial_number' => 1
            
        ]);
           $makeId = $create->id;

           }

           $vModel = ucfirst(strtolower($data['Response']['DataItems']['RoiVehicleDetails']['Model']));
           $car_model = CarModel::where('brand_id', $makeId)->first();
           if ($car_model) {
              $modelId = $car_model->id;
           } else {
           $createModel = CarModel::create([
            'name' => $vModel,
            'slug' => createSlug($vModel),
            'language_id' => 20,
            'status' => 1,
            'brand_id' => $makeId,
            'serial_number' => 1
            
        ]);
           $modelId = $createModel->id;

           }
      $apiarray["response"] = $data['Response']['StatusCode'] ;
     foreach($data['Response']['DataItems']['RoiVehicleDetails'] as $key=>$val){
        $apiarray[$key] = $val;

      }
      $apiarray["makeID"] = $makeId;
      $apiarray["modelID"] = $modelId;
      

       
    return response()->json(['code' => 200, 'message' => 'successful.','data' =>$apiarray]); exit;
   // echo json_encode($cardata);

          
        }
  }
  public function tabsData(Request $request){
    
    $misc = new MiscellaneousController();
    $language = $misc->getLanguage();
    $queryResult['language'] = $language;
    $hmlList = "";
    $car_categories = Category::where('language_id', $language->id)->where('status', 1)->where('parent_id', $request->catid)->orderBy('serial_number', 'asc')->get();
    foreach($car_categories as $category){
      $img = ($category->image ? $category->image : 'noicon.png').'';
      $hmlList .='<div class="col-lg-3 col-sm-6" data-aos="fade-up">
                  <a href="'.route('frontend.cars', ['category' => $category->slug]).'">
                    <div class="category-item">
                      <div class="d-flex flex-wrep justify-content-between mb-10">
                        <h6 class="category-title mb-10">
              <img class="lazyload blur-up" data-src="'.asset('assets/admin/img/car-category/' . $img).'"  >  '. $category->name .'
                        </h6>
                         
                      </div>
                     
                    </div>
                  </a>
                </div>';

    }
    return response()->json(['code' => 200, 'message' => 'successful.','data' =>$hmlList]); exit;
  }
  //about
  public function about()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keywords_about_page', 'meta_description_about_page')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();
    $secInfo = Section::query()->first();
    $queryResult['secInfo'] = $secInfo;

    if ($secInfo->work_process_section_status == 1) {
      $queryResult['workProcessSecInfo'] = $language->workProcessSection()->first();
      $queryResult['processes'] = $language->workProcess()->orderBy('serial_number', 'asc')->get();
    }

    if ($secInfo->testimonial_section_status == 1) {
      $queryResult['testimonialSecInfo'] = $language->testimonialSection()->first();
      $queryResult['testimonials'] = $language->testimonial()->orderByDesc('id')->get();
      $queryResult['testimonialSecImage'] = Basic::query()->pluck('testimonial_section_image')->first();
    }

    if ($secInfo->counter_section_status == 1) {
      $queryResult['counterSectionImage'] = Basic::query()->pluck('counter_section_image')->first();
      $queryResult['counterSectionInfo'] = CounterSection::where('language_id', $language->id)->first();
      $queryResult['counters'] = $language->counterInfo()->orderByDesc('id')->get();
    }

    return view('frontend.about', $queryResult);
  }

  //offline
  public function offline()
  {
    return view('frontend.offline');
  }
  
}
