<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\BannedUrl;
use App\Classes\FastImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class BannerController extends Controller
{
  public function index()
  {
    $page = "Banners";
    $banners = Auth::user()->banners;
    return view("user/banners", compact("banners", "page"));
  }

  public function show($id)
  {
    $banner = Banner::findOrFail($id);
    return $banner;
  }

  public function check_banned($url)
  {
    $domain = str_replace("www.", "", parse_url($url, PHP_URL_HOST));
    $isBanned = BannedUrl::where('url', $domain)->get()->first();
    if ($isBanned) {
      $warning_message = 'This is URL banned!';
      if ($isBanned->reason)
        $warning_message .= " Reason: " . $isBanned->reason;
      return $warning_message;
    } else {
      return false;
    }
  }

  public function store(Request $request)
  {
    $image = new FastImage($request->image_url);
    list($width, $height) = $image->getSize();
    if ($width == 468 && $height == 60) {
      $isBannedTargetUrl = BannedUrlController::check_banned($request->target_url);
      $isBannedImageUrl = BannedUrlController::check_banned($request->image_url);
      if ($isBannedTargetUrl || $isBannedImageUrl) {
        return back()->with('status', ['warning', $isBannedTargetUrl ? $isBannedTargetUrl : $isBannedImageUrl]);
      } else {
        $banner = Banner::create([
          "user_id" => Auth::user()->id,
          "image_url" => str_replace("http://", "https://", $request->image_url),
          "target_url" => str_replace("http://", "https://", $request->target_url),
        ]);
        if ($request->imps && $request->imps > 0) {
          if ($request->user()->banner_imps < $request->imps) {
            return back()->with("status", ["warning", "You don't have enough banner impressions."]);
          } else {
            $banner->assigned = $request->imps;
            $banner->save();
            $request->user()->decrement("banner_imps", $request->imps);
          }
        }
        return back();
      }
    } else {
      return back()->with("status", ["warning", "Banner must have 468 pixel width and 60 pixel height."]);
    }
  }

  public function change_status($id)
  {
    $banner = Banner::findOrFail($id);
    $response = Gate::inspect("update", $banner);
    if ($response->allowed()) {
      if ($banner->status != "Pending" && $banner->status != "Suspended") {
        if ($banner->status == "Active") {
          $banner->status = "Paused";
        } else {
          $banner->status = "Active";
        }
        $banner->save();
        return back();
      } else {
        return back()->with("status", ["warning", "You can't activate Pending or Suspended banners."]);
      }
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }

  public function update(Request $request)
  {
    switch ($request->input("action")) {
      case "assign":
        $banners = $request->assign_banners;
        $total_assign = 0;
        // Calculate how many banner imps user wants to assign
        foreach ($banners as $imps) {
          $total_assign += $imps;
        }

        // if total assign value is greater than user"s credits, stop
        if ($request->user()->banner_imps < $total_assign) {
          return back()->with("status", ["warning", "You don't have enough banner impressions."]);
        } else {
          // otherwise, continue to assign
          foreach ($banners as $id => $imp) {
            $banner = Banner::findOrFail($id);
            $response = Gate::inspect("update", $banner);
            if ($response->allowed()) {
              if ($imp) {
                $banner->increment("assigned", $imp);
                $request->user()->decrement("banner_imps", $imp);
              }
            } else {
              return back()->with("status", ["warning", $response->message()]);
            }
          }
          return back();
        }
        break;

      case "delete_selected":
        $banners = $request->selected_banners;
        foreach ($banners as $id) {
          $this->destroy($request, $id);
        }
        return back();
        break;

      case "pause_selected":
        $banners = $request->selected_banners;
        foreach ($banners as $id) {
          $banner = Banner::findOrFail($id);
          $response = Gate::inspect("update", $banner);
          if ($response->allowed()) {
            if ($banner->status != "Pending" && $banner->status != "Suspended") {
              $banner->status = "Paused";
              $banner->save();
            } else {
              return back()->with("status", ["warning", "You can't pause Pending or Suspended banners."]);
            }
          } else {
            return back()->with("status", $response->message());
          }
        }
        return back();
        break;

      case "activate_selected":
        $banners = $request->selected_banners;
        foreach ($banners as $id) {
          $banner = Banner::findOrFail($id);
          $response = Gate::inspect("update", $banner);
          if ($response->allowed()) {
            if ($banner->status != "Pending" && $banner->status != "Suspended") {
              $banner->status = "Active";
              $banner->save();
            } else {
              return back()->with("status", ["warning", "You can't activate Pending or Suspended banners."]);
            }
          } else {
            return back()->with("status", $response->message());
          }
        }
        return back();
        break;

      case "distribute_imps":
        // find users active websites
        $active_banners = $request->user()->active_banners;
        $imps = $request->user()->banner_imps;
        $imps_to_distribute = $request->imps_to_distribute;

        if (count($active_banners) > 0)
          $imps_per_banner = round($imps_to_distribute / count($active_banners));
        else
          return back()->with("status", "You don\"t have any active banners.");


        if ($imps < $imps_to_distribute) {
          return back()->with("status", "You don\"t have enough banner impressions.");
        } else {
          // otherwise, continue to assign
          foreach ($active_banners as $banner) {
            $banner = Banner::findOrFail($banner->id);
            $response = Gate::inspect("update", $banner);
            if ($response->allowed()) {
              if ($imps_per_banner) {
                $banner->increment("assigned", $imps_per_banner);
                $request->user()->decrement("banner_imps", $imps_per_banner);
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
    $banner = Banner::findOrFail($id);
    $response = Gate::inspect("update", $banner);
    $isBannedTargetUrl = BannedUrlController::check_banned($request->edit_target_url);
    $isBannedImageUrl = BannedUrlController::check_banned($request->edit_image_url);
    if ($isBannedTargetUrl || $isBannedImageUrl) {
      return back()->with('status', ['warning', $isBannedTargetUrl ? $isBannedTargetUrl : $isBannedImageUrl]);
    }
    if ($response->allowed()) {
      $image = new FastImage($request->edit_image_url);
      list($width, $height) = $image->getSize();
      if ($width == 468 && $height == 60) {
        $banner->target_url = str_replace("http://", "https://", $request->edit_target_url);
        $banner->image_url = str_replace("http://", "https://", $request->edit_image_url);
        if ($banner->isDirty("image_url") || $banner->isDirty("target_url")) {
          $banner->status = "Pending";
        }
        $banner->save();
        return back();
      } else {
        return back()->with("status", ["warning", "Banner must have 468 pixel width and 60 pixel height."]);
      }
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }

  public function destroy(Request $request, $id)
  {
    $banner = Banner::findOrFail($id);
    $response = Gate::inspect("delete", $banner);
    if ($response->allowed()) {
      $imps = $banner->assigned;
      $request->user()->increment("banner_imps", $imps);
      $banner->delete();
      return back();
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }

  public function banner_click($id)
  {
    $selected_banner = Banner::select("target_url")->where("id", $id);
    $selected_banner->increment("clicks");
    return redirect()->away($selected_banner->get()->first()->target_url);
  }

  public function banner_reset($id)
  {
    $banner = Banner::findOrFail($id);
    $response = Gate::inspect("update", $banner);
    if ($response->allowed()) {
      $banner->views = 0;
      $banner->clicks = 0;
      $banner->save();
      return back();
    } else {
      return back()->with("status", ["warning", $response->message()]);
    }
  }
}
