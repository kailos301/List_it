<?php

namespace App\Models\Journal;

use App\Models\Journal\BlogInformation;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['language_id', 'name', 'slug', 'status', 'serial_number'];

  public function categoryLang()
  {
    return $this->belongsTo(Language::class);
  }

  public function blogInfo()
  {
    return $this->hasMany(BlogInformation::class);
  }
}
