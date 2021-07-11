<x-layout title="{{ $page }}">
  <h4><a href="{{ url('user/referrals') }}">Referrals</a> > <a href="{{ url()->current() }}">Transfer Credits</a></h4>
  <x-alert />
  <p>You have {{ Auth::user()->credits }} credits in your account.</p>
  <p>You are transferring credits to your referral <strong>{{ $transfer_to }}</strong> </p>
  <form action="{{ url()->current() }}" method="POST">
    @csrf
    <div class="mb-3 col-3">
      <label for="credits" class="form-label">Credits to transfer</label>
      <input type="number" min="1" max="{{ Auth::user()->credits }}" name="credits" id="credits" class="form-control">
      @error('credits')
      <div class="text-danger">{{ $message }}</div>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary">Transfer</button>
  </form>
</x-layout>
