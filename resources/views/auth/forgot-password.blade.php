<x-layout title="{{ $page }}">
  <x-alert />
  <h4><a href="{{ url('forgot-password') }}">Forgot Password</a></h4>
  <p>Enter your email address below to get a password reset link.</p>
  <form action="{{ url('forgot-password') }}" method="POST">
    @csrf
    <div class="row mb-3">
      <div class="col-sm-5">
        <input type="email" class="form-control @error('email') border border-danger @enderror" id="email" name="email">
        @error('email')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Send Email</button>
  </form>
</x-layout>