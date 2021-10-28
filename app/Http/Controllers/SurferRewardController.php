<?php

namespace App\Http\Controllers;

use App\Models\PurchaseBalance;
use App\Models\SurferReward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SurferRewardController extends Controller
{
  public function index()
  {
    $surfer_rewards = SurferReward::all();
    $surf_page = SurferReward::select("page")->where("page", "<=", Auth::user()->surfed_today)->max("page");
    $prizes = SurferReward::select("credit_prize", "banner_prize", "square_banner_prize", "text_ad_prize", "purchase_balance")->where("page", $surf_page)->get();
    $page = "Surfer Rewards";
    return view("rewards/surfer_rewards", compact("surfer_rewards", "prizes", "surf_page", "page"));
  }

  public function store(Request $request)
  {
    $page = SurferReward::select("page")->where("page", "<=", Auth::user()->surfed_today)->max("page");
    $prize_amounts = SurferReward::select("credit_prize", "banner_prize", "square_banner_prize", "text_ad_prize", "purchase_balance")
      ->where("page", $page)->get()->first();
    switch ($request->reward) {
      case "Credits":
        $request->user()->increment("credits", $prize_amounts->credit_prize);
        $request->user()->surfer_reward_claimed = 1;
        $request->user()->save();
        return back()->with("status", ["success", number_format($prize_amounts->credit_prize) . " credits have been added to your account."]);
        break;
      case "Banner Impressions":
        $request->user()->increment("banner_imps", $prize_amounts->banner_prize);
        $request->user()->surfer_reward_claimed = 1;
        $request->user()->save();
        return back()->with("status", ["success", number_format($prize_amounts->banner_prize) . " banner impressions have been added to your account."]);
        break;
      case "Square Banner Impressions":
        $request->user()->increment("square_banner_imps", $prize_amounts->square_banner_prize);
        $request->user()->surfer_reward_claimed = 1;
        $request->user()->save();
        return back()->with("status", ["success", number_format($prize_amounts->square_banner_prize) . " square banner impressions have been added to your account."]);
        break;
      case "Text Ad Impressions":
        $request->user()->increment("text_imps", $prize_amounts->text_ad_prize);
        $request->user()->surfer_reward_claimed = 1;
        $request->user()->save();
        return back()->with("status", ["success", number_format($prize_amounts->text_ad_prize) . " text ad impressions have been added to your account."]);
        break;
      case "Purchase Balance":
        PurchaseBalance::create([
          "user_id" => $request->user()->id,
          "type" => "Surf Prize",
          "amount" => $prize_amounts->purchase_balance,
          "status" => "Completed"
        ]);
        $request->user()->surfer_reward_claimed = 1;
        $request->user()->save();
        return back()->with("status", ["success", "$$prize_amounts->purchase_balance purchase balance have been added to your account."]);
        break;
      default:
        return back()->with("status", ["warning", "Please select your reward."]);
    }
  }
}
