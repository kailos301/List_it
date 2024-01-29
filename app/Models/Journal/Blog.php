<?php

namespace App\Models\Journal;

use App\Models\Journal\BlogInformation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['image', 'serial_number'];

  public function information()
  {
    return $this->hasMany(BlogInformation::class);
  }
}
