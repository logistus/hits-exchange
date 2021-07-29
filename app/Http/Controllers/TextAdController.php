<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TextAd;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TextAdController extends Controller
{
  public function index()
  {
    $texts = Auth::user()->texts;
    $page = "Text Ads";
    return view('user/texts', compact('texts', 'page'));
  }

  public function show($id)
  {
    $text = TextAd::findOrFail($id);
    return $text;
  }

  public function store(Request $request)
  {
    $isBanned = BannedUrlController::check_banned($request->target_url);
    if ($isBanned) {
      return back()->with('status', ['warning', $isBanned]);
    } else {
      $text = TextAd::create([
        'user_id' => Auth::user()->id,
        'body' => $request->text_body,
        'target_url' => str_replace("http://", "https://", $request->target_url),
        'text_color' => $request->user()->type->name == "Free" ? $request->user()->type->default_text_ad_color : $request->text_color,
        'bg_color' => $request->user()->type->name == "Free" ? $request->user()->type->default_text_ad_bg_color : $request->bg_color,
        'text_bold' => ($request->user()->type->name != "Free" && $request->edit_text_bold) ? $request->edit_text_bold : 0,
      ]);
      if ($request->imps && $request->imps > 0) {
        if ($request->user()->text_imps < $request->imps) {
          return back()->with('status', 'You don\'t have enough text ad impressions.');
        } else {
          $text->assigned = $request->imps;
          $text->save();
          $request->user()->decrement('text_imps', $request->imps);
        }
      }
      return back();
    }
  }

  public function change_status($id)
  {
    $text = TextAd::findOrFail($id);
    $response = Gate::inspect('update', $text);
    if ($response->allowed()) {
      if ($text->status != 'Pending' && $text->status != 'Suspended') {
        if ($text->status == 'Active') {
          $text->status = 'Paused';
        } else {
          $text->status = 'Active';
        }
        $text->save();
        return back();
      } else {
        return back()->with('status', 'You can\'t activate Pending or Suspended text ads.');
      }
    } else {
      return back()->with('status', $response->message());
    }
  }

  public function update(Request $request)
  {
    switch ($request->input('action')) {
      case 'assign':
        $texts = $request->assign_texts;
        $total_assign = 0;
        // Calculate how many banner imps user wants to assign
        foreach ($texts as $imps) {
          $total_assign += $imps;
        }

        // if total assign value is greater than user's credits, stop
        if ($request->user()->text_imps < $total_assign) {
          return back()->with('status', 'You don\'t have enough text ad impressions.');
        } else {
          // otherwise, continue to assign
          foreach ($texts as $id => $imp) {
            $text = TextAd::findOrFail($id);
            $response = Gate::inspect('update', $text);
            if ($response->allowed()) {
              if ($imp) {
                $text->increment('assigned', $imp);
                $request->user()->decrement('text_imps', $imp);
              }
            } else {
              return back()->with('status', $response->message());
            }
          }
          return back();
        }
        break;

      case 'delete_selected':
        $text = $request->selected_texts;
        foreach ($text as $id) {
          $this->destroy($request, $id);
        }
        return back();
        break;

      case 'pause_selected':
        $texts = $request->selected_texts;
        foreach ($texts as $id) {
          $text = TextAd::findOrFail($id);
          $response = Gate::inspect('update', $text);
          if ($response->allowed()) {
            if ($text->status != 'Pending' && $text->status != 'Suspended') {
              $text->status = 'Paused';
              $text->save();
            } else {
              return back()->with('status', 'You can\'t pause Pending or Suspended text ads.');
            }
          } else {
            return back()->with('status', $response->message());
          }
        }
        return back();
        break;

      case 'activate_selected':
        $texts = $request->selected_texts;
        foreach ($texts as $id) {
          $text = TextAd::findOrFail($id);
          $response = Gate::inspect('update', $text);
          if ($response->allowed()) {
            if ($text->status != 'Pending' && $text->status != 'Suspended') {
              $text->status = 'Active';
              $text->save();
            } else {
              return back()->with('status', 'You can\'t activate Pending or Suspended text ads.');
            }
          } else {
            return back()->with('status', $response->message());
          }
        }
        return back();
        break;

      case 'distribute_imps':
        // find users active websites
        $active_texts = $request->user()->active_texts;
        $imps = $request->user()->text_imps;
        $imps_to_distribute = $request->imps_to_distribute;

        if (count($active_texts) > 0)
          $imps_per_banner = round($imps_to_distribute / count($active_texts));
        else
          return back()->with('status', 'You don\'t have any active text ads.');


        if ($imps < $imps_to_distribute) {
          return back()->with('status', 'You don\'t have enough text ad impressions.');
        } else {
          // otherwise, continue to assign
          foreach ($active_texts as $text) {
            $text = TextAd::findOrFail($text->id);
            $response = Gate::inspect('update', $text);
            if ($response->allowed()) {
              if ($imps_per_banner) {
                $text->increment('assigned', $imps_per_banner);
                $request->user()->decrement('text_imps', $imps_per_banner);
              }
            } else {
              return back()->with('status', $response->message());
            }
          }
          return back();
        }

        // assign all credits evenly
        break;

      default:
        return back()->with('status', 'Invalid post request.');
    }
  }

  public function update_selected(Request $request, $id)
  {
    //return $request->all();
    $text = TextAd::findOrFail($id);
    $response = Gate::inspect('update', $text);
    $isBanned = BannedUrlController::check_banned($request->edit_target_url);
    if ($isBanned) {
      return back()->with('status', ['warning', $isBanned]);
    }
    if ($response->allowed()) {
      $text->target_url = str_replace("http://", "https://", $request->edit_target_url);
      $text->body = $request->edit_text_body;
      $text->text_color = $request->user()->type->name == "Free" ? $request->user()->type->default_text_ad_color : $request->edit_text_color;
      $text->bg_color = $request->user()->type->name == "Free" ? $request->user()->type->default_text_ad_bg_color : $request->edit_bg_color;
      $text->text_bold = ($request->user()->type->name != "Free" && $request->edit_text_bold) ? $request->edit_text_bold : 0;
      if ($text->isDirty('body') || $text->isDirty('target_url')) {
        $text->status = 'Pending';
      }
      $text->save();
      return back();
    } else {
      return back()->with('status', $response->message());
    }
  }

  public function destroy(Request $request, $id)
  {
    $text = TextAd::findOrFail($id);
    $response = Gate::inspect('delete', $text);
    if ($response->allowed()) {
      $imps = $text->assigned;
      $request->user()->increment('text_imps', $imps);
      $text->delete();
      return back();
    } else {
      return back()->with('status', $response->message());
    }
  }

  public function text_click($id)
  {
    $selected_text = TextAd::select('target_url')->where('id', $id);
    $selected_text->increment('clicks');
    return redirect()->away($selected_text->get()->first()->target_url);
  }

  public function text_reset($id)
  {
    $text = TextAd::findOrFail($id);
    $response = Gate::inspect('update', $text);
    if ($response->allowed()) {
      $text->views = 0;
      $text->clicks = 0;
      $text->save();
      return back();
    } else {
      return back()->with('status', $response->message());
    }
  }
}
