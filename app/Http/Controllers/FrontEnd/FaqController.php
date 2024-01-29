<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FrontEnd\MiscellaneousController;
use Illuminate\Http\Request;

class FaqController extends Controller
{
  public function faq()
  {
    $misc = new MiscellaneousController();

    $language = $misc->getLanguage();

    $queryResult['seoInfo'] = $language->seoInfo()->select('meta_keyword_faq', 'meta_description_faq')->first();

    $queryResult['pageHeading'] = $misc->getPageHeading($language);

    $queryResult['bgImg'] = $misc->getBreadcrumb();

    $queryResult['faqs'] = $language->faq()->orderBy('serial_number', 'asc')->get();

    //return view('frontend.faq', $queryResult);
     return response()->json($queryResult);
  }
}
