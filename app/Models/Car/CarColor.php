<?php

namespace App\Models\Car;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarColor extends Model
{
    use HasFactory;
    protected $fillable = [
        'language_id',
        'name',
        'slug',
        'status',
        'serial_number',
    ];
}
