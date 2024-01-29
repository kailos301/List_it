<?php

namespace App\Models\Footer;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterContent extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['language_id', 'about_company', 'copyright_text'];

  public function contentLang()
  {
    return $this->belongsTo(Language::class);
  }
}
