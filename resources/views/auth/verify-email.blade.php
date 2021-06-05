<x-layout title="{{ $page }}">
  <x-alert />
  <div class="alert alert-warning">
    <h4>Verify Email Address</h4>
    <p>We have sent you an email message with a confirmation link.</p>
    <p>Click the link in the email message to confirm your email address.</p>
  </div>
  <hr>
  <p>If you didn't get an email or deleted it by accident click the button below to resend it.</p>
  <form action="{{ url('/email/verification-notification') }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-primary">Resend Verification Email</button>
  </form>
  <hr>
  <p>If you entered your email incorrect you can change it at your <a href="{{ url('user/profile') }}">profile</a> page.</p>
</x-layout>