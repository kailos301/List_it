<?php

namespace App\Models\BasicSettings;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailTemplate extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['mail_subject', 'mail_body'];

  /**
   * Make timestamps false for mail-templates table.
   *
   * @var array
   */
  public $timestamps = false;
}
