<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SurferReward;
use Illuminate\Http\Request;

class SurferRewardController extends Controller
{
  public function list()
  {
    $surfer_rewards = SurferReward::all();
    return view('admin.surfer_rewards.list', compact('surfer_rewards'));
  }

  public function create()
  {
    return view('admin.surfer_rewards.add');
  }

  public function store(Request $request)
  {
    $request->validate([
      'page' => 'numeric',
      'credit_prize' => 'numeric|nullable',
      'banner_prize' => 'numeric|nullable',
      'text_ad_prize' => 'numeric|nullable',
      'purchase_balance' => 'numeric|nullable',
    ]);

    SurferReward::create($request->all());

    return redirect('admin/surfer_rewards');
  }

  public function edit(Request $request, $id)
  {
    $surfer_reward = SurferReward::findOrFail($id);
    return view('admin.surfer_rewards.edit', compact('surfer_reward', 'id'));
  }

  public function destroy($id)
  {
    $minimum_page = SurferReward::where('id', $id)->value('minimum_page');
    SurferReward::where('minimum_page', $minimum_page)->delete();
    return redirect('admin/surfer_rewards');
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'page' => 'numeric',
      'credit_prize' => 'numeric|nullable',
      'banner_prize' => 'numeric|nullable',
      'text_ad_prize' => 'numeric|nullable',
      'purchase_balance' => 'numeric|nullable',
    ]);

    $surfer_reward = SurferReward::findOrFail($id);
    $surfer_reward->update($request->except('_token', '_method'));
    $surfer_reward->save();

    return redirect('admin/surfer_rewards');
  }
}
