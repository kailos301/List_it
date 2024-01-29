<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Http\Helpers\UploadFile;
use App\Models\HomePage\Banner;
use App\Models\Language;
use App\Rules\ImageMimeTypeRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $language = Language::query()->where('code', '=', $request->language)->first();
        $information['language'] = $language;
        $information['langs'] = Language::all();

        $banners = Banner::where('language_id', $language->id)->orderByDesc('id')->get();

        $information['banners'] = $banners;

        return view('backend.home-page.banner.index', $information);
    }

    public function store(Request $request)
    {
        $rules = [
            'image' => [
                'required',
                $request->hasFile('image') ? new ImageMimeTypeRule() : ''
            ],
            'language_id' => 'required',
            'url' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $imageName = UploadFile::store(public_path('assets/img/banners/'), $request->file('image'));

        Banner::create($request->except('image') + [
            'image' => $imageName
        ]);

        Session::flash('success', 'New banner added successfully!');

        return response()->json(['status' => 'success'], 200);
    }

    public function update(Request $request)
    {
        $rules = [
            'image' => $request->hasFile('image') ? new ImageMimeTypeRule() : '',
            'url' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $banner = Banner::where('id', $request->id)->first();

        if ($request->hasFile('image')) {
            $newImage = $request->file('image');
            $oldImage = $banner->image;
            $imageName = UploadFile::update(public_path('assets/img/banners/'), $newImage, $oldImage);
            @unlink(public_path('assets/img/banners/') . $banner->image);
        }

        $banner->update($request->except('image') + [
            'image' => $request->hasFile('image') ? $imageName : $banner->image
        ]);

        Session::flash('success', 'Banner updated successfully!');

        return response()->json(['status' => 'success'], 200);
    }

    public function destroy(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        @unlink(public_path('assets/img/banners/') . $banner->image);

        $banner->delete();

        return redirect()->back()->with('success', 'Banner deleted successfully!');
    }
}
