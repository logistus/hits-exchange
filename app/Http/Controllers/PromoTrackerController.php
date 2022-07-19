<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoTrackerController extends Controller
{
  public function index(Request $request)
  {
    $page = "Promo Trackers";
    $sort_by = $request->query('sort_by') ? $request->query('sort_by') : 'total_hits';
    $sort = $request->query("sort") ? $request->query("sort") : "desc";
    if ($sort_by == "" || $sort_by == "total_hits" || $sort_by == "tracker_name" || $sort_by == "referrals") {
      if ($sort_by == "referrals") {
        $promo_trackers = $request->user()->referrals()
          ->join('promo_trackers', 'users.tracker', '=', 'promo_trackers.tracker_name')
          ->select('users.tracker as tracker_name', DB::raw('COUNT(users.tracker) as referrals'), 'promo_trackers.total_hits')
          ->groupBy('users.tracker')
          ->orderByRaw("COUNT(users.tracker) $sort")
          ->paginate(15)
          ->withQueryString();
      } else {
        $promo_trackers = $request->user()->promo_trackers()
          ->orderBy($sort_by, $sort)
          ->paginate(15)
          ->withQueryString();
      }
      return view("user/promo_trackers", compact('page', 'promo_trackers'));
    } else {
      $promo_trackers = null;
      return back()->with("status", ["warning", "Invalid sorting type."]);
    }
  }
}
