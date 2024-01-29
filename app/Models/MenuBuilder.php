<?php

namespace App\Models;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuBuilder extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['language_id', 'menus'];

  public function languageInfo()
  {
    return $this->belongsTo(Language::class, 'language_id', 'id');
  }
}
