<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <title>{{ config('app.name') }} - Login</title>
</head>
<body class="bg-light">
  <div style="min-height: 100vh;" class="d-flex flex-column justify-content-center align-items-center">
    <x-alert />
    <div class="text-center mb-3">
      <x-logo />
    </div>
    <div class="bg-white border border-primary border-opacity-25 w-25 px-5 py-4">
      <h2>Login</h2>
      <form method="POST" action="{{ url('login') }}">
        @csrf
        <div class="mb-3">
          <label for="username" class="form-label">Username</label>
          <input type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') border border-danger @enderror">
          @error('username')
          <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" name="password" class="form-control @error('password') border border-danger @enderror">
          @error('password')
          <div class="text-danger">{{ $message }}</div>
          @enderror
        </div>
        <div class="form-check mt-3">
          <input class="form-check-input" type="checkbox" name="remember" id="remember">
          <label class="form-check-label" for="remember">
            Remember me
          </label>
        </div>
        @if (config("app.env") == "production")
        <div>Google Enterprise Captcha</div>
        @endif
        <div class="d-grid">
          <button type="submit" class="btn btn-primary mt-3">Login</button>
        </div>
        <hr>
        <div class="mt-2">Don't have an account? <a href="{{ url('register') }}">Register</a></div>
        <div class="mt-2">Forgot password? <a href="{{ url('forgot-password') }}" class="mt-3">Reset</a></div>
      </form>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

</body>
</html>
