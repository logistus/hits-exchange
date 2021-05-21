<x-layout title="{{ $page }}">
  <h4><a href="{{ url('buy/start_page') }}">Buy Start Page</a></h4>
  <p>yada yada yada</p>
  <div class="d-flex flex-row justify-content-between mb-3">
    <a href="#" id="step-url" class="border w-100 text-decoration-none bg-primary text-white p-3 step">Select URL</a>
    <a href="#" id="step-date" class="border w-100 text-decoration-none bg-light p-3 step">Select Date(s)<span id="dates"></span></a>
    <a href="#" id="step-payment" class="border w-100 text-decoration-none bg-light p-3 step">Make Payment</a>
  </div>
  <div class="d-flex justify-content-center pt-3">
    <form method="POST" class="step-url _step">
      @if (count($user_websites) > 0)
      <select class="form-select" id="user_websites" aria-label="Select Website">
        <option value="0" selected>Select one of your websites</option>
        @foreach ($user_websites as $website)
        <option value="{{ $website->id }}">{{ $website->url }}</option>
        @endforeach
      </select>
      @endif
      <div class="my-3">
        <label for="start_page_url" class="form-label">or specify new URL</label>
        <input type="url" class="form-control" id="start_page_url">
      </div>
      <button id="step-url-next-btn" type="button" class="btn btn-primary">Next</button>
    </form>
    <div class="d-none step-date _step">
      <div id="datepicker" class="mt-3"></div>
      <button id="step-date-previous-btn" type="button" class="btn btn-primary mt-3">Previous</button>
      <button id="step-date-next-btn" type="button" class="btn btn-primary mt-3">Next</button>
    </div>
    <div id="payment" class="d-none step-payment _step">
      <div id="price" class="fs-2 mt-2">$0</div>
      <div>-- TODO -- Payment Buttons</div>
      <button id="step-payment-previous-btn" type="button" class="btn btn-primary mt-3">Previous</button>
    </div>
  </div>
  <!--
  <div class="row mt-3">
    <div class="col">
      <h4>Your Start Pages</h4>
      @if (count($user_start_pages) > 0)
      <table class="table table-bordered align-middle">
        <thead>
          <tr class="bg-light">
            <th scope="col">URL</th>
            <th scope="col">Start Date</th>
            <th scope="col">Total Views</th>
            <th scope="col">Status</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($user_start_pages as $start_page)
          <tr>
            <td>
              <a href="{{ $start_page->url }}" target="_blank" rel="noopener noreferrer">{{ $start_page->url }}</a> <i class="bi-box-arrow-up-right" style="font-size: .8rem;"></i>
            </td>
            <td>{{ $start_page->start_date }}</td>
            <td>{{ $start_page->total_views }}</td>
            <td>{{ now() < $start_page->start_date ? $start_page->status : "Done" }}</td>
            <td>
              <a href="{{ url('start_page/delete', $start_page->id) }}" onclick="return confirm('Are you sure?');" class="btn btn-danger">Delete</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
      <div>You don't have any start pages.</div>
      @endif
    </div>
  </div>-->
  @php
  $locked_dates = array();
  foreach ($bought_dates as $date) {
  array_push($locked_dates, $date);
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
    let locked_dates_as_array = bought_dates.replace(/quot/g, '').replace(/{&;start_date&;:&;/g, '').replace(/&;}/g, '').split(',');
    const picker = new Litepicker({
      element: document.getElementById('datepicker'),
      elementEnd: document.getElementById('dates'),
      inlineMode: true,
      singleMode: true,
      delimiter: ',',
      firstDay: 1,
      lockDays: locked_dates_as_array,
      minDate: new Date().getTime(),
      plugins: ['multiselect'],
    });
    picker.on('multiselect.select', function(date) {
      $("#dates").html(" <strong>(" + (picker.getMultipleDates().length + 1) + " selected)</strong>");
    });
    picker.on('multiselect.deselect', function(date) {
      if (picker.getMultipleDates().length - 1 > 0) {
        $("#dates").html(" <strong>(" + (picker.getMultipleDates().length - 1) + " selected)</strong>");
      } else {
        $("#dates").empty();
      }
    });

    $("#step-url, #step-date, #step-payment").click(function(e) {
      e.preventDefault();
    });

    $("#step-url-next-btn").click(function(e) {
      e.preventDefault();
      if ($("#user_websites").val() != 0 || $("#start_page_url").val() != "") {
        $(".step").removeClass("bg-primary").removeClass("text-white").addClass("bg-light");
        $("#step-date").removeClass("bg-light").addClass("bg-primary").addClass("text-white");
        $("#step-url").removeClass("bg-light").addClass("bg-success").addClass("text-white");
        $("._step").addClass("d-none");
        $(".step-date").removeClass("d-none").addClass("d-block");
      } else {
        alert("select url");
      }
    });

    $("#step-date-previous-btn").click(function(e) {
      e.preventDefault();
      $(".step").removeClass("bg-primary").removeClass("text-white").removeClass("bg-success").addClass("bg-light");
      $("#step-url").removeClass("bg-light").addClass("bg-primary").addClass("text-white");
      $("._step").addClass("d-none");
      $(".step-url").removeClass("d-none").addClass("d-block");
    });

    $("#step-date-next-btn").click(function(e) {
      e.preventDefault();
      if (picker.multipleDatesToString() != "") {
        $(".step").removeClass("bg-primary").removeClass("text-white").addClass("bg-light");
        $("#step-payment").removeClass("bg-light").addClass("bg-primary").addClass("text-white");
        $("#step-date").removeClass("bg-light").addClass("bg-success").addClass("text-white");
        $("#step-url").removeClass("bg-light").addClass("bg-success").addClass("text-white");
        $("._step").addClass("d-none");
        $(".step-payment").removeClass("d-none").addClass("d-block");
      } else {
        alert("Please select date(s)");
      }
    });

    $("#step-payment-previous-btn").click(function(e) {
      e.preventDefault();
      $(".step").removeClass("bg-primary").removeClass("text-white").removeClass("bg-success").addClass("bg-light");
      $("#step-date").removeClass("bg-light").addClass("bg-primary").addClass("text-white");
      $("#step-url").removeClass("bg-light").addClass("bg-success").addClass("text-white");
      $("._step").addClass("d-none");
      $(".step-date").removeClass("d-none").addClass("d-block");
    });
  </script>
</x-layout>