<?php

namespace App\Models\Shop;

use App\Models\Shop\ProductPurchaseItem;
use App\Models\Shop\ShippingCharge;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOrder extends Model
{
  use HasFactory;

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = [];

  public function userInfo()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function item()
  {
    return $this->hasMany(ProductPurchaseItem::class, 'product_order_id', 'id');
  }

  public function shippingMethod()
  {
    return $this->belongsTo(ShippingCharge::class, 'product_shipping_charge_id', 'id');
  }
}
