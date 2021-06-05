<x-layout title="{{ $page }}">
  <h4><a href="{{ url('login') }}">Login</a></h4>
  <x-alert />
  <form method="POST" action="{{ url('login') }}">
    @csrf
    <div class="row mb-3">
      <label for="username" class="col-sm-1 col-form-label">Username</label>
      <div class="col-sm-5">
        <input type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') border border-danger @enderror">
      </div>
      @error('username')
      <div class="text-danger offset-sm-1">{{ $message }}</div>
      @enderror
    </div>
    <div class="row mb-3">
      <label for="password" class="col-sm-1 col-form-label">Password</label>
      <div class="col-sm-5">
        <input type="password" name="password" class="form-control @error('password') border border-danger @enderror">
      </div>
      @error('password')
      <div class="text-danger offset-sm-1">{{ $message }}</div>
      @enderror
    </div>
    <div class="form-check mt-3">
      <input class="form-check-input" type="checkbox" name="remember" id="remember">
      <label class="form-check-label" for="remember">
        Remember me
      </label>
    </div>
    <button type="submit" class="btn btn-success mt-3">Login</button>
    <hr>
    <div class="mt-2">Don't have an account? <a href="{{ url('register') }}">Register</a></div>
    <div class="mt-2">Forgot password? <a href="{{ url('forgot-password') }}" class="mt-3">Reset</a></div>
  </form>
</x-layout>