@php
use Carbon\Carbon;
@endphp
<x-layout title="{{ $page }}">
  <h4><a href="{{ url('user/profile') }}">Edit Profile</a></h4>
  <x-alert />
  @if (Auth::user()->email_verified_at)
  <form action="{{ url('user/profile') }}" method="POST">
    @csrf
    <div class="row mb-3">
      <label for="member_since" class="col-sm-2 col-form-label">Member Since</label>
      <div class="col-sm-5">
        <input type="text" readonly class="form-control-plaintext" id="member_since" value="{{ Carbon::create(Auth::user()->join_date)->format("j F Y") }}">
      </div>
    </div>
    <h4 class="my-3">Personal Informations</h4>
    <div class="row mb-3">
      <label for="name" class="col-sm-2 col-form-label">Name</label>
      <div class="col-sm-5">
        <input type="text" class="form-control  @error('name') border border-danger @enderror" id="name" name="name" value="{{ Auth::user()->name }}">
      </div>
      @error('name')
      <div class="text-danger offset-sm-2">{{ $message }}</div>
      @enderror
    </div>
    <div class="row mb-3">
      <label for="surname" class="col-sm-2 col-form-label">Surname</label>
      <div class="col-sm-5">
        <input type="text" class="form-control  @error('surname') border border-danger @enderror" id="surname" name="surname" value="{{ Auth::user()->surname }}">
      </div>
      @error('surname')
      <div class="text-danger offset-sm-2">{{ $message }}</div>
      @enderror
    </div>
    <div class="row mb-3">
      <label for="username" class="col-sm-2 col-form-label">Username</label>
      <div class="col-sm-5">
        <input type="text" class="form-control @error('username') border border-danger @enderror" id="username" name="username" value="{{ Auth::user()->username }}">
      </div>
      @error('username')
      <div class="text-danger offset-sm-2">{{ $message }}</div>
      @enderror
    </div>
    <div class="row mb-3">
      <label for="email" class="col-sm-2 col-form-label">Email</label>
      <div class="col-sm-5">
        <input type="email" class="form-control @error('email') border border-danger @enderror" id="email" name="email" value="{{ Auth::user()->email }}">
      </div>
      @error('email')
      <div class="text-danger offset-sm-2">{{ $message }}</div>
      @enderror
    </div>
    <div class="row mb-3">
      <label for="country" class="col-sm-2 col-form-label">Country</label>
      <div class="col-sm-5">
        <select name="country" id="country" class="form-select">
          <option value="0">Select</option>
          @foreach ($countries as $country)
          <option value="{{ $country->code }}" @if (Auth::user()->country == $country->code)
            selected
            @endif
            >{{ $country->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <h4 class="my-3">Cashout Settings</h4>
    <div class="row mb-3">
      <label for="payment_type" class="col-sm-2 col-form-label">Payment Type</label>
      <div class="col-sm-5">
        <select name="payment_type" id="payment_type" class="form-select">
          <option value="">Hold Commissions</option>
          <option value="btc" {{ Auth::user()->payment_type == "btc" ? "selected" : "" }}>Bitcoin</option>
          <option value="coinbase" {{ Auth::user()->payment_type == "coinbase" ? "selected" : "" }}>Coinbase</option>
        </select>
      </div>
    </div>
    <div class="row mb-3 payment_types {{ Auth::user()->payment_type != 'btc' ? 'd-none' : '' }}" id="payment_btc">
      <label for="btc_address" class="col-sm-2 col-form-label">BTC Address</label>
      <div class="col-sm-5">
        <input type="text" class="form-control @error('btc_address') border border-danger @enderror" id="btc_address" name="btc_address" value="{{ Auth::user()->btc_address }}">
        <small>We will send your money to this BTC address.</small>
      </div>
      @error('btc_address')
      <div class="text-danger offset-sm-2">{{ $message }}</div>
      @enderror
    </div>
    <div class="row mb-3 payment_types {{ Auth::user()->payment_type != 'coinbase' ? 'd-none' : '' }}" id="payment_coinbase">
      <label for="coinbase_email" class="col-sm-2 col-form-label">Coinbase Email</label>
      <div class="col-sm-5">
        <input type="email" class="form-control @error('coinbase_email') border border-danger @enderror" id="coinbase_email" name="coinbase_email" value="{{ Auth::user()->coinbase_email }}">
      </div>
      @error('coinbase_email')
      <div class="text-danger offset-sm-2">{{ $message }}</div>
      @enderror
    </div>
    <h4 class="mt-5">Email Preferences</h4>
    <p class="fs-6 fw-light">When do you want to receive an email notification</p>
    <div class="form-check">
      <label class="form-check-label" for="referral-noticication">
        Referral Notification
      </label>
      <input class="form-check-input" type="checkbox" name="referral_notification" id="referral-noticication" {{ Auth::user()->referral_notification ? "checked" : ""}}>
    </div>
    <div class="form-check">
      <label class="form-check-label" for="commission-noticication">
        Commission Notification
      </label>
      <input class="form-check-input" type="checkbox" name="commission_notification" id="commission-noticication" {{ Auth::user()->commission_notification ? "checked" : ""}}>
    </div>
    <div class="form-check">
      <label class="form-check-label" for="pm-noticication">
        Private Message Notification
      </label>
      <input class="form-check-input" type="checkbox" name="pm_notification" id="pm-noticication" {{ Auth::user()->pm_notification ? "checked" : ""}}>
    </div>
    <button type="submit" class="btn btn-primary offset-sm-2 mt-3">Save Changes</button>
  </form>
  <hr>
  <h4 class="my-3">Change Password</h4>
  <form action="{{ url('user/password') }}" method="POST">
    @csrf
    <div class="row mb-3">
      <label for="current_password" class="col-sm-2 col-form-label">Current Password</label>
      <div class="col-sm-5">
        <input type="password" class="form-control @error('current_password') border border-danger @enderror" id="current_password" name="current_password">
      </div>
      @error('current_password')
      <div class="text-danger offset-sm-2">{{ $message }}</div>
      @enderror
    </div>
    <div class="row mb-3">
      <label for="new_password" class="col-sm-2 col-form-label">New Password</label>
      <div class="col-sm-5">
        <input type="password" class="form-control @error('new_password') border border-danger @enderror" id="new_password" name="new_password">
      </div>
      @error('new_password')
      <div class="text-danger offset-sm-2">{{ $message }}</div>
      @enderror
    </div>
    <div class="row mb-3">
      <label for="new_password_confirmation" class="col-sm-2 col-form-label">Confirm New Password</label>
      <div class="col-sm-5">
        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
      </div>
    </div>
    <button type="submit" class="btn btn-primary offset-sm-2 mt-3">Change Password</button>
  </form>
  <hr>
  <h4 class="my-3">Delete Account</h4>
  <form action="{{ url('user/delete') }}" method="POST" class="alert alert-danger">
    <p>You can not <strong>undo</strong> this action. All your earned commissions, purchase balances, and advertisements will be deleted <strong>permanently</strong>. You will also <strong>lose all your referrals</strong>.</p>
    @csrf
    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure that you want to delete your account?');">I understand, delete my account</button>
  </form>
  @else
  <form action="{{ url('user/change-email') }}" method="POST">
    @csrf
    <div class="row mb-3">
      <label for="email" class="col-sm-2 col-form-label">Email</label>
      <div class="col-sm-5">
        <input type="email" class="form-control @error('email') border border-danger @enderror" id="email" name="email" value="{{ Auth::user()->email }}">
      </div>
      @error('email')
      <div class="text-danger offset-sm-2">{{ $message }}</div>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary offset-sm-2 mt-3">Change Email Address</button>
  </form>
  @endif
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script>
    $(function() {
      $("#payment_type").change(function() {
        if ($(this).val() == "btc") {
          $("#payment_coinbase").addClass("d-none");
          $("#payment_btc").removeClass("d-none");
        } else if ($(this).val() == "coinbase") {
          $("#payment_btc").addClass("d-none");
          $("#payment_coinbase").removeClass("d-none");
        } else {
          $("#payment_btc").addClass("d-none");
          $("#payment_coinbase").addClass("d-none");
        }
      });
    });

  </script>
</x-layout>
