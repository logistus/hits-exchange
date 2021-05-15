<x-layout title="{{ $page }}">
  <div class="row justify-content-center">
    <form method="POST" action="{{ url('register') }}" class="col-4">
      @csrf
      <div class="mt-3">
        <input type="text" placeholder="name" name="name" value="{{ old('name') }}" class="form-control @error('name') border border-danger @enderror">
      </div>
      @error('name')
      <div class="text-danger">{{ $message }}</div>
      @enderror

      <div class="mt-3">
        <input type="text" placeholder="username" value="{{ old('username') }}" name="username" class="form-control @error('username') border border-danger @enderror">
      </div>
      @error('username')
      <div class="text-danger">{{ $message }}</div>
      @enderror

      <div class="mt-3">
        <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') border border-danger @enderror" placeholder="email address">
      </div>
      @error('email')
      <div class="text-danger">{{ $message }}</div>
      @enderror
      <div class="mt-3">
        <input type="password" name="password" class="form-control @error('password') border border-danger @enderror" placeholder="password">
      </div>
      @error('password')
      <div class="text-danger">{{ $message }}</div>
      @enderror
      <div class="mt-3">
        <input type="password" name="password_confirmation" class="form-control" placeholder="confirm password">
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
      <div class="mt-2">Already registered? <a href="{{ url('login') }}">Login</a></div>
    </form>
  </div>
</x-layout>