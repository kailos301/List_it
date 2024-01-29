<?php

namespace App\Models\BasicSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialMedia extends Model
{
  use HasFactory;

  protected $table = 'social_medias';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['icon', 'url', 'serial_number'];
}
