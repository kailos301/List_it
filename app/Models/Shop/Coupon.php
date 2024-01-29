<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
  use HasFactory;

  protected $table = 'product_coupons';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'code',
    'type',
    'value',
    'start_date',
    'end_date',
    'minimum_spend',
    'serial_number'
  ];
}
