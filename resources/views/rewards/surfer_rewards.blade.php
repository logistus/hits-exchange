<x-layout title="{{ $page }}">
  <h4><a href="{{ url('surfer_rewards') }}">Surfer Rewards</a></h4>
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
        <option value="{{ $prize->prize_type }}">{{ $prize->prize_amount }} {{ $prize->prize_type }}</option>
        @endforeach
      </select>
      <button type="submit" class="btn btn-success">Claim Reward</button>
    </form>
  </div>
  @endif
  @foreach ($surfer_rewards as $minimum_page => $rewards)
  <div class="p-3 border d-flex rewards" @if ($surf_page==$minimum_page) style="background-color: #d8e5f3;" @endif>
    <div>
      <div class="fs-4">Surf at least {{ $minimum_page }} pages to choose one of the prizes below:</div>
      @foreach ($rewards as $reward)
      <span>{{ $reward->prize_amount }} {{ $reward->prize_type }}
        @if (!$loop->last)
        ,
        @endif
      </span>
      @endforeach
    </div>
  </div>
  @endforeach
  @else
  <p>There is no active surfer rewards.</p>
  @endif
  @endif
</x-layout>