<?php

namespace App\Http\Controllers\BackEnd\HomePage;

use App\Http\Controllers\Controller;
use App\Models\HomePage\Methodology\WorkProcess;
use App\Models\HomePage\Methodology\WorkProcessSection;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class WorkProcessController extends Controller
{
  public function sectionInfo(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->firstOrFail();
    $information['language'] = $language;

    $information['data'] = $language->workProcessSection()->first();

    $information['processes'] = $language->workProcess()->orderByDesc('id')->get();

    $information['langs'] = Language::all();

    return view('backend.home-page.work-process-section.index', $information);
  }

  public function updateSectionInfo(Request $request)
  {
    $language = Language::query()->where('code', '=', $request->language)->first();

    WorkProcessSection::query()->updateOrCreate(
      ['language_id' => $language->id],
      [
        'subtitle' => $request->subtitle,
        'title' => $request->title,
      ]
    );

    $request->session()->flash('success', 'Work process section updated successfully!');

    return redirect()->back();
  }


  public function storeWorkProcess(Request $request)
  {
    $rules = [
      'language_id' => 'required',
      'icon' => 'required',
      'title' => 'required',
      'text' => 'required',
      'serial_number' => 'required|numeric'
    ];

    $message = [
      'language_id.required' => 'The language field is required.'
    ];

    $validator = Validator::make($request->all(), $rules, $message);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    WorkProcess::query()->create($request->except('language'));

    $request->session()->flash('success', 'New work process added successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function updateWorkProcess(Request $request)
  {
    $rules = [
      'title' => 'required',
      'text' => 'required',
      'serial_number' => 'required|numeric'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()
      ], 400);
    }

    $workProcess = WorkProcess::query()->find($request->id);

    $workProcess->update($request->except('language'));

    $request->session()->flash('success', 'Work process updated successfully!');

    return Response::json(['status' => 'success'], 200);
  }

  public function destroyWorkProcess($id)
  {
    $workProcess = WorkProcess::query()->find($id);

    $workProcess->delete();

    return redirect()->back()->with('success', 'Work process deleted successfully!');
  }

  public function bulkDestroyWorkProcess(Request $request)
  {
    $ids = $request['ids'];

    foreach ($ids as $id) {
      $workProcess = WorkProcess::query()->find($id);

      $workProcess->delete();
    }

    $request->session()->flash('success', 'Work processes deleted successfully!');

    return Response::json(['status' => 'success'], 200);
  }
}
