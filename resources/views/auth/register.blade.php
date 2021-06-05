<x-layout title="{{ $page }}">
  <h4><a href="{{ url('register') }}">Register</a></h4>
  <x-alert />
  <form method="POST" action="{{ url('register') }}">
    @csrf
    <div class="row mb-3">
      <label for="name" class="col-sm-2 col-form-label">Name</label>
      <div class="col-sm-5">
        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') border border-danger @enderror">
      </div>
      @error('name')
      <div class="text-danger offset-sm-2">{{ $message }}</div>
      @enderror
    </div>
    <div class="row mb-3">
      <label for="surname" class="col-sm-2 col-form-label">Surname</label>
      <div class="col-sm-5">
        <input type="text" name="surname" value="{{ old('surname') }}" class="form-control @error('surname') border border-danger @enderror">
      </div>
      @error('surname')
      <div class="text-danger offset-sm-2">{{ $message }}</div>
      @enderror
    </div>
    <div class="row mb-3">
      <label for="username" class="col-sm-2 col-form-label">Username</label>
      <div class="col-sm-5">
        <input type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') border border-danger @enderror">
      </div>
      @error('username')
      <div class="text-danger offset-sm-2">{{ $message }}</div>
      @enderror
    </div>
    <div class="row mb-3">
      <label for="email" class="col-sm-2 col-form-label">Email Address</label>
      <div class="col-sm-5">
        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') border border-danger @enderror">
      </div>
      @error('email')
      <div class="text-danger offset-sm-2">{{ $message }}</div>
      @enderror
    </div>
    <div class="row mb-3">
      <label for="country" class="col-sm-2 col-form-label">Country</label>
      <div class="col-sm-5">
        <select name="country" id="country" class="form-select  @error('country') border border-danger @enderror">
          <option value="">Select</option>
          @foreach ($countries as $country)
          <option value="{{ $country->country_iso_code }}">{{ $country->country_name }}</option>
          @endforeach
        </select>
      </div>
      @error('country')
      <div class="text-danger offset-sm-2">{{ $message }}</div>
      @enderror
    </div>
    <div class="row mb-3">
      <label for="password" class="col-sm-2 col-form-label">Password</label>
      <div class="col-sm-5">
        <input type="password" name="password" class="form-control @error('password') border border-danger @enderror">
      </div>
      @error('password')
      <div class="text-danger offset-sm-2">{{ $message }}</div>
      @enderror
    </div>
    <div class="row mb-3">
      <label for="password_confirmation" class="col-sm-2 col-form-label">Confirm Password</label>
      <div class="col-sm-5">
        <input type="password" name="password_confirmation" class="form-control">
      </div>
    </div>
    <div class="form-check mt-3">
      <input class="form-check-input" type="checkbox" id="tos" name="tos">
      <label class="form-check-label" for="tos">
        Accept <a href="#">Terms of Service</a>
      </label>
    </div>
    @error('tos')
    <div class="text-danger">Terms of Service must be accepted.</div>
    @enderror
    <button type="submit" class="btn btn-primary mt-3">Register</button>
    <hr>
    <div class="mt-2">Already registered? <a href="{{ url('login') }}">Login</a></div>
  </form>
</x-layout>