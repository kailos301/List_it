<?php

namespace App\Models\BasicSettings;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CookieAlert extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'cookie_alert_status',
    'cookie_alert_btn_text',
    'cookie_alert_text'
  ];

  public function cookieAlertLang()
  {
    return $this->belongsTo(Language::class);
  }
}
