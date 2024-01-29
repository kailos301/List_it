<?php

namespace App\Models\CustomPage;

use App\Models\CustomPage\Page;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageContent extends Model
{
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'page_id',
    'title',
    'slug',
    'content',
    'meta_keywords',
    'meta_description'
  ];

  public function contentLang()
  {
    return $this->belongsTo(Language::class);
  }

  public function page()
  {
    return $this->belongsTo(Page::class, 'page_id', 'id');
  }
}
