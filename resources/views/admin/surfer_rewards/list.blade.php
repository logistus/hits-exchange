@php
use \App\Models\SurferReward;
@endphp
@extends('admin.layout')

@section('page')
Surfer Rewards
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>
  <li class="breadcrumb-item active">Surfer Rewards</li>
</ol>
@endsection

@section('content')
<p class="text-right">
  <a href="{{ url('admin/surfer_rewards/create') }}" class="btn btn-success" title="Add New Reward"><i class="fas fa-plus"></i> Add New Reward</a>
</p>
@if (count($surfer_rewards))
<table class="table table-bordered table-hover table-head-fixed">
  <thead>
    <tr>
      <th scope="col">Page</th>
      <th scope="col">Rewards</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody class="bg-light">
    @foreach($surfer_rewards as $surfer_reward)
    <tr>
      <td>{{ $surfer_reward->page }}</td>
      <td>
        @php
        $rewards = array();
        if ($surfer_reward->credit_prize != NULL) {
        $reward = $surfer_reward->credit_prize." Credits";
        array_push($rewards, $reward);
        }
        if ($surfer_reward->banner_prize != NULL) {
        $reward = $surfer_reward->banner_prize." Banner Impressions";
        array_push($rewards, $reward);
        }
        if ($surfer_reward->square_banner_prize != NULL) {
        $reward = $surfer_reward->square_banner_prize." Square Banner Impressions";
        array_push($rewards, $reward);
        }
        if ($surfer_reward->text_ad_prize != NULL) {
        $reward = $surfer_reward->text_ad_prize." Text Ad Impressions";
        array_push($rewards, $reward);
        }
        if ($surfer_reward->purchase_balance != NULL) {
        $reward = $surfer_reward->purchase_balance." Purchase Balance";
        array_push($rewards, $reward);
        }
        @endphp
        @foreach ($rewards as $reward)
        {{ $reward }}
        @if (!$loop->last)
        ,
        @endif
        @endforeach
      </td>
      <td>
        <div class="btn-group" role="group" aria-label="Manage Surfer Rewards">
          <a class="btn btn-sm btn-primary" href="{{ url('admin/surfer_rewards/edit', $surfer_reward->id) }}" title="Edit Surfer Reward"><i class="fas fa-edit"></i></a>
          <a class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');" href="{{ url('admin/surfer_rewards/delete', $surfer_reward->id) }}" title="Delete Surfer Reward"><i class="fas fa-trash"></i></a>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
@else
<p>No surfer rewards found.</p>
@endif
@endsection
