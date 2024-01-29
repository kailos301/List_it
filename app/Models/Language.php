<?php

namespace App\Models;

use App\Models\BasicSettings\CookieAlert;
use App\Models\BasicSettings\PageHeading;
use App\Models\BasicSettings\SEO;
use App\Models\Car\BodyType;
use App\Models\Car\Brand;
use App\Models\Car\CarColor;
use App\Models\Car\CarContent;
use App\Models\Car\CarModel;
use App\Models\Car\CarSpecificationContent;
use App\Models\Car\Category;
use App\Models\Car\FuelType;
use App\Models\Car\TransmissionType;
use App\Models\CustomPage\PageContent;
use App\Models\FAQ;
use App\Models\Footer\FooterContent;
use App\Models\Footer\QuickLink;
use App\Models\HomePage\Banner;
use App\Models\HomePage\BlogSection;
use App\Models\HomePage\CallToActionSection;
use App\Models\HomePage\CategorySection;
use App\Models\HomePage\CounterInformation;
use App\Models\HomePage\Hero\Slider;
use App\Models\HomePage\Methodology\WorkProcess;
use App\Models\HomePage\Methodology\WorkProcessSection;
use App\Models\HomePage\Prominence\Feature;
use App\Models\HomePage\Testimony\Testimonial;
use App\Models\HomePage\Testimony\TestimonialSection;
use App\Models\Journal\BlogCategory;
use App\Models\Journal\BlogInformation;
use App\Models\MenuBuilder;
use App\Models\Popup;
use App\Models\Prominence\FeatureSection;
use App\Models\Shop\ProductCategory;
use App\Models\Shop\ProductContent;
use App\Models\Shop\ShippingCharge;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'code', 'direction', 'is_default'];






  public function faq()
  {
    return $this->hasMany(FAQ::class);
  }

  public function customPageInfo()
  {
    return $this->hasMany(PageContent::class);
  }

  public function footerContent()
  {
    return $this->hasOne(FooterContent::class);
  }

  public function footerQuickLink()
  {
    return $this->hasMany(QuickLink::class);
  }

  public function announcementPopup()
  {
    return $this->hasMany(Popup::class);
  }

  public function blogCategory()
  {
    return $this->hasMany(BlogCategory::class);
  }

  public function blogInformation()
  {
    return $this->hasMany(BlogInformation::class);
  }

  public function menuInfo()
  {
    return $this->hasOne(MenuBuilder::class, 'language_id', 'id');
  }


  public function workProcessSection()
  {
    return $this->hasOne(WorkProcessSection::class, 'language_id', 'id');
  }

  public function workProcess()
  {
    return $this->hasMany(WorkProcess::class, 'language_id', 'id');
  }

  public function featureSection()
  {
    return $this->hasOne(FeatureSection::class, 'language_id', 'id');
  }

  public function feature()
  {
    return $this->hasMany(Feature::class, 'language_id', 'id');
  }

  public function counterInfo()
  {
    return $this->hasMany(CounterInformation::class, 'language_id', 'id');
  }
  public function counterSection()
  {
    return $this->hasMany(CounterSection::class, 'language_id', 'id');
  }

  public function testimonialSection()
  {
    return $this->hasOne(TestimonialSection::class, 'language_id', 'id');
  }

  public function testimonial()
  {
    return $this->hasMany(Testimonial::class, 'language_id', 'id');
  }

  public function callToActionSection()
  {
    return $this->hasOne(CallToActionSection::class, 'language_id', 'id');
  }

  public function blogSection()
  {
    return $this->hasOne(BlogSection::class, 'language_id', 'id');
  }

  public function shippingCharge()
  {
    return $this->hasMany(ShippingCharge::class);
  }

  public function productCategory()
  {
    return $this->hasMany(ProductCategory::class);
  }

  public function productContent()
  {
    return $this->hasMany(ProductContent::class);
  }


  //new relation are goes here
  public function carCategory()
  {
    return $this->hasMany(Category::class);
  }
  public function CarColor()
  {
    return $this->hasMany(CarColor::class);
  }
  public function carBrand()
  {
    return $this->hasMany(Brand::class);
  }
  public function carModel()
  {
    return $this->hasMany(CarModel::class);
  }
  public function fuelType()
  {
    return $this->hasMany(FuelType::class);
  }
  public function TransmissionType()
  {
    return $this->hasMany(TransmissionType::class);
  }
  public function carContents()
  {
    return $this->hasOne(CarContent::class);
  }
  public function CarSpecificationContents()
  {
    return $this->hasMany(CarSpecificationContent::class);
  }

  public function CategorySection()
  {
    return $this->hasMany(CategorySection::class);
  }

  public function vendorInfo()
  {
    return $this->hasOne(VendorInfo::class);
  }

  public function sliderInfo()
  {
    return $this->hasMany(Slider::class, 'language_id', 'id');
  }

  public function banner()
  {
    return $this->hasOne(Banner::class);
  }
  public function pageName()
  {
    return $this->hasOne(PageHeading::class);
  }

  public function seoInfo()
  {
    return $this->hasOne(SEO::class);
  }
  public function cookieAlertInfo()
  {
    return $this->hasOne(CookieAlert::class);
  }
}
