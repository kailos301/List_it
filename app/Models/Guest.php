<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use NotificationChannels\WebPush\HasPushSubscriptions;

class Guest extends Model
{
  use HasFactory, Notifiable, HasPushSubscriptions;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['endpoint'];
}
