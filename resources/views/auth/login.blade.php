<x-layout title="{{ $page }}">
  <div class="row justify-content-center">
    <form method="POST" action="{{ url('login') }}" class="col-4">
      @error('invalid')
      <div class="alert alert-danger">{{ $message }}</div>
      @enderror
      @csrf
      <div class="mt-3">
        <input type="text" name="username" id="username" value="{{ old('username') }}" class="form-control @error('username') border border-danger @enderror" placeholder="username">
      </div>
      @error('username')
      <div class="text-danger">{{ $message }}</div>
      @enderror
      <div class="mt-3">
        <input type="password" name="password" class="form-control @error('password') border border-danger @enderror" placeholder="password">
      </div>
      @error('password')
      <div class="text-danger">{{ $message }}</div>
      @enderror
      <div class="d-flex justify-content-between">
        <div class="form-check mt-3">
          <input class="form-check-input" type="checkbox" name="remember" id="remember">
          <label class="form-check-label" for="remember">
            Remember me
          </label>
        </div>
        <a href="#" class="mt-3">Forgot Password?</a>
      </div>
      <button type="submit" class="btn btn-success mt-3">Login</button>
      <div class="mt-2">Don't have an account? <a href="{{ url('register') }}">Register</a></div>
      <div class="mt-2">Didn't get verification e-mail? <a href="{{ url('register') }}">Resend</a></div>
    </form>
  </div>
</x-layout>