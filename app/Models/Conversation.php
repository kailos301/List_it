<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
  use HasFactory;
  protected $fillable = [
    'user_id',
    'type',
    'support_ticket_id',
    'reply',
    'file',
  ];
}
