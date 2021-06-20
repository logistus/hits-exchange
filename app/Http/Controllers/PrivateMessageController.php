<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PrivateMessage;
use App\Notifications\PrivateMessageReceived;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PrivateMessageController extends Controller
{
  public function inbox(Request $request)
  {
    $page = "Private Messages - Inbox";
    $private_messages = $request->user()->private_messages()->paginate(15);
    return view('private_messages/inbox', compact('page', 'private_messages'));
  }


  public function sent(Request $request)
  {
    $page = "Private Messages - Sent Messages";
    $private_messages_sent = $request->user()->private_messages_sent()->paginate(15);
    return view('private_messages/sent', compact('page', 'private_messages_sent'));
  }

  public function trash(Request $request)
  {
    $page = "Private Messages - Trash";
    $private_messages_trash = $request->user()->private_messages_trash()->paginate(15);
    return view('private_messages/trash', compact('page', 'private_messages_trash'));
  }

  public function show($id)
  {
    $page = "Private Messages - Read";
    $private_message = PrivateMessage::findOrFail($id);
    $response = Gate::inspect('view', $private_message);
    if ($response->allowed()) {
      if ($private_message->to_id == Auth::id()) {
        $private_message->read = 1;
        $private_message->save();
      }
      return view('private_messages/read', compact('page', 'private_message'));
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }

  public function reply($id)
  {
    $page = "Private Messages - Reply";
    $private_message = PrivateMessage::findOrFail($id);
    $response = Gate::inspect('update', $private_message);
    if ($response->allowed()) {
      return view('private_messages/reply', compact('page', 'private_message'));
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }

  public function create(Request $request)
  {
    $page = "Private Messages - Compose";
    return view('private_messages/create', compact('page'));
  }

  public function store(Request $request)
  {
    $request->validate([
      "pm_to" => "required|numeric",
      "pm_subject" => "required",
      "pm_message" => "required",
    ]);

    PrivateMessage::create([
      "from_id" => Auth::user()->id,
      "to_id" => $request->pm_to,
      "folder_receiver" => 'Inbox',
      "subject" => $request->pm_subject,
      "message" => $request->pm_message
    ]);

    // Send an email to receiver if available
    $receiver = User::where('id', $request->pm_to)->get()->first();
    if ($receiver->pm_notification) {
      $receiver->notify(new PrivateMessageReceived($receiver));
    }

    return back()->with("status", ["success", "Your private message has been sent."]);
  }

  public function delete_from_sender($id)
  {
    $private_message = PrivateMessage::findOrFail($id);
    $response = Gate::inspect('delete_from_sender', $private_message);
    if ($response->allowed()) {
      $private_message->deleted_from_sender = 1;
      $private_message->save();
      if ($private_message->deleted_from_receiver == 1 && $private_message->deleted_from_sender == 1) {
        $private_message->delete();
      }
      return redirect('private_messages/sent');
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }

  public function move_trash($id)
  {
    $private_message = PrivateMessage::findOrFail($id);
    $response = Gate::inspect('update', $private_message);
    if ($response->allowed()) {
      $private_message->folder_receiver = 'Trash';
      $private_message->save();
      return back();
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }

  public function destroy($id)
  {
    $private_message = PrivateMessage::findOrFail($id);
    $response = Gate::inspect('delete_from_receiver', $private_message);
    if ($response->allowed()) {
      $private_message->deleted_from_receiver = 1;
      $private_message->save();
      if ($private_message->deleted_from_receiver == 1 && $private_message->deleted_from_sender == 1) {
        $private_message->delete();
      }
      return back();
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }

  public function empty_trash(Request $request)
  {
    $private_messages_trash = $request->user()->private_messages_trash;
    foreach ($private_messages_trash as $pm) {
      $this->destroy($pm->id);
    }
    return redirect('private_messages/trash');
  }

  public function move_inbox($id)
  {
    $private_message = PrivateMessage::findOrFail($id);
    $response = Gate::inspect('update', $private_message);
    if ($response->allowed()) {
      $private_message->folder_receiver = 'Inbox';
      $private_message->save();
      return back();
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }

  public function report($id)
  {
    $private_message = PrivateMessage::findOrFail($id);
    $response = Gate::inspect('update', $private_message);
    if ($response->allowed()) {
      $private_message->reported = 1;
      $private_message->save();
      return back()->with("status", ["success", "Your report has been recorded. Thank you."]);
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }

  public function update(Request $request)
  {
    switch ($request->input("action")) {
      case "move_to_trash_selected":
        $private_messages = $request->selected_pms;
        foreach ($private_messages as $pm) {
          $this->move_trash($pm);
        }
        return back();
        break;

      case "report_selected":
        $private_messages = $request->selected_pms;
        foreach ($private_messages as $pm) {
          $this->report($pm);
        }
        return back();
        break;

      case "delete_from_sender_selected":
        $private_messages = $request->selected_pms;
        foreach ($private_messages as $pm) {
          $this->delete_from_sender($pm);
        }
        return back();
        break;

      case "delete_selected":
        $private_messages = $request->selected_pms;
        foreach ($private_messages as $pm) {
          $this->destroy($pm);
        }
        return back();
        break;

      case "move_to_inbox_selected":
        $private_messages = $request->selected_pms;
        foreach ($private_messages as $pm) {
          $this->move_inbox($pm);
        }
        return back();
        break;
    }
  }
}
