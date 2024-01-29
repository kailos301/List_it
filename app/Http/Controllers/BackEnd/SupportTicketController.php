<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\SupportTicket;
use App\Models\SupportTicketStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class SupportTicketController extends Controller
{
  //index
  public function index(Request $request)
  {
    $role_id = Auth::guard('admin')->user()->role_id;

    $status = $ticket_id = null;
    if ($request->filled('status')) {
      $status = $request['status'];
    }
    if ($request->filled('ticket_id')) {
      $ticket_id = $request['ticket_id'];
    }

    if (empty($role_id)) {
      $collection = SupportTicket::with('vendor')->when($status, function ($query, $status) {
        return $query->where('status',  $status);
      })
        ->when($ticket_id, function ($query, $ticket_id) {
          return $query->where('id', 'like', '%' . $ticket_id . '%');
        })
        ->orderByDesc('id')
        ->paginate(10);
    } else {
      $collection = SupportTicket::with('vendor')->when($status, function ($query, $status) {
        return $query->where('status',  $status);
      })
        ->when($ticket_id, function ($query, $ticket_id) {
          return $query->where('id', 'like', '%' . $ticket_id . '%');
        })
        ->where('admin_id', Auth::guard('admin')->user()->id)
        ->orderByDesc('id')
        ->paginate(10);
    }


    return view('backend.support_ticket.index', compact('collection'));
  }
  //message
  public function message($id)
  {
    $role_id = Auth::guard('admin')->user()->role_id;
    if (empty($role_id)) {
      $ticket = SupportTicket::where('id', $id)->firstOrFail();
    } else {
      $ticket = SupportTicket::where([['id', $id], ['admin_id', Auth::guard('admin')->user()->id]])->firstOrFail();
    }
    return view('backend.support_ticket.messages', compact('ticket'));
  }
  public function zip_file_upload(Request $request)
  {
    $file = $request->file('file');
    $allowedExts = array('zip');
    $rules = [
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

    $validator = Validator::make($request->all(), $rules, $messages);
    if ($validator->fails()) {
      return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
    }

    if ($request->hasFile('file')) {
      $file = $request->file('file');
      $filename = uniqid() . '.' . $file->getClientOriginalExtension();
      $file->move(public_path('assets/front/temp/'), $filename);
      $input['file'] = $filename;
    }

    return response()->json(['data' => 1]);
  }
  public function ticketreply(Request $request, $id)
  {
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
        'max:5000'
      ],
    ];

    $messages = [
      'file.max' => ' zip file may not be greater than 5 MB',
    ];

    $request->validate($rules, $messages);
    $input = $request->all();

    $reply = str_replace(url('/') . '/assets/front/img/', "{base_url}/assets/front/img/", $request->reply);
    $input['reply'] = Purifier::clean($reply, 'youtube');
    $input['user_id'] = Auth::guard('admin')->user()->id;
    $input['type'] = 2;

    $input['support_ticket_id'] = $id;
    if ($request->hasFile('file')) {
      $file = $request->file('file');
      $filename = uniqid() . '.' . $file->getClientOriginalExtension();
      $file->move(public_path('assets/admin/img/support-ticket/'), $filename);
      $input['file'] = $filename;
    }

    $data = new Conversation();
    $data->create($input);

    $files = glob('assets/front/temp/*');
    foreach ($files as $file) {
      @unlink($file);
    }

    SupportTicket::where('id', $id)->update([
      'last_message' => Carbon::now(),
      'status' => 2,
    ]);

    Session::flash('success', 'Message Sent Successfully');
    return back();
  }
  //ticket_closed
  public function ticket_closed($id)
  {
    SupportTicket::where('id', $id)->update([
      'status' => 3,
    ]);
    Session::flash('success', 'Support Ticket close successfully!');
    return back();
  }

  //setting
  public function setting()
  {
    $content = SupportTicketStatus::where('id', 1)->first();
    return view('backend.support_ticket.setting', compact('content'));
  }
  //update_setting
  public function update_setting(Request $request)
  {
    $status = SupportTicketStatus::where('id', 1)->first();
    $status->support_ticket_status = $request->support_ticket_status;
    $status->save();
    Session::flash('success', 'Support Ticket Status Updated successfully!');
    return back();
  }
  //assign_stuff.supoort.ticket
  public function assign_stuff(Request $request, $id)
  {
    $support_ticket = SupportTicket::where('id', $id)->first();
    $support_ticket->admin_id = $request->admin_id;
    $support_ticket->save();
    Session::flash('success', 'Add Stuff to this support ticket successfully..!');
    return back();
  }
  public function unassign_stuff($id)
  {
    SupportTicket::where('id', $id)->update([
      'admin_id' => null,
    ]);

    Session::flash('success', 'Unassign stuff successfully!');
    return back();
  }

  //delete
  public function delete($id)
  {
    //delete all support ticket
    $support_ticket = SupportTicket::where('id', $id)->first();
    if ($support_ticket) {
      //delete conversation 
      $messages = $support_ticket->messages()->get();
      foreach ($messages as $message) {
        @unlink(public_path('assets/admin/img/support-ticket/') . $message->file);
        $message->delete();
      }
      @unlink(public_path('assets/admin/img/support-ticket/') . $support_ticket->attachment);
      $support_ticket->delete();
    }
    Session::flash('success', 'Support Ticket Deleted Successfully..!');
    return back();
  }

  public function bulk_delete(Request $request)
  {
    $ids = $request->ids;
    foreach ($ids as $id) {
      $support_ticket = SupportTicket::where('id', $id)->first();
      if ($support_ticket) {
        //delete conversation 
        $messages = $support_ticket->messages()->get();
        foreach ($messages as $message) {
          @unlink(public_path('assets/admin/img/support-ticket/' . $message->file));
          $message->delete();
        }
        @unlink(public_path('assets/admin/img/support-ticket/') . $support_ticket->attachment);
        $support_ticket->delete();
      }
    }
    Session::flash('success', 'Support Tickets are Deleted Successfully..!');
    return response()->json(['status' => 'success'], 200);
  }
}
