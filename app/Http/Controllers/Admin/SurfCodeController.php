<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SurfCode;
use App\Models\SurfCodeClaim;
use App\Models\SurfCodePrize;
use Illuminate\Http\Request;

class SurfCodeController extends Controller
{
  public function list()
  {
    $surf_codes = SurfCode::all();
    return view('admin.surf_codes.list', compact('surf_codes'));
  }

  public function create()
  {
    return view('admin.surf_codes.add');
  }

  public function edit($id)
  {
    $surf_code = SurfCode::findOrFail($id);
    return view('admin.surf_codes.edit', compact('surf_code', 'id'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'code' => 'required',
      'valid_from' => 'required|date',
      'valid_to' => 'required|date',
      'surf_amount' => 'required|numeric',
    ]);

    SurfCode::create($request->all());

    return redirect('admin/surf_codes');
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'code' => 'required',
      'valid_from' => 'required|date',
      'valid_to' => 'required|date',
      'surf_amount' => 'required|numeric',
    ]);

    $surf_code = SurfCode::findOrFail($id);
    $surf_code->update($request->except('_token', '_method'));
    if (!$request->confirmed) {
      $surf_code->confirmed = 0;
      $surf_code->save();
    }
    $surf_code->save();

    return redirect('admin/surf_codes');
  }

  public function destroy($id)
  {
    SurfCode::where('id', $id)->delete();
    SurfCodeClaim::where('code_id', $id)->delete();
    SurfCodePrize::where('code_id', $id)->delete();
    return redirect('admin/surf_codes');
  }
}
