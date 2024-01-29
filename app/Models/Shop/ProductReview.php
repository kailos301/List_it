<?php

namespace App\Models\Shop;

use App\Models\Shop\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['user_id', 'product_id', 'comment', 'rating'];

  public function userInfo()
  {
    return $this->belongsTo(User::class, 'user_id', 'id');
  }

  public function productInfo()
  {
    return $this->belongsTo(Product::class);
  }
}
