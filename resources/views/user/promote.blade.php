<x-layout title="{{ $page }}">
  <h4><a href="{{ url('promote') }}">Affiliate Links</a></h4>
  <div>Do you want to earn commissions? Then, this page is where you want to be.</div>
  <div class="mt-3 fw-bold fs-4">What are those links below?</div>
  <div>Use the links, banners and splash pages below to promote {{ config('app.name') }} in order to get referrals.</div>
  <div>Earn commissions (based on your member level) whenever your referrals buy something from {{ config('app.name') }}.</div>
  <div class="mt-3 fw-bold fs-4">Add trackers to your links</div>
  <div>Do you want to know from which source you get the most referrals?</div>
  <div>Simply add trackers to your links and see stats for those trackers from the <a href="{{ url('promote/trackers') }}">trackers</a> page.</div>
  <div>To add a tracker to any of your links, add <code>?t=trackername</code> at the end of your link. You can rename <code>trackername</code> whatever you want.</div>
  <div class="mt-3">For example, if you want to advertise your main affiliate link at Hungry for Hits, you can add a tracker to your link like below:</div>
  <div>
    <a href="{{ config('app.url') }}/ref/{{ request()->user()->username }}/?t=hungryforhits" target="_blank">{{ config('app.url') }}/ref/{{ request()->user()->username }}/?t=hungryforhits</a>
  </div>
  <ul class="nav nav-tabs mt-5" id="linksTab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Main Affiliate Link</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="splash-tab" data-bs-toggle="tab" data-bs-target="#splash-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Splash Pages</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="banner-tab" data-bs-toggle="tab" data-bs-target="#banner-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Banners</button>
    </li>
  </ul>
  <div class="tab-content" id="linksTabContent">
    <div class="tab-pane pt-3 fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
      <a href="{{ config('app.url') }}/ref/{{ request()->user()->username }}/" target="_blank">
        {{ config('app.url') }}/ref/{{ request()->user()->username }}/
      </a>
    </div>
    <div class="tab-pane pt-3 fade" id="splash-tab-pane" role="tabpanel" aria-labelledby="splash-tab" tabindex="0">
      @foreach ($splash_pages as $splash_page)
      <a href="{{ config('app.url') }}/splash/{{ $splash_page->id }}/ref/{{ request()->user()->username }}/" target="_blank">
        {{ config('app.url') }}/splash/{{ $splash_page->id }}/ref/{{ request()->user()->username }}/
      </a>
      @endforeach
    </div>
    <div class="tab-pane pt-3 fade" id="banner-tab-pane" role="tabpanel" aria-labelledby="banner-tab" tabindex="0">
      <p>Banners</p>
    </div>
  </div>
</x-layout>
