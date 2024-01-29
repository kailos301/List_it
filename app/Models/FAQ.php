<?php

namespace App\Models;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
  use HasFactory;

  protected $table = 'faqs';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['language_id', 'question', 'answer', 'serial_number'];

  public function language()
  {
    return $this->belongsTo(Language::class);
  }
}
