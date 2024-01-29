<?php

namespace App\Models\PaymentGateway;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflineGateway extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'short_description',
    'instructions',
    'status',
    'has_attachment',
    'serial_number'
  ];
}
