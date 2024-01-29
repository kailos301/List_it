<?php

namespace App\Models\Car;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarSpecification extends Model
{
    use HasFactory;
    protected $fillable = [
        'car_id',
        'key',
    ];
}
