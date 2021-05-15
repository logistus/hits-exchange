<?php

namespace App\Http\Controllers;

use App\Models\SurferReward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurferRewardController extends Controller
{
  public function index()
  {
    $surfer_rewards = SurferReward::all("minimum_page", "prize_amount", "prize_type")->groupBy("minimum_page");
    $surf_page = SurferReward::select("minimum_page")->where("minimum_page", "<=", Auth::user()->surfed_today)->max("minimum_page");
    $prizes = SurferReward::select("prize_type", "prize_amount")->where("minimum_page", $surf_page)->get();
    $page = "Surfer Rewards";
    return view("rewards/surfer_rewards", compact("surfer_rewards", "prizes", "surf_page", "page"));
  }

  public function store(Request $request)
  {
    $page = SurferReward::select("minimum_page")->where("minimum_page", "<=", Auth::user()->surfed_today)->max("minimum_page");
    $prize_amount = SurferReward::select("prize_amount")
      ->where("minimum_page", $page)
      ->where("prize_type", $request->reward)->value("prize_amount");
    switch ($request->reward) {
      case "Credits":
        $request->user()->increment("credits", $prize_amount);
        $request->user()->surfer_reward_claimed = 1;
        $request->user()->save();
        return back()->with("status", ["success", "$prize_amount credits have been added to your account."]);
        break;
      case "Banner Impressions":
        $request->user()->increment("banner_imps", $prize_amount);
        $request->user()->surfer_reward_claimed = 1;
        $request->user()->save();
        return back()->with("status", ["success", "$prize_amount banner impressions have been added to your account."]);
        break;
      case "Text Ad Impressions":
        $request->user()->increment("text_imps", $prize_amount);
        $request->user()->surfer_reward_claimed = 1;
        $request->user()->save();
        return back()->with("status", ["success", "$prize_amount text ad impressions have been added to your account."]);
        break;
      default:
        return back()->with("status", ["warning", "Please elect your reward."]);
    }
  }
}
