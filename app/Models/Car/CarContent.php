<?php

namespace App\Models\Car;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarContent extends Model
{
    use HasFactory;
    protected $fillable = [
        'language_id',
        'car_id',
        'category_id',
        'main_category_id',
        'car_condition_id',
        'brand_id',
        'car_model_id',
        'body_type_id',
        'fuel_type_id',
        'transmission_type_id',
        'title',
        'slug',
        'description',
        'address',
        'meta_keyword',
        'meta_description',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function model()
    {
        return $this->belongsTo(CarModel::class, 'car_model_id', 'id');
    }
    public function body_type()
    {
        return $this->belongsTo(BodyType::class);
    }
    public function fuel_type()
    {
        return $this->belongsTo(FuelType::class);
    }
    public function transmission_type()
    {
        return $this->belongsTo(TransmissionType::class);
    }
}
