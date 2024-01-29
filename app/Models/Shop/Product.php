<?php

namespace App\Models\Shop;

use App\Models\Shop\ProductContent;
use App\Models\Shop\ProductPurchaseItem;
use App\Models\Shop\ProductReview;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'product_type',
    'vendor_id',
    'featured_image',
    'slider_images',
    'status',
    'input_type',
    'file',
    'link',
    'stock',
    'current_price',
    'previous_price',
    'average_rating',
    'is_featured'
  ];

  public function content()
  {
    return $this->hasMany(ProductContent::class);
  }

  public function purchase()
  {
    return $this->hasMany(ProductPurchaseItem::class, 'product_id', 'id');
  }

  public function review()
  {
    return $this->hasMany(ProductReview::class);
  }
}
