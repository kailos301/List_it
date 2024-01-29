<?php

namespace App\Models\Car;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'language_id',
        'brand_id',
        'name',
        'slug',
        'status',
        'serial_number',
    ];

    //brand
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
