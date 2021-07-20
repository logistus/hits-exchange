<x-layout title="{{ $page }}">
  <h4><a href="{{ url('buy/login_spotlight') }}">Buy Login Spotlight</a></h4>
  <x-alert />
  <p>Every member will see login spotlight every time they login the first time at current day. <br>The price is $2 for each day.</p>
  <div class="d-flex pt-3">
    <form method="POST" action="{{ url('buy/login_spotlight') }}">
      @csrf
      <h4>Select a website</h4>
      @if (count($user_websites) > 0)
      <div class="mb-3">
        <label for="login_spotlight_user_website" class="form-label">Select from your websites:</label>
        <select class="form-select" id="login_spotlight_user_website" name="login_spotlight_user_website" aria-label="Select Website">
          <option value="0" selected>Select one of your websites</option>
          @foreach ($user_websites as $website)
          <option value="{{ $website->id }}" {{ old('login_spotlight_user_website') == $website->id ? "selected" : ""}}>{{ $website->url }}</option>
          @endforeach
        </select>
      </div>
      @else
      <p>You don't have any websites.</p>
      @endif
      <h4>or specify new URL</h4>
      <div class="my-3">
        <input type="url" class="form-control" id="new_login_spotlight_url" value="{{ old('new_login_spotlight_url') }}" name="new_login_spotlight_url">
      </div>
      @error('login_spotlight_user_website')
      <div class="text-danger">You must select one from your websites or specify new URL.</div>
      @enderror
      @error('new_login_spotlight_url')
      <div class="text-danger">You must select one from your websites or specify new URL.</div>
      @enderror
      <h4 class="mt-3">Select date(s)</h4>
      <div id="datepicker" class="my-3"></div>
      @error('selected_dates')
      <div class="text-danger">You must select 1 or more days.</div>
      @enderror
      <input type="hidden" name="selected_dates" id="dates">
      <button type="button" id="place_order" class="btn btn-primary d-block">Place Order</button>
    </form>
  </div>
  @php
  $locked_dates = array();
  foreach ($bought_dates as $dates) {
  array_push($locked_dates, $dates->dates);
  }
  @endphp
  <script src="{{ asset('js/jquery-3.6.0.js') }}"></script>
  <script src="{{ asset('js/moment.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/plugins/multiselect.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" />
  <style>
  </style>
  <script>
    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Dec"];
    let bought_dates = "{{ implode(',', $locked_dates) }}";
    console.log(bought_dates);
    console.log('{{ date( "Y-m-d", strtotime( "+1 days" ) ) }}');
    let locked_dates_as_array = bought_dates.replace(/ /g, '').split(',');
    const picker = new Litepicker({
      element: document.getElementById('datepicker')
      , inlineMode: true
      , singleMode: true
      , delimiter: ','
      , firstDay: 1
      , numberOfMonths: 2
      , numberOfColumns: 2
      , lockDays: locked_dates_as_array
      , minDate: '{{ date( "Y-m-d", strtotime( "+1 days" ) ) }}', // current day can't be selected, also javascript can't be used to select day because we need server's time
      plugins: ['multiselect']
    , });
    $("#place_order").click(function() {
      $("#dates").val(picker.multipleDatesToString());
      $("form").submit();
    });

  </script>
</x-layout>
