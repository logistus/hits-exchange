<?php

namespace App\Http\Controllers;

use App\Models\SurfCode;
use App\Models\SurfCodeClaim;
use Illuminate\Http\Request;

class SurfCodeController extends Controller
{
  public function index(Request $request)
  {
    $active_surf_codes = $request->user()->active_surf_codes;
    $completed_surf_codes = $request->user()->completed_surf_codes;
    $page = "Surf Codes";
    return view("rewards/surf_codes", compact('active_surf_codes', 'completed_surf_codes', 'page'));
  }

  public function store(Request $request)
  {
    $code = $request->surf_code;
    $today = date("Y-m-d");
    $code_info = SurfCode::where("code", $code)
      ->where("confirmed", 1)
      ->where("valid_from", "<=", $today)
      ->where("valid_to", ">", $today)->get()->first();

    if ($code_info) {
      $claimed = SurfCodeClaim::where("id", $code_info->id)->where("user_id", $request->user()->id)->get()->first();
      if ($claimed) {
        return back()->with("status", ["warning", "You already claimed or completed this code."]);
      } else {
        SurfCodeClaim::create([
          'user_id' => $request->user()->id,
          'code_id' => $code_info->id
        ]);
        $prizes = $code_info->prizes;
        $prizes_text = "";
        $i = 0;
        foreach ($prizes as $prize) {
          $prizes_text .= $prize->prize_amount . " " . $prize->prize_type;
          if (++$i != count($prizes)) {
            $prizes_text .= " and ";
          }
        }
        return back()->with("status", ["success", "Code accpeted. Surf $code_info->surf_amount pages and receive $prizes_text."]);
      }
    } else {
      return back()->with("status", ["warning", "Invalid or expired surf code."]);
    }
  }
}
