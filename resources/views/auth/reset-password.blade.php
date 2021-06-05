<x-layout title="{{ $page }}">
  <x-alert />
  <h4>Reset Password</h4>
  <form action="{{ url('reset-password') }}" method="POST">
    @csrf
    <div class="row mb-3">
      <label for="email" class="col-sm-2 col-form-label">Email Address</label>
      <div class="col-sm-5">
        <input type="email" class="form-control @error('email') border border-danger @enderror" id="email" name="email" value="{{ $email }}">
        @error('email')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="row mb-3">
      <label for="password" class="col-sm-2 col-form-label">New Password</label>
      <div class="col-sm-5">
        <input type="password" class="form-control @error('password') border border-danger @enderror" id="password" name="password">
        @error('password')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="row mb-3">
      <label for="password_confirmation" class="col-sm-2 col-form-label">Confirm Password</label>
      <div class="col-sm-5">
        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
      </div>
    </div>
    <input type="hidden" name="token" value="{{ $token }}">
    <button type="submit" class="btn btn-primary">Reset Password</button>
  </form>
</x-layout>