<?php

namespace App\Models\Car;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarSpecificationContent extends Model
{
    use HasFactory;
    protected $fillable = [
        'language_id',
        'car_specification_id',
        'label',
        'value',
    ];
}
