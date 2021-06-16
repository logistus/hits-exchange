<x-layout title="{{ $page }}">
  <h4><a href="{{ url('user/profile') }}">Edit Profile</a></h4>
  <x-alert />
  @if (Auth::user()->email_verified_at)
  <form action="{{ url('user/profile') }}" method="POST">
    @csrf
    <h4>Personal Informations</h4>
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
          <option value="{{ $country->country_iso_code }}" @if (Auth::user()->country == $country->country_iso_code)
            selected
            @endif
            >{{ $country->country_name }}</option>
          @endforeach
        </select>
      </div>
    </div>
    <h4 class="mt-5">Email Preferences</h4>
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
  <h4>Change Password</h4>
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
  <h4>Delete Account</h4>
  <p class="alert alert-danger">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Aliquam iure, alias laborum soluta temporibus laboriosam veniam blanditiis delectus voluptates, cumque maxime. Dolores, dolorum minima dolore error magnam facere modi laudantium.</p>
  <form action="{{ url('user/delete') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-danger">I understand, delete my account</button>
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
</x-layout>