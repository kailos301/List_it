<?php

namespace App\Http\Controllers\BackEnd\BasicSettings;

use App\Http\Controllers\Controller;
use App\Models\BasicSettings\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SocialMediaController extends Controller
{
  public function index()
  {
    $information['medias'] = SocialMedia::orderByDesc('id')->get();

    return view('backend.basic-settings.social-media.index', $information);
  }

  public function store(Request $request)
  {
    $rules = [
      'icon' => 'required',
      'url' => 'required|url',
      'serial_number' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    SocialMedia::create($request->all());

    $request->session()->flash('success', 'New social media added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function update(Request $request)
  {
    $rules = [
      'url' => 'required|url',
      'serial_number' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    SocialMedia::find($request->id)->update($request->all());

    $request->session()->flash('success', 'Social media updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroy($id)
  {
    SocialMedia::find($id)->delete();

    return redirect()->back()->with('success', 'Social media deleted successfully!');
  }
}
