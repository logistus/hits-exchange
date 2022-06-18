  @php
  use App\Models\User;
  @endphp
  <x-layout title="{{ $page }}">
    <div class="row">
      <div class="col">
        <h1 class="fw-bold">Drive traffic to your website</h1>
        <p class="fs-3">A simple and <span class="text-primary fw-bold text-decoration-underline">free</span> way to drive traffic to your website, affiliate link, online campaign, or whatever you are promoting.</p>
        <a href="{{ url('register') }}" class="btn btn-lg btn-danger">Get Started</a>
      </div>
      <div class="col text-end">
        <img src="images/Campaign launch_Isometric.svg" alt="Campaign Launch">
      </div>
    </div>
    <h2 class="text-center text-secondary fw-bold my-5">Features</h2>
    <div class="row">
      <div class="col text-center me-3">
        <img src="images/Bank note_Isometric.svg" style="height: 128px;" alt="Bank Note">
        <h3 class="fw-bold my-2">Free</h3>
        <p class="px-5">Our services are completely free. You can promote your online campaign as a free user.</p>
      </div>
      <div class="col text-center me-3">
        <img src="images/Boss_Isometric.svg" style="height: 128px;" alt="Boss">
        <h3 class="fw-bold my-2">Easy</h3>
        <p class="px-5">You can start promoting your online campaign in seconds and get real people to visit your website.</p>
      </div>
      <div class="col text-center">
        <img src="images/Progress _Isometric.svg" style="height: 128px;" alt="Progress">
        <h3 class="fw-bold my-2">Effective</h3>
        <p class="px-5">See the results immediately and enjoy your simple, free traffic. Boost your website's ranking effortlessly.</p>
      </div>
    </div>
    <h2 class="text-center text-secondary fw-bold my-5">Statistics</h2>
    <div class="row bg-light">
      <div class="col text-center me-3">
        <h3 class="fw-bold my-2">{{ count(User::all()) }}</h3>
        <p class="px-5">Members Total</p>
      </div>
      <div class="col text-center me-3">
        <h3 class="fw-bold my-2">{{ User::signedUpToday() }}</h3>
        <p class="px-5">New Members Today</p>
      </div>
      <div class="col text-center me-3">
        <h3 class="fw-bold my-2">0</h3>
        <p class="px-5">Hits Delivered Total</p>
      </div>
      <div class="col text-center me-3">
        <h3 class="fw-bold my-2">0</h3>
        <p class="px-5">Hits Delivered Today</p>
      </div>
    </div>
  </x-layout>
