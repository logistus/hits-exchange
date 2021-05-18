<x-layout title="{{ $page }}">
  <h4><a href="{{ url('buy/start_page') }}">Buy Start Page</a></h4>
  <p>yada yada yada</p>
  <div class="d-flex justify-content-between mb-3">
    <a href="#" class="badge rounded-pill bg-primary">Select URL</a>
    <a href="#" class="badge rounded-pill bg-secondary">Select Date(s)</a>
    <a href="#" class="badge rounded-pill bg-secondary">Make Payment</a>
  </div>
  <div class="d-flex justify-content-between">
    <form method="POST">
      @if (count($user_websites) > 0)
      <select class="form-select" aria-label="Select Website">
        <option value="0" selected>Select on of your websites</option>
        @foreach ($user_websites as $website)
        <option value="{{ $website->id }}">{{ $website->url }}</option>
        @endforeach
      </select>
      @endif
      <div class="my-3">
        <label for="start_page_url" class="form-label">or specify new URL</label>
        <input type="url" class="form-control" id="start_page_url" placeholder="https://www.yoursite.com/">
      </div>
    </form>
    <div style="min-height: 300px;" class="d-none">
      <strong>Select Date(s)</strong>
      <div id="datepicker" class="mt-3"></div>
    </div>
    <div id="payment" class="d-none">
      <strong>Make Payment</strong>
      <div id="price" class="fs-2 mt-2">$0</div>
      -- TODO -- Payment Buttons
    </div>
  </div>
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
  </div>
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
      $("#dates").append(
        "<div id='" + date.dateInstance.getDate() + "-" +
        (date.dateInstance.getMonth() + 1) + "-" +
        date.dateInstance.getFullYear() + "'>" +
        date.dateInstance.getDate() + " " +
        months[date.dateInstance.getMonth()] + " " +
        date.dateInstance.getFullYear() + "</div>");
      $("#price").text("$" + (2 * $("#dates *").length));
    });
    picker.on('multiselect.deselect', function(date) {
      $("#" + date.dateInstance.getDate() + "-" +
        (date.dateInstance.getMonth() + 1) + "-" +
        date.dateInstance.getFullYear()).remove();
      $("#price").text("$" + (2 * $("#dates *").length));
    });
  </script>
</x-layout>