<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use App\Models\BasicSettings\Basic;
use App\Models\Journal\Blog;
use App\Models\Journal\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
  public function index(Request $request)
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_blog', 'meta_description_blog')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $blogTitle = $blogCategory = null;

    if ($request->filled('title')) {
      $blogTitle = $request['title'];
    }
    if ($request->filled('category')) {
      $blogCategory = $request['category'];
    }

    $queryResult['blogs'] = Blog::join('blog_informations', 'blogs.id', '=', 'blog_informations.blog_id')
      ->join('blog_categories', 'blog_categories.id', '=', 'blog_informations.blog_category_id')
      ->where('blog_informations.language_id', '=', $language->id)
      ->when($blogTitle, function ($query, $blogTitle) {
        return $query->where('blog_informations.title', 'like', '%' . $blogTitle . '%');
      })
      ->when($blogCategory, function ($query, $blogCategory) {
        $categoryId = BlogCategory::query()->where('slug', '=', $blogCategory)->pluck('id')->first();

        return $query->where('blog_informations.blog_category_id', '=', $categoryId);
      })
      ->select('blogs.image', 'blog_categories.name as categoryName', 'blog_categories.slug AS categorySlug', 'blog_informations.title', 'blog_informations.slug', 'blog_informations.author', 'blogs.created_at', 'blog_informations.content')
      ->orderBy('blogs.serial_number', 'asc')
      ->paginate(9);

    $queryResult['categories'] = $this->getCategories($language);

    $queryResult['allBlogs'] = $language->blogInformation()->count();

    return view('frontend.journal.blog', $queryResult);
  }

  public function show($slug)
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $details = Blog::join('blog_informations', 'blogs.id', '=', 'blog_informations.blog_id')
      ->join('blog_categories', 'blog_categories.id', '=', 'blog_informations.blog_category_id')
      ->where('blog_informations.language_id', '=', $language->id)
      ->where('blog_informations.slug', '=', $slug)
      ->select('blogs.id', 'blogs.image', 'blogs.created_at', 'blog_informations.title', 'blog_informations.content', 'blog_informations.meta_keywords', 'blog_informations.meta_description', 'blog_categories.name as categoryName', 'blog_categories.slug as categorySlug')
      ->firstOrFail();

    $queryResult['details'] = $details;

    $queryResult['recent_blogs'] = Blog::join('blog_informations', 'blogs.id', '=', 'blog_informations.blog_id')
      ->where('blog_informations.language_id', '=', $language->id)
      ->where('blogs.id', '!=', $details->id)
      ->select('blogs.image',  'blog_informations.title', 'blog_informations.slug', 'blog_informations.author', 'blogs.created_at', 'blog_informations.content')
      ->orderBy('blogs.serial_number', 'asc')
      ->limit(3)->get();

    $queryResult['disqusInfo'] = Basic::select('disqus_status', 'disqus_short_name')->firstOrFail();

    $queryResult['categories'] = $this->getCategories($language);

    $queryResult['allBlogs'] = $language->blogInformation()->count();

    return view('frontend.journal.blog-details', $queryResult);
  }

  public function getCategories($language)
  {
    $categories = $language->blogCategory()->where('status', 1)->orderBy('serial_number', 'asc')->get();

    $categories->map(function ($category) {
      $category['blogCount'] = $category->blogInfo()->count();
    });

    return $categories;
  }
}
