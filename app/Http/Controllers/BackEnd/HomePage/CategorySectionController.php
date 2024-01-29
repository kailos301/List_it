<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\HomePage\CategorySection;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CategorySectionController extends Controller
{
    public function index(Request $request)
    {
        $information['info'] = DB::table('basic_settings')->select('category_section_background')->first();

        $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
        $information['language'] = $language;

        $information['data'] = CategorySection::where('language_id', $language->id)->first();

        $information['langs'] = Language::all();

        return view('backend.home-page.category-section', $information);
    }

    public function updateImage(Request $request)
    {
        $data = DB::table('basic_settings')->select('category_section_background')->first();

        $rules = [];

        if (empty($data->category_section_background)) {
            $rules['category_section_background'] = 'required';
        }
        if ($request->hasFile('category_section_background')) {
            $rules['category_section_background'] = new ImageMimeTypeRule();
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        if ($request->hasFile('category_section_background')) {
            $newImage = $request->file('category_section_background');
            $oldImage = $data->category_section_background;

            $imgName = UploadFile::update(public_path('assets/img/'), $newImage, $oldImage);

            // finally, store the image into db
            DB::table('basic_settings')->updateOrInsert(
                ['uniqid' => 12345],
                ['category_section_background' => $imgName]
            );

            Session::flash('success', 'Image updated successfully!');
        }

        return redirect()->back();
    }


    public function update(Request $request)
    {
        $language = Language::query()->where('code', '=', $request->language)->first();

        CategorySection::query()->updateOrCreate(
            ['language_id' => $language->id],
            [
                'subtitle' => $request->subtitle,
                'title' => $request->title,
                'text' => clean($request->text),
                'button_text' => $request->button_text
            ]
        );

        Session::flash('success', 'About section updated successfully!');

        return redirect()->back();
    }
}
