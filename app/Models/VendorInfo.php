<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'language_id',
        'name',
        'business_name',
        'vat_number',
        'business_address',
        'shop_name',
        'country',
        'city',
        'state',
        'zip_code',
        'address',
        'details'
    ];
}
//nvii mfpl wzis cjhl 