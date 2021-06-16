<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Banner;
use App\Models\TextAd;
use App\Models\Website;
use App\Models\SurfCode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\SurfCodeClaim;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class SurfController extends Controller
{
  public function view(Request $request)
  {
    $auto_assign = $this->check_auto_assign($request->user());
    if ($auto_assign < $request->user()->type->min_auto_assign) {
      return redirect('websites/auto_assign');
    }
    User::where('id', Auth::user()->id)->update(['start_time' => time()]);
    session(['surfed_session' => 0]);
    session(['selected_website_url' => url('start_page')]);
    session(['selected_website_owner' => 1]);
    $this->selectRandomBanner();
    $this->selectRandomTextAd();
    $request->session()->regenerateToken();
    return view('surf');
  }

  public function surf_code_claimed($id)
  {
    $code = SurfCode::findOrFail($id);
    $prizes = $code->prizes;
    $prizes_text = "";
    $i = 0;
    foreach ($prizes as $prize) {
      $prizes_text .= $prize->prize_amount . " " . $prize->prize_type;
      if (++$i != count($prizes)) {
        $prizes_text .= " and ";
      }
    }
    $code = $code->code;
    return view('surf_code/surf_code_claimed', compact('prizes_text', 'code'));
  }

  public function checkSurfCode()
  {
    $active_surf_codes = Auth::user()->active_surf_codes;
    if (count($active_surf_codes)) {
      foreach ($active_surf_codes as $active_surf_code) {
        if (Auth::user()->surfed_today >= $active_surf_code->code_info->surf_amount) {
          $claim = SurfCodeClaim::where("code_id", $active_surf_code->code_info->id)->get()->first();
          $claim->completed = 1;
          $claim->save();
          // give prizes
          $prizes = $active_surf_code->code_info->prizes;
          foreach ($prizes as $prize) {
            switch ($prize->prize_type) {
              case 'Credits':
                User::where('id', Auth::user()->id)->increment('credits', $prize->prize_amount);
                break;
              case 'Banner Impressions':
                User::where('id', Auth::user()->id)->increment('banner_imps', $prize->prize_amount);
                break;
              case 'Text Ad Impressions':
                User::where('id', Auth::user()->id)->increment('text_imps', $prize->prize_amount);
                break;
            }
          }
          $url = url('surf_code_claimed', $active_surf_code->code_info->id);
          return $url;
        } else {
          return $this->selectRandomWebsite();
        }
      }
    } else {
      return $this->selectRandomWebsite();
    }
  }

  public function selectRandomWebsite()
  {
    $website = Website::inRandomOrder()->select('id', 'url', 'user_id')
      ->where('user_id', '!=', Auth::user()->id)
      ->where('assigned', '>', 0)
      ->where('status', 'Active')
      ->where('max_daily_views', 0)
      ->orwhere(function ($query) {
        $query->where('views_today', '<=', 'max_daily_views')
          ->where('max_daily_views', '>', 0);
      })->limit(1)->get()->first();
    // save website ID to session
    session(['selected_website_owner' => $website->user_id]);
    session(['selected_website_url' => $website->url]);
    session(['selected_website_id' => $website->id]);
    return $website;
  }

  public function selectRandomBanner()
  {
    $banner = Banner::inRandomOrder()->select('image_url', 'id')
      ->where('user_id', '!=', Auth::user()->id)
      ->where('assigned', '>', 0)
      ->where('status', 'Active')
      ->limit(1)->get()->first();
    // save website ID to session
    session(['selected_banner_id' => $banner->id]);
    session(['selected_banner_image' => $banner->image_url]);
    Banner::where('id', $banner->id)->decrement('assigned');
    Banner::where('id', $banner->id)->increment('views');
    return $banner;
  }

  public function selectRandomTextAd()
  {
    $text = TextAd::inRandomOrder()->select('id', 'body', 'text_color', 'bg_color', 'text_bold')
      ->where('user_id', '!=', Auth::user()->id)
      ->where('assigned', '>', 0)
      ->where('status', 'Active')
      ->limit(1)->get()->first();
    session(['selected_text_id' => $text->id]);
    session(['selected_text_body' => $text->body]);
    session(['selected_text_color' => $text->text_color]);
    session(['selected_bg_color' => $text->bg_color]);
    session(['selected_text_bold' => $text->text_bold]);
    TextAd::where('id', $text->id)->decrement('assigned');
    TextAd::where('id', $text->id)->increment('views');
    return $text;
  }

  public function click_icons()
  {
    $selected_icons = range(1, 17);
    shuffle($selected_icons);
    $icons = array_slice($selected_icons, 0, 4);
    $duplicate_image = $icons[array_rand($icons)];
    array_push($icons, $duplicate_image);
    shuffle($icons);

    session(['icons' => $icons]);

    $icon_ids = array();
    for ($i = 0; $i <= 4; $i++) {
      array_push($icon_ids, Str::random(20));
    }
    session(['icon_ids' => $icon_ids]);

    $unique_icons = array_unique($icons);
    $duplicates = array_diff_assoc($icons, $unique_icons);
    $duplicate_keys = array_keys(array_intersect($icons, $duplicates));
    session(['correct_icons' =>  $duplicate_keys]);
  }

  public function surf_icons()
  {
    $image_1 = imagecreatefrompng('icons/' . session('icons')[0] . '.png');
    $color_image_1 = imagecolorallocatealpha($image_1, 0, 0, 0, 127);
    //$textcolor = imagecolorallocate($image_1, 0, 0, 255);
    //imagestring($image_1, 5, 10, 10, $icons[0], $textcolor);
    imagefill($image_1, 0, 0, $color_image_1);

    $image_2 = imagecreatefrompng('icons/' . session('icons')[1] . '.png');
    $color_image_2 = imagecolorallocatealpha($image_2, 0, 0, 0, 127);
    imagefill($image_2, 0, 0, $color_image_2);

    $image_3 = imagecreatefrompng('icons/' . session('icons')[2] . '.png');
    $color_image_3 = imagecolorallocatealpha($image_3, 0, 0, 0, 127);
    imagefill($image_3, 0, 0, $color_image_3);

    $image_4 = imagecreatefrompng('icons/' . session('icons')[3] . '.png');
    $color_image_4 = imagecolorallocatealpha($image_4, 0, 0, 0, 127);
    imagefill($image_4, 0, 0, $color_image_4);

    $image_5 = imagecreatefrompng('icons/' . session('icons')[4] . '.png');
    $color_image_5 = imagecolorallocatealpha($image_5, 0, 0, 0, 127);
    imagefill($image_5, 0, 0, $color_image_5);

    $surfIcons = imagecreatetruecolor(320, 48);

    imagecopymerge($surfIcons, $image_1, 0, 0, 0, 0, 48, 48, 100);
    imagecopymerge($surfIcons, $image_2, (48 + 20), 0, 0, 0, 48, 48, 100);
    imagecopymerge($surfIcons, $image_3, (48 + 20) * 2, 0, 0, 0, 48, 48, 100);
    imagecopymerge($surfIcons, $image_4, (48 + 20) * 3, 0, 0, 0, 48, 48, 100);
    imagecopymerge($surfIcons, $image_5, (48 + 20) * 4, 0, 0, 0, 48, 48, 100);

    $color_images = imagecolorallocatealpha($surfIcons, 0, 0, 0, 127);
    imagefill($surfIcons, 0, 0, $color_images);
    imagesavealpha($surfIcons, true);
    imagealphablending($surfIcons, false);
    ob_start();
    imagepng($surfIcons);
    $icons = ob_get_contents();
    ob_clean();
    return Image::make($icons)->response('png')->header("Content-Type", "image/png");
  }

  public function view_surf_icons()
  {
    $this->click_icons();
    return view('surf_icons');
  }

  public function validate_click($id)
  {
    $surf_ratio = Auth::user()->type->surf_ratio;
    if (time() - Auth::user()->start_time < Auth::user()->type->surf_timer) {
      return response()->json([
        'status' => 'ec'
      ]);
    } else {
      User::where('id', Auth::user()->id)->update(['last_click' => time()]);
      User::where('id', Auth::user()->id)->increment('surfed_today');

      // increase website's views and views today columns, aaaanddd decrease credit
      Website::where('id', session('selected_website_id'))->increment('views');
      Website::where('id', session('selected_website_id'))->increment('views_today');
      Website::where('id', session('selected_website_id'))->decrement('assigned');

      $website = $this->checkSurfCode();
      $banner = $this->selectRandomBanner();
      $text = $this->selectRandomTextAd();
      $url = is_object($website) ? $website->url : $website;
      $website_owner_gravatar = is_object($website) ?  User::generate_gravatar($website->user_id) : null;
      $website_owner_username = is_object($website) ? User::where('id', $website->user_id)->value('username') : null;

      if ($id == session('icon_ids')[session('correct_icons')[0]] || $id == session('icon_ids')[session('correct_icons')[1]]) {
        User::where('id', Auth::user()->id)->increment('correct_clicks');
        $user_websites = Auth::user()->websites;
        $user_total_auto_assign = Auth::user()->websites->sum('auto_assign');
        $auto_assigned = 0;
        // check auto assign
        if ($user_total_auto_assign > 0) {
          foreach ($user_websites as $website) {
            if ($website->auto_assign > 0) {
              $credits_to_assign = ($surf_ratio * $website->auto_assign) / 100;
              $auto_assigned += $credits_to_assign;
              Website::where('id', $website->id)->increment('assigned', $credits_to_assign);
            }
          }
          User::where('id', Auth::user()->id)->increment('credits', $surf_ratio - $auto_assigned);
        } else {
          // if it is 0, give all credits to user
          User::where('id', Auth::user()->id)->increment('credits', $surf_ratio);
        }

        // if user has upline, give upline credits based on user type
        if (Auth::user()->upline) {
          $reward_credit = ($surf_ratio * User::where('id', Auth::user()->upline)->get()->first()->type->credit_reward_ratio) / 100;
          User::where('id', Auth::user()->upline)->get()->first()->increment('credits', $reward_credit);
        }
        $status = '<span class="bg-success text-white px-4 py-2 fs-2">+' . Auth::user()->type->surf_ratio + 0 . ' Credit</span>'; // added + 0 to remove unnecessary zeros
      } else {
        User::where('id', Auth::user()->id)->increment('wrong_clicks');
        $status = '<span class="bg-danger text-white px-4 py-2 fs-2">Wrong Click!</span>';
      }
      return response()->json([
        'status' => $status,
        'url' => $url,
        'website_owner_gravatar' => $website_owner_gravatar,
        'website_owner_username' => $website_owner_username,
        'banner_id' => $banner->id,
        'banner_image' => $banner->image_url,
        'text_id' => $text->id,
        'text_body' => $text->body,
        'text_color' => $text->text_color,
        'text_bg_color' => $text->bg_color,
        'text_bold' => $text->text_bold,
        'surfed_today' => User::where("id", Auth::user()->id)->lockForUpdate()->value('surfed_today'),
        'credits' => User::where("id", Auth::user()->id)->lockForUpdate()->value('credits'),
      ]);
    }
  }

  public function check_auto_assign(User $user)
  {
    return $user->websites->sum('auto_assign');
  }

  public function start_page()
  {
    $start_page = 'start_page';
    return view($start_page);
  }
}
