<x-layout title="{{ $page }}">
  <h4><a href="{{ url('buy/start_page') }}">Buy Start Page</a></h4>
  <x-alert />
  <p>Every member will see start page every time they start to surf throughout the day. Buy now to get maximum exposure to your website.<br>The price is $1 for each day.</p>
  <div class="d-flex pt-3">
    <form method="POST" action="{{ url('buy/start_page') }}">
      @csrf
      <h4>Select a website</h4>
      @if (count($user_websites) > 0)
      <div class="mb-3">
        <label for="start_page_user_website" class="form-label">Select from your websites:</label>
        <select class="form-select" id="start_page_user_website" name="start_page_user_website" aria-label="Select Website">
          <option value="0" selected>Select one of your websites</option>
          @foreach ($user_websites as $website)
          <option value="{{ $website->id }}" {{ old('start_page_user_website') == $website->id ? "selected" : ""}}>{{ $website->url }}</option>
          @endforeach
        </select>
      </div>
      @endif
      <div class="my-3">
        <label for="new_start_page_url" class="form-label">or specify new URL:</label>
        <input type="url" class="form-control" id="new_start_page_url" value="{{ old('new_start_page_url') }}" name="new_start_page_url">
      </div>
      @error('start_page_user_website')
      <div class="text-danger">You must select one from your websites or specify new URL.</div>
      @enderror
      @error('new_start_page_url')
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
    let locked_dates_as_array = bought_dates.replace(/ /g, '').split(',');
    const picker = new Litepicker({
      element: document.getElementById('datepicker'),
      inlineMode: true,
      singleMode: true,
      delimiter: ',',
      firstDay: 1,
      numberOfMonths: 2,
      numberOfColumns: 2,
      lockDays: locked_dates_as_array,
      minDate: '{{ date( "Y-m-d", strtotime( "+1 days" ) ) }}', // current day can't be selected, also javascript can't be used to select day because we need server's time
      plugins: ['multiselect'],
    });
    $("#place_order").click(function() {
      $("#dates").val(picker.multipleDatesToString());
      $("form").submit();
    });

    picker.on('multiselect.select', function(date) {
      console.log(picker.multipleDatesToString());
      /*
      let total_days = picker.getMultipleDates().length + 1;
      let total_price = total_days * start_page_price;
      $("#date_title").html(" <strong>(" + total_days + " selected)</strong>");
      $("#price_title").html(" <strong>($" + total_price + ")</strong>");
      $("[name=item_name").val("Start Page - " + total_days + " day(s)");
      $("[name=amountf").val(total_price);
      */
    });
    picker.on('multiselect.deselect', function(date) {
      /*
      let total_days = picker.getMultipleDates().length - 1;
      let total_price = total_days * start_page_price;

      if (total_days > 0) {
        $("#date_title").html(" <strong>(" + total_days + " selected)</strong>");
        $("#price_title").html(" <strong>($" + total_price + ")</strong>");
        $("[name=item_name").val("Start Page - " + total_days + " day(s)");
        $("[name=amountf").val(total_price);
      } else {
        $("#price_title").empty();
        $("#date_title").empty();
      }
      */
    });
  </script>
</x-layout>