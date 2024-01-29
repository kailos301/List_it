<?php

namespace App\Models\Shop;

use App\Models\Language;
use App\Models\Shop\ProductOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCharge extends Model
{
  use HasFactory;

  protected $table = 'product_shipping_charges';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'title',
    'short_text',
    'shipping_charge',
    'serial_number'
  ];

  public function language()
  {
    return $this->belongsTo(Language::class);
  }

  public function order()
  {
    return $this->hasMany(ProductOrder::class, 'product_shipping_charge_id', 'id');
  }
}
