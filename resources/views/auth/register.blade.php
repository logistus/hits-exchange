@php
use App\Models\User;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <title>{{ config('app.name') }} - Register</title>
</head>
<body class="bg-light">
  <div style="min-height: 100vh;" class="d-flex flex-column justify-content-center align-items-center">
    <x-alert />
    <div class="text-center my-3">
      <x-logo />
    </div>
    <div class="bg-white border border-primary border-opacity-25 px-5 py-4 ">
      <h2>Register</h2>
      <form method="POST" action="{{ url('register') }}">
        @csrf
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') border border-danger @enderror">
            @error('name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6 mb-3">
            <label for="surname" class="form-label">Surname</label>
            <input type="text" name="surname" value="{{ old('surname') }}" class="form-control @error('surname') border border-danger @enderror">
            @error('surname')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') border border-danger @enderror">
            @error('username')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') border border-danger @enderror">
            @error('email')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="mb-3">
          <label for="country" class="form-label">Country</label>
          <select name="country" id="country" class="form-select  @error('country') border border-danger @enderror">
            <option value="">Select</option>
            @foreach ($countries as $country)
            <option value="{{ $country->code }}">{{ $country->name }}</option>
            @endforeach
          </select>
          @error('country')
          <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control @error('password') border border-danger @enderror">
            @error('password')
            <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
          <div class="col-md-6 mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control">
          </div>
        </div>
        @if( count(User::where('username', request()->cookie('hits_exchange_ref'))->get()))
        <div class="alert alert-info">You have been referred by {{ User::where('username', request()->cookie('hits_exchange_ref'))->get()->first()->full_name }}</div>
        @endif
        <div class="form-check mt-3">
          <input class="form-check-input" type="checkbox" id="tos" name="tos">
          <label class="form-check-label" for="tos">
            Accept <a href="{{ url('terms') }}" target="_blank">Terms of Service</a>
          </label>
        </div>
        @error('tos')
        <div class="text-danger">Terms of Service must be accepted.</div>
        @enderror
        <div class="d-grid">
          <button type="submit" class="btn btn-primary mt-3">Register</button>
        </div>
        <hr>
        <div class="mt-2">Already registered? <a href="{{ url('login') }}">Login</a></div>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>
</html>
