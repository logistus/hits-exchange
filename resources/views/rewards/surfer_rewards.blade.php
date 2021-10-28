<x-layout title="{{ $page }}">
  <h4><a href="{{ url('surfer_rewards') }}">Surfer Rewards</a></h4>
  <p><strong>Server Time: </strong> {{ date("Y-m-d h:i:s a") }}</p>
  <p>The reward is valid only on the day you surf, and you can only choose one prize per day.</p>
  <p>Today you have surfed <strong>{{ Auth::user()->surfed_today }}</strong> pages.</p>
  <x-alert />
  @if (Auth::user()->surfer_reward_claimed)
  <div class="alert alert-info">You already claimed surfer reward for today.</div>
  @else
  @if ($surfer_rewards)
  @if ($surf_page && Auth::user()->surfed_today >= $surf_page)
  <div class="alert alert-info d-flex flex-column text-center align-items-center">
    Claim your reward
    <form action="{{ url('surfer_rewards') }}" method="POST" required>
      @csrf
      <select name="reward" class="form-select my-2">
        <option value="0">Select Reward</option>
        @foreach ($prizes as $prize)
        @if ($prize->credit_prize != null)
        <option value="Credits">{{ number_format($prize->credit_prize) }} Credits</option>
        @endif
        @if ($prize->banner_prize != null)
        <option value="Banner Impressions">{{ number_format($prize->banner_prize) }} Banner Impressions</option>
        @endif
        @if ($prize->square_banner_prize != null)
        <option value="Square Banner Impressions">{{ number_format($prize->square_banner_prize) }} Square Banner Impressions</option>
        @endif
        @if ($prize->text_ad_prize != null)
        <option value="Text Ad Impressions">{{ number_format($prize->text_ad_prize) }} Text Ad Impressions</option>
        @endif
        @if ($prize->purchase_balance != null)
        <option value="Purchase Balance">${{ $prize->purchase_balance }} Purchase Balance</option>
        @endif
        @endforeach
      </select>
      <button type="submit" class="btn btn-success">Claim Reward</button>
    </form>
  </div>
  @endif
  @foreach ($surfer_rewards as $surfer_reward)
  <div class="p-3 border d-flex rewards" @if ($surf_page==$surfer_reward->page) style="background-color: #d8e5f3;" @endif>
    <div>
      <div class="fs-4">Surf at least {{ $surfer_reward->page }} pages to choose one of the prizes below:</div>
      @php
      $rewards = array();
      if ($surfer_reward->credit_prize != NULL) {
      $reward = number_format($surfer_reward->credit_prize)." Credits";
      array_push($rewards, $reward);
      }
      if ($surfer_reward->banner_prize != NULL) {
      $reward = number_format($surfer_reward->banner_prize)." Banner Impressions";
      array_push($rewards, $reward);
      }
      if ($surfer_reward->square_banner_prize != NULL) {
      $reward = number_format($surfer_reward->square_banner_prize)." Square Banner Impressions";
      array_push($rewards, $reward);
      }
      if ($surfer_reward->text_ad_prize != NULL) {
      $reward = number_format($surfer_reward->text_ad_prize)." Text Ad Impressions";
      array_push($rewards, $reward);
      }
      if ($surfer_reward->purchase_balance != NULL) {
      $reward = "$".$surfer_reward->purchase_balance." Purchase Balance";
      array_push($rewards, $reward);
      }
      @endphp
      @foreach ($rewards as $reward)
      {{ $reward }}
      @if (!$loop->last)
      or
      @endif
      @endforeach
    </div>
  </div>
  @endforeach
  @else
  <p>There is no active surfer rewards.</p>
  @endif
  @endif
</x-layout>
