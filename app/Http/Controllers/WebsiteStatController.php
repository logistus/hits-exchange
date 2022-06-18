<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\WebsiteStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class WebsiteStatController extends Controller
{
  public function show($id)
  {
    $website = Website::findOrFail($id);
    $response = Gate::inspect("view", $website);
    if ($response->allowed()) {
      $websiteStats = WebsiteStat::where('website_id', $id)->orderBy('view_date')->get();
      return $websiteStats;
    } else {
      return redirect('websites')->with("status", ["warning", $response->message()]);
    }
  }

  public function show_at_date($id, $date)
  {
    $website = Website::findOrFail($id);
    $response = Gate::inspect("view", $website);
    if ($response->allowed()) {
      $websiteStat = WebsiteStat::where('website_id', $id)->where('view_date', $date)->get()->first();
      if ($websiteStat) {
        return $websiteStat->total_views;
      } else {
        return 0;
      }
    } else {
      return redirect('websites')->with("status", ["warning", $response->message()]);
    }
  }

  public function show_interval_stats($id, $interval = 14)
  {
    $website = Website::findOrFail($id);
    $response = Gate::inspect("view", $website);
    if ($response->allowed()) {
      $stats = [];

      for ($i = 0; $i <= $interval; $i++) {
        $check_date = Carbon::today()->subDays($interval)->addDays($i);
        $visit_at_date = $this->show_at_date($id, $check_date->toDateString());
        $stats[$i]["date"] = $check_date->format("F j");
        $stats[$i]["visits"] = $visit_at_date;
      }
      return $stats;
    } else {
      return redirect('websites')->with("status", ["warning", $response->message()]);
    }
  }

  public function visits($id, $interval)
  {
    $website = Website::findOrFail($id);
    $response = Gate::inspect("view", $website);
    if ($response->allowed()) {
      if ($interval == "all") {
        return WebsiteStat::where("website_id", $id)->get()->sum("total_views");
      } else if ($interval == "today") {
        return WebsiteStat::where("website_id", $id)->where("view_date", Carbon::today()->toDateString())->get()->sum("total_views");
      } else if ($interval == "yesterday") {
        return WebsiteStat::where("website_id", $id)->where("view_date", Carbon::yesterday()->toDateString())->get()->sum("total_views");
      } else {
        $start_date = Carbon::today()->subDays($interval - 1)->toDateString();
        return WebsiteStat::where("website_id", $id)->where("view_date", ">=", $start_date)
          ->where("view_date", "<=", Carbon::today()->toDateString())->get()->sum("total_views");
      }
    } else {
      return redirect('websites')->with("status", ["warning", $response->message()]);
    }
  }
}
