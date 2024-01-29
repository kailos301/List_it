<?php

namespace App\Models\Shop;

use App\Models\Language;
use App\Models\Shop\ProductContent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['language_id', 'name', 'slug', 'status', 'serial_number'];

  public function language()
  {
    return $this->belongsTo(Language::class);
  }

  public function productContent()
  {
    return $this->hasMany(ProductContent::class);
  }
  public function products()
  {
    return $this->hasMany(ProductContent::class);
  }
}
