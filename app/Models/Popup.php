<?php

namespace App\Models;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'type',
    'image',
    'name',
    'background_color',
    'background_color_opacity',
    'title',
    'text',
    'button_text',
    'button_color',
    'button_url',
    'end_date',
    'end_time',
    'delay',
    'serial_number',
    'status'
  ];

  public function popupLang()
  {
    return $this->belongsTo(Language::class);
  }
}
