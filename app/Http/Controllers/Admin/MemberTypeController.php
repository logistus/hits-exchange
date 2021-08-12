<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberTypeController extends Controller
{
  public function index()
  {
    $user_types = UserType::all();
    return view('admin.member_types.list', compact('user_types'));
  }

  public function create()
  {
    return view('admin.member_types.add');
  }

  public function store(Request $request)
  {
    $request->validate([
      "name" => "required",
      "surf_timer" => "required|numeric|min:1",
      "surf_ratio" => "required|numeric|min:0.1",
      "commission_ratio" => "required|numeric|min:1",
      "max_websites" => "required|numeric|min:1",
      "max_banners" => "required|numeric|min:1",
      "max_square_banners" => "required|numeric|min:1",
      "max_texts" => "required|numeric|min:1",
      "min_auto_assign" => "required|numeric|min:0",
      "credit_reward_ratio" => "required|numeric|min:0",
      "credits_to_banner" => "required|numeric|min:0",
      "credits_to_square_banner" => "required|numeric|min:0",
      "credits_to_text" => "required|numeric|min:0",
    ]);

    UserType::create($request->all());
    return redirect('admin/member_types');
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      "name" => "required",
      "surf_timer" => "required|numeric|min:1",
      "surf_ratio" => "required|numeric|min:0.1",
      "commission_ratio" => "required|numeric|min:1",
      "max_websites" => "required|numeric|min:1",
      "max_banners" => "required|numeric|min:1",
      "max_square_banners" => "required|numeric|min:1",
      "max_texts" => "required|numeric|min:1",
      "min_auto_assign" => "required|numeric|min:0",
      "credit_reward_ratio" => "required|numeric|min:0",
      "credits_to_banner" => "required|numeric|min:0",
      "credits_to_square_banner" => "required|numeric|min:0",
      "credits_to_text" => "required|numeric|min:0",
    ]);
    $user_type = UserType::findOrFail($id);
    $user_type->update($request->all());
    return redirect('admin/member_types');
  }

  public function destroy($id)
  {
    $user_type = UserType::findOrFail($id);
    $user_type->delete();
    return redirect('admin/member_types');
  }

  public function edit($id)
  {
    $user_type = UserType::findOrFail($id);
    return view('admin.member_types.edit', compact('user_type'));
  }
}
