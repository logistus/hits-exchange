<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\SurfCodePrize;
use App\Http\Controllers\Controller;

class SurfCodePrizeController extends Controller
{
  public function destroy($id)
  {
    SurfCodePrize::where('id', $id)->delete();
    return redirect('admin/surf_codes');
  }

  public function store(Request $request)
  {
    $code_id = $request->code_id;

    SurfCodePrize::create([
      'code_id' => $code_id,
      'prize_amount' => $request->prize_amount,
      'prize_type' => $request->prize_type
    ]);
    return redirect('admin/surf_codes');
  }
}
