<?php

namespace App\Models\Journal;

use App\Models\Journal\Blog;
use App\Models\Journal\BlogCategory;
use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogInformation extends Model
{
  use HasFactory;

  protected $table = 'blog_informations';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'language_id',
    'blog_category_id',
    'blog_id',
    'title',
    'slug',
    'author',
    'content',
    'meta_keywords',
    'meta_description'
  ];

  public function language()
  {
    return $this->belongsTo(Language::class);
  }

  public function blogCategory()
  {
    return $this->belongsTo(BlogCategory::class);
  }

  public function blog()
  {
    return $this->belongsTo(Blog::class);
  }
}
