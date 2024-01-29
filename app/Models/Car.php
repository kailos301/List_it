<?php

namespace App\Models;

use App\Models\Car\Brand;
use App\Models\Car\CarContent;
use App\Models\Car\CarImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'feature_image',
        'vendor_id',
        'price',
        'previous_price',
        'speed',
        'year',
        'youtube_video',
        'mileage',
        'ad_type',
        'vregNo',
        'engineCapacity',
        'doors',
        'seats',
        'is_featured',
        'specification',
        'latitude',
        'longitude',
        'status',
    ];

    //car_content
    public function car_content()
    {
        return $this->hasOne(CarContent::class);
    }

    public function galleries()
    {
        return $this->hasMany(CarImage::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function visitors()
    {
        return $this->hasMany(Visitor::class);
    }
}
