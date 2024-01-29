<?php

namespace App\Models\Shop;

use App\Models\Shop\Product;
use App\Models\Shop\ProductOrder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPurchaseItem extends Model
{
  use HasFactory;

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = [];

  public function order()
  {
    return $this->belongsTo(ProductOrder::class, 'product_order_id', 'id');
  }

  public function productInfo()
  {
    return $this->belongsTo(Product::class, 'product_id', 'id');
  }
}
