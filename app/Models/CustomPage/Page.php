<?php

namespace App\Models\CustomPage;

use App\Models\CustomPage\PageContent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['status'];

  public function content()
  {
    return $this->hasMany(PageContent::class, 'page_id', 'id');
  }
}
