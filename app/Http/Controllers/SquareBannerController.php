<?php

namespace App\Http\Controllers;

use App\Models\SquareBanner;
use Illuminate\Http\Request;
use App\Classes\FastImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class SquareBannerController extends Controller
{
  public function index()
  {
    $page = "Square Banners";
    $square_banners = Auth::user()->square_banners;
    return view("user/square_banners", compact("square_banners", "page"));
  }

  public function show($id)
  {
    $square_banner = SquareBanner::findOrFail($id);
    return $square_banner;
  }

  public function store(Request $request)
  {
    $image = new FastImage($request->image_url);
    list($width, $height) = $image->getSize();
    if ($width == 125 && $height == 125) {
      $square_banner = SquareBanner::create([
        "user_id" => Auth::user()->id,
        "image_url" => $request->image_url,
        "target_url" => $request->target_url,
      ]);
      if ($request->imps && $request->imps > 0) {
        if ($request->user()->square_banner_imps < $request->imps) {
          return back()->with("status", ["warning", "You don't have enough square banner impressions."]);
        } else {
          $square_banner->assigned = $request->imps;
          $square_banner->save();
          $request->user()->decrement("square_banner_imps", $request->imps);
        }
      }
      return back();
    } else {
      return back()->with("status", ["warning", "Square banner must have 125 pixel width and 125 pixel height."]);
    }
  }

  public function change_status($id)
  {
    $square_banner = SquareBanner::findOrFail($id);
    $response = Gate::inspect("update", $square_banner);
    if ($response->allowed()) {
      if ($square_banner->status != "Pending" && $square_banner->status != "Suspended") {
        if ($square_banner->status == "Active") {
          $square_banner->status = "Paused";
        } else {
          $square_banner->status = "Active";
        }
        $square_banner->save();
        return back();
      } else {
        return back()->with("status", ["warning", "You can't activate Pending or Suspended square banners."]);
      }
    } else {
      return back()->with("status", $response->message());
    }
  }

  public function update(Request $request)
  {
    switch ($request->input("action")) {
      case "assign":
        $square_banners = $request->assign_square_banners;
        $total_assign = 0;
        // Calculate how many SquareBanner imps user wants to assign
        foreach ($square_banners as $imps) {
          $total_assign += $imps;
        }

        // if total assign value is greater than user"s credits, stop
        if ($request->user()->square_banner_imps < $total_assign) {
          return back()->with("status", ["warning", "You don't have enough square banner impressions."]);
        } else {
          // otherwise, continue to assign
          foreach ($square_banners as $id => $imp) {
            $square_banner = SquareBanner::findOrFail($id);
            $response = Gate::inspect("update", $square_banner);
            if ($response->allowed()) {
              if ($imp) {
                $square_banner->increment("assigned", $imp);
                $request->user()->decrement("square_banner_imps", $imp);
              }
            } else {
              return back()->with("status", ["warning", $response->message()]);
            }
          }
          return back();
        }
        break;

      case "delete_selected":
        $square_banners = $request->selected_square_banners;
        foreach ($square_banners as $id) {
          $this->destroy($request, $id);
        }
        return back();
        break;

      case "pause_selected":
        $square_banners = $request->selected_square_banners;
        foreach ($square_banners as $id) {
          $square_banner = SquareBanner::findOrFail($id);
          $response = Gate::inspect("update", $square_banner);
          if ($response->allowed()) {
            if ($square_banner->status != "Pending" && $square_banner->status != "Suspended") {
              $square_banner->status = "Paused";
              $square_banner->save();
            } else {
              return back()->with("status", ["warning", "You can't pause Pending or Suspended Square Banners."]);
            }
          } else {
            return back()->with("status", $response->message());
          }
        }
        return back();
        break;

      case "activate_selected":
        $square_banners = $request->selected_square_banners;
        foreach ($square_banners as $id) {
          $square_banner = SquareBanner::findOrFail($id);
          $response = Gate::inspect("update", $square_banner);
          if ($response->allowed()) {
            if ($square_banner->status != "Pending" && $square_banner->status != "Suspended") {
              $square_banner->status = "Active";
              $square_banner->save();
            } else {
              return back()->with("status", ["warning", "You can't activate Pending or Suspended Square Banners."]);
            }
          } else {
            return back()->with("status", $response->message());
          }
        }
        return back();
        break;

      case "distribute_imps":
        // find users active websites
        $active_square_banners = $request->user()->active_square_banners;
        $imps = $request->user()->square_banner_imps;
        $imps_to_distribute = $request->imps_to_distribute;

        if (count($active_square_banners) > 0)
          $imps_per_square_banner = round($imps_to_distribute / count($active_square_banners));
        else
          return back()->with("status", "You don\"t have any active square banners.");


        if ($imps < $imps_to_distribute) {
          return back()->with("status", "You don\"t have enough square banner impressions.");
        } else {
          // otherwise, continue to assign
          foreach ($active_square_banners as $square_banner) {
            $square_banner = SquareBanner::findOrFail($square_banner->id);
            $response = Gate::inspect("update", $square_banner);
            if ($response->allowed()) {
              if ($imps_per_square_banner) {
                $square_banner->increment("assigned", $imps_per_square_banner);
                $request->user()->decrement("square_banner_imps", $imps_per_square_banner);
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
    $square_banner = SquareBanner::findOrFail($id);
    $response = Gate::inspect("update", $square_banner);
    if ($response->allowed()) {
      $image = new FastImage($request->edit_image_url);
      list($width, $height) = $image->getSize();
      if ($width == 125 && $height == 125) {
        $square_banner->target_url = $request->edit_target_url;
        $square_banner->image_url = $request->edit_image_url;
        if ($square_banner->isDirty("image_url") || $square_banner->isDirty("target_url")) {
          $square_banner->status = "Pending";
        }
        $square_banner->save();
        return back();
      } else {
        return back()->with("status", ["warning", "Square Banner must have 125 pixel width and 125 pixel height."]);
      }
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }

  public function destroy(Request $request, $id)
  {
    $square_banner = SquareBanner::findOrFail($id);
    $response = Gate::inspect("delete", $square_banner);
    if ($response->allowed()) {
      $imps = $square_banner->assigned;
      $request->user()->increment("square_banner_imps", $imps);
      $square_banner->delete();
      return back();
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }

  public function square_banner_click($id)
  {
    $selected_square_banner = SquareBanner::select("target_url")->where("id", $id);
    $selected_square_banner->increment("clicks");
    return redirect()->away($selected_square_banner->get()->first()->target_url);
  }

  public function square_banner_reset($id)
  {
    $square_banner = SquareBanner::findOrFail($id);
    $response = Gate::inspect("update", $square_banner);
    if ($response->allowed()) {
      $square_banner->views = 0;
      $square_banner->clicks = 0;
      $square_banner->save();
      return back();
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }
}
