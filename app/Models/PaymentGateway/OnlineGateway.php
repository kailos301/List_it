<?php

namespace App\Models\PaymentGateway;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineGateway extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'keyword', 'information', 'status'];

  // as the timestamps is not needed, so make it false.
  public $timestamps = false;

  public function convertAutoData()
  {
    return json_decode($this->information, true);
  }
}
