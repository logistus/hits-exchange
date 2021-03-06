<x-layout title="{{ $page }}">
  <style>
    .bonus:last-child {
      margin-top: 15px;
    }

  </style>
  <h3 class="mb-5">Welcome back, {{ Auth::user()->name }}</h3>
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
    <div class="row">
      <div class="col mb-3">
        <div class="card bg-light w-100 text-center me-3">
          <div class="card-header">Credits</div>
          <div class="card-body">
            <h5>{{ Auth::user()->credits }}</h5>
            <a href="{{ url('websites') }}">Assign</a>
          </div>
        </div>
      </div>
      <div class="col mb-3">
        <div class="card bg-light w-100 text-center me-3">
          <div class="card-header">Banner Impressions</div>
          <div class="card-body">
            <h5>{{ Auth::user()->banner_imps }}</h5>
            <a href="{{ url('banners') }}">Assign</a>
          </div>
        </div>
      </div>
      <div class="col mb-3">
        <div class="card bg-light w-100 text-center me-3">
          <div class="card-header">Square Banner Impressions</div>
          <div class="card-body">
            <h5>{{ Auth::user()->square_banner_imps }}</h5>
            <a href="{{ url('square_banners') }}">Assign</a>
          </div>
        </div>
      </div>
      <div class="col mb-3">
        <div class="card bg-light w-100 text-center">
          <div class="card-header">Text Ad Impressions</div>
          <div class="card-body">
            <h5>{{ Auth::user()->text_imps }}</h5>
            <a href="{{ url('texts') }}">Assign</a>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col mb-3">
        <div class="card bg-light w-100 text-center">
          <div class="card-header">Commissions</div>
          <div class="card-body">
            <h5>${{ number_format(Auth::user()->commissions_all->sum('amount'), 2) }}</h5>
            <a href="{{ url('user/commissions') }}">Details</a>
          </div>
        </div>
      </div>
      <div class="col mb-3">
        <div class="card bg-light w-100 text-center">
          <div class="card-header">Purchase Balance</div>
          <div class="card-body">
            <h5>${{ number_format(Auth::user()->purchase_balance_completed->sum('amount'), 2) }}</h5>
            <a href="{{ url('user/purchase_balance') }}">Details</a>
          </div>
        </div>
      </div>
      <div class="col mb-3">
        <div class="card bg-light w-100 text-center">
          <div class="card-header">Referrals</div>
          <div class="card-body">
            <h5>{{ count(Auth::user()->referrals) }}</h5>
            <a href="{{ url('user/referrals') }}">Details</a> - <a href="{{ url('promote') }}">Promote</a>
          </div>
        </div>
      </div>
    </div>
    @if ($surf_codes)
    <h4 class="my-3">Surf Code</h4>
    <div class="bg-light border p-3">
      <p>Enter the code <strong>{{ $surf_codes->code }}</strong> on the <a href="{{ url('surf_codes') }}">Surf Codes</a> page (under Rewards), surf {{ $surf_codes->surf_amount }} pages and win a prize!</strong></p>
    </div>
    @endif
</x-layout>
