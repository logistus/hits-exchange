<?php

namespace App\Http\Controllers;

use App\Models\BannedUrl;
use App\Models\Website;
use Hamcrest\Type\IsBoolean;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class WebsiteController extends Controller
{
  public function index()
  {
    $websites = Auth::user()->websites;
    $page = "Websites";
    return view("user/websites", compact("websites", "page"));
  }


  public function auto_assign_view()
  {
    $page = "Auto Assign";
    $websites = Auth::user()->websites;
    return view("user/auto_assign", compact("websites", "page"));
  }

  public function auto_assign(Request $request)
  {
    $request->validate([
      "aa_values" => "array",
      "aa_values.*" => "numeric"
    ]);

    $auto_assign_values = $request->aa_values;
    $total_auto_assign = 0;
    foreach ($auto_assign_values as $value) {
      $total_auto_assign += $value;
    }

    if ($total_auto_assign > 100) {
      return back()->with("status", ["warning", "Total auto assign value must be equal or below 100%"]);
    }

    foreach ($auto_assign_values as $id => $value) {
      $website = Website::findOrFail($id);
      $response = Gate::inspect("update", $website);
      if ($response->allowed()) {
        $website->auto_assign = $value;
        $website->save();
      } else {
        return back()->with("status", ["warning", $response->message()]);
      }
    }
    return back();
  }

  public function show($id)
  {
    $website = Website::findOrFail($id);
    $response = Gate::inspect("view", $website);
    if ($response->allowed()) {
      return $website;
    } else {
      return redirect('websites')->with("status", ["warning", $response->message()]);
    }
  }

  public function store(Request $request)
  {
    // check if url is banned
    $isBanned = BannedUrlController::check_banned($request->url);
    if ($isBanned) {
      return back()->with('status', ['warning', $isBanned]);
    } else {
      $website = Website::create([
        "user_id" => $request->user()->id,
        "url" => str_replace("http://", "https://", $request->url),
        "max_daily_views" => $request->max_daily_views,
      ]);
      if ($request->credits && $request->credits > 0) {
        if ($request->user()->credits < $request->credits) {
          return back()->with("status", ["warning", "You don't have enough credits."]);
        } else {
          $website->assigned = $request->credits;
          $website->save();
          $request->user()->decrement("credits", $request->credits);
        }
      }
      return redirect('websites/check_website/' . $website->id);
    }
  }

  public function change_status($id)
  {
    $website = Website::findOrFail($id);
    $response = Gate::inspect("update", $website);
    if ($response->allowed()) {
      if ($website->status != "Pending" && $website->status != "Suspended") {
        if ($website->status == "Active") {
          $website->status = "Paused";
        } else {
          $website->status = "Active";
        }
        $website->save();
        return back();
      } else {
        return back()->with("status", ["warning", "You can't activate Pending or Suspended websites."]);
      }
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }

  public function update(Request $request)
  {
    switch ($request->input("action")) {
      case "assign":
        $websites = $request->assign_websites;
        $total_assign = 0;
        // Calculate how many credits user wants to assign
        foreach ($websites as $credit) {
          $total_assign += $credit;
        }

        // if total assign value is greater than user"s credits, stop
        if ($request->user()->credits < $total_assign) {
          return back()->with("status", ["warning", "You don't have enough credits."]);
        } else {
          // otherwise, continue to assign
          foreach ($websites as $id => $credit) {
            $website = Website::findOrFail($id);
            $response = Gate::inspect("update", $website);
            if ($response->allowed()) {
              if ($credit) {
                $website->increment("assigned", $credit);
                $request->user()->decrement("credits", $credit);
              }
            } else {
              return back()->with("status", ["warning", $response->message()]);
            }
          }
          return back();
        }
        break;

      case "delete_selected":
        $websites = $request->selected_websites;
        foreach ($websites as $id) {
          $this->destroy($request, $id);
        }
        return back();
        break;

      case "pause_selected":
        $websites = $request->selected_websites;
        foreach ($websites as $id) {
          $website = Website::findOrFail($id);
          $response = Gate::inspect("update", $website);
          if ($response->allowed()) {
            if ($website->status != "Pending" && $website->status != "Suspended") {
              $website->status = "Paused";
              $website->save();
            } else {
              return back()->with("status", ["warning", "You can't pause Pending or Suspended websites."]);
            }
          } else {
            return back()->with("status", ["warning", $response->message()]);
          }
        }
        return back();
        break;

      case "activate_selected":
        $websites = $request->selected_websites;
        foreach ($websites as $id) {
          $website = Website::findOrFail($id);
          $response = Gate::inspect("update", $website);
          if ($response->allowed()) {
            if ($website->status != "Pending" && $website->status != "Suspended") {
              $website->status = "Active";
              $website->save();
            } else {
              return back()->with("status", ["warning", "You can't activate Pending or Suspended websites."]);
            }
          } else {
            return back()->with("status", ["warning", $response->message()]);
          }
        }
        return back();
        break;

      case "distribute_credits":
        // find users active websites
        $active_websites = $request->user()->active_websites;
        $credits = $request->user()->credits;
        $credits_to_distribute = $request->credits_to_distribute;
        if (count($active_websites) > 0)
          $credits_per_site = round($credits_to_distribute / count($active_websites));
        else
          return back()->with("status", ["warning", "You don't have any active websites."]);


        if ($credits < $credits_to_distribute) {
          return back()->with("status", ["warning", "You don't have enough credits."]);
        } else {
          // otherwise, continue to assign
          foreach ($active_websites as $website) {
            $website = Website::findOrFail($website->id);
            $response = Gate::inspect("update", $website);
            if ($response->allowed()) {
              if ($credits_per_site) {
                $website->increment("assigned", $credits_per_site);
                $request->user()->decrement("credits", $credits_per_site);
              }
            } else {
              return back()->with("status", ["warning", $response->message()]);
            }
          }
          return back();
        }

        // assign all credits evenly
        break;

      default:
        return back()->with("status", ["warning", "Invalid post request."]);
    }
  }

  public function update_selected(Request $request, $id)
  {
    // return $request->all();
    $website = Website::findOrFail($id);
    $response = Gate::inspect("update", $website);
    $isBanned = BannedUrlController::check_banned($request->edit_url);
    if ($isBanned) {
      return back()->with('status', ['warning', $isBanned]);
    }
    if ($response->allowed()) {
      $website->url = str_replace("http://", "https://", $request->edit_url);
      $website->max_daily_views = $request->edit_max_daily_views;
      if ($website->isDirty("url")) {
        $website->status = "Pending";
        $website->save();
        return redirect('websites/check_website/' . $website->id);
      } else {
        $website->save();
        return back();
      }
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }

  public function destroy(Request $request, $id)
  {
    $website = Website::findOrFail($id);
    $response = Gate::inspect("delete", $website);
    if ($response->allowed()) {
      $credits = $website->assigned;
      $request->user()->increment("credits", $credits);
      $website->delete();
      return back();
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }

  public function website_reset($id)
  {
    $website = Website::findOrFail($id);
    $response = Gate::inspect("update", $website);
    if ($response->allowed()) {
      $website->views = 0;
      $website->save();
      return back();
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }

  public function website_check($id)
  {
    $website = Website::findOrFail($id);
    return view('check_website', compact('website'));
  }

  public function website_approve($id)
  {
    $website = Website::findOrFail($id);
    $response = Gate::inspect("update", $website);
    if ($response->allowed()) {
      $website->status = 'Active';
      $website->save();
      return redirect('websites');
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }
}
