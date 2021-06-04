<x-layout title="{{ $page }}">
  <h4><a href="{{ url('user/profile') }}">Edit Profile</a></h4>
  <x-alert />
  <form action="{{ url('user/profile') }}" method="POST">
    @csrf
    <h4>Personal Informations</h4>
    <div class="row mb-3">
      <label for="name" class="col-sm-2 col-form-label">Name</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}">
      </div>
    </div>
    <div class="row mb-3">
      <label for="surname" class="col-sm-2 col-form-label">Surname</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" id="surname" name="surname" value="{{ Auth::user()->surname }}">
      </div>
    </div>
    <div class="row mb-3">
      <label for="username" class="col-sm-2 col-form-label">Username</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" id="username" name="username" value="{{ Auth::user()->username }}">
      </div>
    </div>
    <div class="row mb-3">
      <label for="email" class="col-sm-2 col-form-label">Email</label>
      <div class="col-sm-5">
        <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}">
      </div>
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
    <hr>
    <h4>Email Preferences</h4>
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
    <hr>
    <button type="submit" class="btn btn-primary offset-sm-2">Save Changes</button>
  </form>
</x-layout>