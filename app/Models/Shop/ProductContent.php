<?php

namespace App\Models\Shop;

use App\Models\Language;
use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductContent extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'product_category_id',
    'product_id',
    'title',
    'slug',
    'summary',
    'content',
    'meta_keywords',
    'meta_description'
  ];

  public function language()
  {
    return $this->belongsTo(Language::class);
  }

  public function category()
  {
    return $this->belongsTo(ProductCategory::class);
  }

  public function product()
  {
    return $this->belongsTo(Product::class);
  }
}
