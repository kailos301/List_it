<?php

namespace App\Models\HomePage\Hero;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
  use HasFactory;

  /**
   * The attributes that aren't mass assignable.
   *
   * @var array
   */
  protected $guarded = [];

  public function language()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }
}
