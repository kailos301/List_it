<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\SupportTicket;
use App\Models\SupportTicketStatus;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Purifier;

class SupportTicketController extends Controller
{
    public function index()
    {
        $s_status = SupportTicketStatus::first();
        if ($s_status->support_ticket_status != 'active') {
            return redirect()->route('user.dashboard');
        }

        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();
        $queryResult['pageHeading'] = $misc->getPageHeading($language);

        $queryResult['bgImg'] = $misc->getBreadcrumb();

        $collection = SupportTicket::where([['user_id', Auth::guard('web')->user()->id], ['user_type', 'user']])->orderBy('id', 'desc')->get();
        $queryResult['collection'] = $collection;
        return view('frontend.user.support_ticket.index', $queryResult);
    }

    public function create()
    {
        $s_status = SupportTicketStatus::first();
        if ($s_status->support_ticket_status != 'active') {
            return redirect()->route('user.dashboard');
        }

        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();

        $queryResult['pageHeading'] = $misc->getPageHeading($language);
        $queryResult['bgImg'] = $misc->getBreadcrumb();
        return view('frontend.user.support_ticket.create', $queryResult);
    }

    //store
    public function store(Request $request)
    {
        $rules = [
            'subject' => 'required',
            'email' => 'required',
            'description' => 'required',
        ];
        if ($request->hasFile('attachment')) {
            $rules['attachment'] = 'mimes:zip|max:20000';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->getMessageBag());
        }

        $in = $request->all();
        $in['user_id'] = Auth::guard('web')->user()->id;
        $in['user_type'] = 'user';
        $file = $request->file('attachment');
        if ($file) {
            $extension = $file->getClientOriginalExtension();
            $directory = public_path('assets/admin/img/support-ticket/attachment/');
            $fileName = uniqid() . '.' . $extension;
            @mkdir($directory, 0775, true);
            $file->move($directory, $fileName);
            $in['attachment'] = $fileName;
        }
        $in['description'] = Purifier::clean($request->description, 'youtube');
        SupportTicket::create($in);
        Session::flash('success', 'Ticket has been submitted successfully.');
        return back();
    }

    //message
    public function message($id)
    {
        $s_status = SupportTicketStatus::first();
        if ($s_status->support_ticket_status != 'active') {
            return redirect()->route('user.dashboard');
        }
        $misc = new MiscellaneousController();
        $language = $misc->getLanguage();

        $queryResult['pageHeading'] = $misc->getPageHeading($language);
        $queryResult['bgImg'] = $misc->getBreadcrumb();
        $bex = SupportTicketStatus::first();

        if ($bex->support_ticket_status == 0) {
            return back();
        }
        $queryResult['ticket'] = SupportTicket::where('id', $id)->firstOrFail();

        return view('frontend.user.support_ticket.message', $queryResult);
    }
    //reply
    public function reply(Request $request, $id)
    {
        $s_status = SupportTicketStatus::first();
        if ($s_status->support_ticket_status != 'active') {
            return redirect()->route('user.dashboard');
        }
        $file = $request->file('file');
        $allowedExts = array('zip');
        $rules = [
            'reply' => 'required',
            'file' => [
                function ($attribute, $value, $fail) use ($file, $allowedExts) {

                    $ext = $file->getClientOriginalExtension();
                    if (!in_array($ext, $allowedExts)) {
                        return $fail("Only zip file supported");
                    }
                },
                'max:20000'
            ],
        ];

        $messages = [
            'file.max' => ' zip file may not be greater than 20 MB',
        ];

        $request->validate($rules, $messages);
        $input = $request->all();

        $input['reply'] = Purifier::clean($request->reply, 'youtube');
        $input['type'] = 1;
        $input['user_id'] = Auth::guard('web')->user()->id;
        $input['support_ticket_id'] = $id;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            @mkdir(public_path('assets/admin/img/support-ticket/'), 0775, true);
            $file->move(public_path('assets/admin/img/support-ticket/'), $filename);
            $input['file'] = $filename;
        }

        $data = new Conversation();
        $data->create($input);

        SupportTicket::where('id', $id)->update([
            'last_message' => Carbon::now()
        ]);

        Session::flash('success', 'Message Sent Successfully');
        return back();
    }
}
