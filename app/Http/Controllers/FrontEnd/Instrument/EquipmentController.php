<?php

namespace App\Http\Controllers\FrontEnd\Instrument;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\Basic;
use App\Models\Instrument\Coupon;
use App\Models\Instrument\Equipment;
use App\Models\Instrument\EquipmentBooking;
use App\Models\Instrument\EquipmentCategory;
use App\Models\Instrument\EquipmentContent;
use App\Models\Instrument\EquipmentReview;
use App\Models\PaymentGateway\OfflineGateway;
use App\Models\PaymentGateway\OnlineGateway;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EquipmentController extends Controller
{
  public function index(Request $request)
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_equipment', 'meta_description_equipment')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $keyword = $sort = $category = $min = $max = $dates = $pricingType = null;
    $bookedEquipmentIds = [];

    if ($request->filled('keyword')) {
      $keyword = $request['keyword'];
    }
    if ($request->filled('sort')) {
      $sort = $request['sort'];
    }
    if ($request->filled('category')) {
      $category = $request['category'];
    }
    if ($request->filled('min') && $request->filled('max')) {
      $min = $request['min'];
      $max = $request['max'];
    }
    if ($request->filled('dates')) {
      $dates = $request['dates'];
    }
    if ($request->filled('pricing')) {
      $pricingType = $request['pricing'];
    }

    $allEquipment = Equipment::query()->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
      ->where('equipment_contents.language_id', '=', $language->id)
      ->when($keyword, function ($query, $keyword) {
        return $query->where('equipment_contents.title', 'like', '%' . $keyword . '%');
      })
      ->when($category, function ($query, $category) {
        $categoryId = EquipmentCategory::query()->where('slug', '=', $category)->pluck('id')->first();

        return $query->where('equipment_contents.equipment_category_id', '=', $categoryId);
      })
      ->when(($min && $max), function ($query) use ($min, $max) {
        return $query->where('equipments.lowest_price', '>=', $min)->where('equipments.lowest_price', '<=', $max);
      })
      ->when($dates, function ($query, $dates) use ($bookedEquipmentIds) {
        // get start & end date from the string
        $arrOfDate = explode(' ', $dates);
        $date_1 = $arrOfDate[0];
        $date_2 = $arrOfDate[2];

        // get all the dates between the start & end date
        $allDates = $this->getAllDates($date_1, $date_2, 'Y-m-d');

        $equipments = Equipment::all();

        // loop through all equipment
        foreach ($equipments as $equipment) {
          $equipId = $equipment->id;
          $equipQuantity = $equipment->quantity;

          // loop through the list of dates, which we have found from the start & end date
          foreach ($allDates as $date) {
            $currentDate = Carbon::parse($date);

            // count number of booking of a specific date
            $bookingCount = DB::table('equipment_bookings')->where('equipment_id', '=', $equipId)
              ->whereDate('start_date', '<=', $currentDate)
              ->whereDate('end_date', '>=', $currentDate)
              ->where('payment_status', '=', 'completed')
              ->count();

            // if the number of booking of a specific date is same as the equipment quantity, then mark that equipment as unavailable
            if (($bookingCount >= $equipQuantity) && !in_array($equipId, $bookedEquipmentIds)) {
              array_push($bookedEquipmentIds, $equipId);
            }
          }
        }

        return $query->whereNotIn('equipments.id', $bookedEquipmentIds);
      })
      ->when($pricingType, function ($query, $pricingType) {
        if ($pricingType == 'fixed price') {
          return $query->whereNotNull('equipments.lowest_price');
        } else {
          return $query->whereNull('equipments.lowest_price');
        }
      })
      ->select('equipments.id', 'equipments.thumbnail_image', 'equipments.lowest_price', 'equipment_contents.title', 'equipment_contents.slug', 'equipments.per_day_price', 'equipments.per_week_price', 'equipments.per_month_price', 'equipment_contents.features', 'equipments.offer', 'equipments.vendor_id')
      ->when($sort, function ($query, $sort) {
        if ($sort == 'new') {
          return $query->orderBy('equipments.created_at', 'desc');
        } else if ($sort == 'old') {
          return $query->orderBy('equipments.created_at', 'asc');
        } else if ($sort == 'ascending') {
          return $query->orderBy('equipments.lowest_price', 'asc');
        } else if ($sort == 'descending') {
          return $query->orderBy('equipments.lowest_price', 'desc');
        }
      }, function ($query) {
        return $query->orderByDesc('equipments.id');
      })
      ->paginate(4);

    $allEquipment->map(function ($equipment) {
      $avgRating = $equipment->review()->avg('rating');
      $ratingCount = $equipment->review()->count();

      $equipment['avgRating'] = floatval($avgRating);
      $equipment['ratingCount'] = $ratingCount;
    });

    $queryResult['allEquipment'] = $allEquipment;

    $queryResult['currencyInfo'] = $this->getCurrencyInfo();

    $queryResult['categories'] = $language->equipmentCategory()->where('status', 1)->orderBy('serial_number', 'asc')->get();

    $queryResult['minPrice'] = Equipment::query()->min('lowest_price');
    $queryResult['maxPrice'] = Equipment::query()->max('lowest_price');

    return view('frontend.equipment.index', $queryResult);
  }

  public function show($slug, Request $request)
  {
    $request->session()->put('redirectTo', url()->current());

    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $details = Equipment::query()->join('equipment_contents', 'equipments.id', '=', 'equipment_contents.equipment_id')
      ->join('equipment_categories', 'equipment_categories.id', '=', 'equipment_contents.equipment_category_id')
      ->where('equipment_contents.language_id', '=', $language->id)
      ->where('equipment_contents.slug', '=', $slug)
      ->select('equipments.id', 'equipments.vendor_id', 'equipments.slider_images', 'equipment_contents.title', 'equipment_categories.name as categoryName', 'equipment_categories.slug as categorySlug', 'equipment_contents.description', 'equipment_contents.features', 'equipments.lowest_price', 'equipments.per_day_price', 'equipments.per_week_price', 'equipments.per_month_price', 'equipments.min_booking_days', 'equipments.max_booking_days', 'equipment_contents.meta_keywords', 'equipment_contents.meta_description')
      ->firstOrFail();

    $queryResult['details'] = $details;

    $queryResult['currencyInfo'] = $this->getCurrencyInfo();

    $equipmentId = EquipmentContent::query()->where('language_id', '=', $language->id)
      ->where('slug', '=', $slug)
      ->pluck('equipment_id')
      ->first();

    $reviews = EquipmentReview::query()->where('equipment_id', '=', $equipmentId)->orderByDesc('id')->get();

    $reviews->map(function ($review) {
      $review['user'] = $review->userInfo()->first();
    });

    $queryResult['reviews'] = $reviews;

    $basicData = Basic::select('self_pickup_status', 'two_way_delivery_status', 'equipment_tax_amount', 'guest_checkout_status')->first();

    if ($details->vendor_id != NULL) {
      $basicData2 = Vendor::where('id', $details->vendor_id)->select('self_pickup_status', 'two_way_delivery_status')->first();
      $c_data = collect($basicData2);

      $c_data->put('equipment_tax_amount', $basicData->equipment_tax_amount);
      $c_data->put('guest_checkout_status', $basicData->guest_checkout_status);

      $queryResult['basicData'] = $c_data;
    } else {
      $queryResult['basicData'] = $basicData;
    }



    $equipment = Equipment::query()->findOrFail($equipmentId);
    $quantity = $equipment->quantity;

    $bookings = EquipmentBooking::query()->where('equipment_id', '=', $equipmentId)
      ->where('payment_status', '=', 'completed')
      ->orWhere('payment_status', '=', 'pending')
      ->select('start_date', 'end_date')
      ->get();


    $bookedDates = [];

    foreach ($bookings as $booking) {
      // get all the dates between the booking start date & booking end date
      $date_1 = $booking->start_date;
      $date_2 = $booking->end_date;

      $allDates = $this->getAllDates($date_1, $date_2, 'Y-m-d');

      // loop through the list of dates, which we have found from the booking start date & booking end date
      foreach ($allDates as $date) {
        $bookingCount = 0;

        // loop through all the bookings
        foreach ($bookings as $currentBooking) {
          $bookingStartDate = Carbon::parse($currentBooking->start_date);
          $bookingEndDate = Carbon::parse($currentBooking->end_date);
          $currentDate = Carbon::parse($date);

          // check for each date, whether the date is present or not in any of the booking date range
          if ($currentDate->betweenIncluded($bookingStartDate, $bookingEndDate)) {
            $bookingCount++;
          }
        }

        // if the number of booking of a specific date is same as the equipment quantity, then mark that date as unavailable
        if ($bookingCount >= $quantity && !in_array($date, $bookedDates)) {
          array_push($bookedDates, $date);
        }
      }
    }


    $queryResult['bookedDates'] = $bookedDates;

    if (!session()->has('shippingMethod')) {
      if ($basicData->self_pickup_status == 1 && $basicData->two_way_delivery_status == 1) {
        session()->put('shippingMethod', 'self pickup');
      } else if ($basicData->self_pickup_status == 1 && $basicData->two_way_delivery_status == 0) {
        session()->put('shippingMethod', 'self pickup');
      } else if ($basicData->self_pickup_status == 0 && $basicData->two_way_delivery_status == 1) {
        session()->put('shippingMethod', 'two way delivery');
      } else {
        session()->put('shippingMethod', null);
      }
    }

    if ($equipment) {
      $vendor_id = $equipment->vendor_id;
      if ($vendor_id != NULL) {
        $queryResult['locations'] = $language->location()->where('vendor_id', $vendor_id)->orderBy('serial_number', 'asc')->get();
      } else {
        $queryResult['locations'] = $language->location()->where('vendor_id', null)->orderBy('serial_number', 'asc')->get();
      }
    }



    $queryResult['onlineGateways'] = OnlineGateway::where('status', 1)->get();

    $queryResult['offlineGateways'] = OfflineGateway::where('status', 1)->orderBy('serial_number', 'asc')->get();

    return view('frontend.equipment.details', $queryResult);
  }

  /**
   * Get all the dates between the booking start date & booking end date.
   *
   * @param  string  $startDate
   * @param  string  $endDate
   * @param  string  $format
   * @return array
   */
  public function getAllDates($startDate, $endDate, $format)
  {
    $dates = [];

    // convert string to timestamps
    $currentTimestamps = strtotime($startDate);
    $endTimestamps = strtotime($endDate);

    // set an increment value
    $stepValue = '+1 day';

    // push all the timestamps to the 'dates' array by formatting those timestamps into date
    while ($currentTimestamps <= $endTimestamps) {
      $formattedDate = date($format, $currentTimestamps);
      array_push($dates, $formattedDate);
      $currentTimestamps = strtotime($stepValue, $currentTimestamps);
    }

    return $dates;
  }

  public function minPrice(Request $request, $id)
  {
    $dates = $request['dates'];
    $totalDays = $this->diffOfDates($dates);

    $equipment = Equipment::find($id);

    if (!empty($equipment)) {
      $perDayPrice = is_null($equipment->per_day_price) ? 0.00 : $equipment->per_day_price;
      $perWeekPrice = is_null($equipment->per_week_price) ? 0.00 : $equipment->per_week_price;
      $perMonthPrice = is_null($equipment->per_month_price) ? 0.00 : $equipment->per_month_price;
      $prices = [];

      // case: 1 -> calculate price according to month & day
      if ($perMonthPrice == 0.00 && $perDayPrice == 0.00) {
        array_push($prices, null);
      } else {
        $finalMonth = 1;
        $finalDay = 0;
        $month = $totalDays / 30;
        $finalMonth = floor($month);

        if ($this->isDecimal($month)) {
          $finalDay = $totalDays % 30;
        }

        $monthDayPrice = ($finalMonth * $perMonthPrice) + ($finalDay * $perDayPrice);
        array_push($prices, $monthDayPrice);
      }

      // case: 2 -> calculate price according to week & day
      if (!empty($perWeekPrice) && !empty($perDayPrice)) {
        $finalWeek = 1;
        $finalDay = 0;
        $week = $totalDays / 7;
        $finalWeek = floor($week);

        if ($this->isDecimal($week)) {
          $finalDay = $totalDays % 7;
        }

        $weekDayPrice = ($finalWeek * $perWeekPrice) + ($finalDay * $perDayPrice);
        array_push($prices, $weekDayPrice);
      }

      // case: 3 -> calculate price according to month, week & day
      if (!empty($perMonthPrice) && !empty($perWeekPrice) && !empty($perDayPrice)) {
        $finalMonth = 1;
        $finalWeek = 0;
        $finalDay = 0;

        if ($totalDays > 30) {
          $month = $totalDays / 30;
          $finalMonth = floor($month);

          if ($this->isDecimal($month)) {
            $day = $totalDays % 30;

            if ($day >= 7) {
              $week = $day / 7;
              $finalWeek = floor($week);

              if ($this->isDecimal($week)) {
                $finalDay = $day % 7;
              }
            } else {
              $finalDay = $day;
            }
          }
        }

        $monthWeekDayPrice = ($finalMonth * $perMonthPrice) + ($finalWeek * $perWeekPrice) + ($finalDay * $perDayPrice);
        array_push($prices, $monthWeekDayPrice);
      }

      // case: 4 -> calculate price according to month & week
      if (!empty($perMonthPrice) && !empty($perWeekPrice)) {
        $finalMonth = 1;
        $finalWeek = 0;

        if ($totalDays > 30) {
          $month = $totalDays / 30;
          $finalMonth = floor($month);

          if ($this->isDecimal($month)) {
            $day = $totalDays % 30;
            $finalWeek = 1;

            if ($day > 7) {
              $week = $day / 7;
              $finalWeek = floor($week);

              if ($this->isDecimal($week)) {
                $finalWeek = $finalWeek + 1;
              }
            }
          }
        }

        $monthWeekPrice = ($finalMonth * $perMonthPrice) + ($finalWeek * $perWeekPrice);
        array_push($prices, $monthWeekPrice);
      }

      // case: 5 -> calculate price according to only month
      if (!empty($perMonthPrice)) {
        $finalMonth = 1;

        if ($totalDays > 30) {
          $month = $totalDays / 30;
          $finalMonth = floor($month);

          if ($this->isDecimal($month)) {
            $finalMonth = $finalMonth + 1;
          }
        }

        $monthPrice = $finalMonth * $perMonthPrice;
        array_push($prices, $monthPrice);
      }


      // case: 6 -> calculate price according to only week
      if (!empty($perWeekPrice)) {
        $finalWeek = 1;

        if ($totalDays > 7) {
          $week = $totalDays / 7;
          $finalWeek = floor($week);

          if ($this->isDecimal($week)) {
            $finalWeek = $finalWeek + 1;
          }
        }

        $weekPrice = $finalWeek * $perWeekPrice;
        array_push($prices, $weekPrice);
      }


      // case: 7 -> calculate price according to only day
      if (!empty($perDayPrice)) {
        $dayPrice = $totalDays * $perDayPrice;
        array_push($prices, $dayPrice);
      }

      $priceArr = array_diff($prices, array(null, 0));
      $minimumPrice = min($priceArr);

      $request->session()->put('totalPrice', $minimumPrice);

      return response()->json(['minimumPrice' => $minimumPrice]);
    } else {
      return response()->json(['errorMessage' => 'Sorry, equipment not found!']);
    }
  }

  public function diffOfDates($dates)
  {
    $arrOfDate = explode(' ', $dates);
    $bookingStartDate = $arrOfDate[0];
    $bookingEndDate = $arrOfDate[2];

    $date1 = date_create($bookingStartDate);
    $date2 = date_create($bookingEndDate);
    $diff = date_diff($date1, $date2);
    $numOfDays = $diff->days + 1;

    return $numOfDays;
  }

  public function isDecimal($value)
  {
    return is_numeric($value) && floor($value) != $value;
  }

  public function changeShippingMethod(Request $request)
  {
    $request->session()->put('shippingMethod', $request['shippingMethod']);

    return response()->json(['success' => 'Shipping method changed'], 200);
  }

  public function applyCoupon(Request $request)
  {
    if (empty($request->dateRange)) {
      return response()->json(['error' => 'First, fillup the booking dates.']);
    } else {
      try {
        $coupon = Coupon::where('code', $request->coupon)->firstOrFail();

        $startDate = Carbon::parse($coupon->start_date);
        $endDate = Carbon::parse($coupon->end_date);
        $todayDate = Carbon::now();

        // check coupon is valid or not
        if ($todayDate->between($startDate, $endDate) == false) {
          return response()->json(['error' => 'Sorry, This coupon has been expired!']);
        }

        // check coupon is valid or not for this equipment
        $equipmentId = $request->equipmentId;
        $equipmentIds = empty($coupon->equipments) ? '' : json_decode($coupon->equipments);

        if (!empty($equipmentIds) && !in_array($equipmentId, $equipmentIds)) {
          return response()->json(['error' => 'You can not apply this coupon for this equipment!']);
        }

        // else proceed
        $total = $request->session()->get('totalPrice');

        if ($coupon->type == 'fixed') {
          $request->session()->put('equipmentDiscount', $coupon->value);

          return response()->json([
            'success' => 'Coupon applied successfully.',
            'amount' => $coupon->value
          ]);
        } else {
          $couponAmount = $total * ($coupon->value / 100);

          $request->session()->put('equipmentDiscount', $couponAmount);

          return response()->json([
            'success' => 'Coupon applied successfully.',
            'amount' => $couponAmount
          ]);
        }
      } catch (ModelNotFoundException $e) {
        return response()->json(['error' => 'Coupon is not valid!']);
      }
    }
  }

  public function storeReview(Request $request, $id)
  {
    $rule = ['rating' => 'required'];

    $validator = Validator::make($request->all(), $rule);

    if ($validator->fails()) {
      return redirect()->back()
        ->with('error', 'The rating field is required for equipment review.')
        ->withInput();
    }

    $equipmentBooked = false;

    // get the authenticate user
    $user = Auth::guard('web')->user();

    // then, get the bookings of that user
    $bookings = $user->equipmentBooking()->where('payment_status', 'completed')->orderBy('id', 'desc')->get();

    $vendor_id = NULL;

    if (count($bookings) > 0) {
      foreach ($bookings as $booking) {
        if ($booking->equipment_id == $id) {
          $equipmentBooked = true;
          if ($booking->equipment_id == $id && $booking->vendor_id != NULL) {
            $vendor_id = $booking->vendor_id;
          }
          break;
        }
      }

      if ($equipmentBooked == true) {
        EquipmentReview::updateOrCreate(
          ['user_id' => $user->id, 'equipment_id' => $id],
          ['comment' => $request->comment, 'rating' => $request->rating, 'vendor_id' => $vendor_id]
        );

        $request->session()->flash('success', 'Your review submitted successfully.');
      } else {
        $request->session()->flash('error', 'You have not booked this equipment yet!');
      }
    } else {
      $request->session()->flash('error', 'You have not booked anything yet!');
    }

    return redirect()->back();
  }
}
