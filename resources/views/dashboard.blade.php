<x-layout title="{{ $page }}">
  <style>
    .bonus:last-child {
      margin-top: 15px;
    }

  </style>
  <h3>Welcome back, {{ Auth::user()->name }}</h3>
  @if ($signup_bonuses && Auth::user()->correct_clicks < $signup_bonuses->max('surf_amount'))
    <div class="bg-light border p-3">
      <h4 class="mb-3">Signup Bonus(es)</h4>
      @foreach ($signup_bonuses as $signup_bonus)
      <div class="bonus">
        <div>Surf {{ $signup_bonus->surf_amount }} pages to earn {{ $signup_bonus->bonus_type == "Purchase Balance" ? "$" : "" }}{{ $signup_bonus->bonus_amount }} {{ $signup_bonus->bonus_type }}</div>
        <div class="progress mt-1">
          <div class="progress-bar" role="progressbar" style="width: {{ Auth::user()->correct_clicks > 0 ? (100 *  Auth::user()->correct_clicks) / $signup_bonus->surf_amount : "0" }}%" aria-valuenow="{{ Auth::user()->correct_clicks > 0 ? (100 *  Auth::user()->correct_clicks) / $signup_bonus->surf_amount : "0" }}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
      @endforeach
    </div>
    @endif

</x-layout>
