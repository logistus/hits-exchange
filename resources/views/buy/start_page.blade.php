<x-layout title="{{ $page }}">
  <h4><a href="{{ url('buy/start_page') }}">Buy Start Page</a></h4>
  <p>yada yada yada</p>
  <div class="row">
    <div id="datepicker" class="col"></div>
    <div style="max-height: 257px; overflow-y:auto;" class="col me-3">
      <strong>Selected Days</strong>
      <div id="dates"></div>
    </div>
    <div id="payment" class="col">
      <strong>Price</strong>
      <div id="price" class="fs-2">$0</div>
      <form action="https://www.coinpayments.net/index.php" method="post">
        <input type="hidden" name="cmd" value="_pay_simple">
        <input type="hidden" name="reset" value="1">
        <input type="hidden" name="merchant" value="0a163329f1a618ee280c49eb1db2d9c2">
        <input type="hidden" name="item_name" value="Start Page">
        <input type="hidden" name="currency" value="USD">
        <input type="hidden" name="amountf" value="2.00000000">
        <input type="hidden" name="want_shipping" value="0">
        <input type="image" src="https://www.coinpayments.net/images/pub/buynow-wide-blue.png" alt="Buy Now with CoinPayments.net">
      </form>
    </div>
  </div>
  <div class="row mt-3">
    <div class="col">
      <h4>Your Start Pages</h4>
      @if ($user_start_pages)
      <table class="table table-bordered">
        <thead>
          <tr class="bg-light">
            <th scope="col">URL</th>
            <th scope="col">Start Date</th>
            <th scope="col">Total Views</th>
            <th scope="col">Status</th>
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
            <td>{{ $start_page->status }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
      @else
      <div class="alert alert-info">You don't have any start pages.</div>
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
      numberOfMonths: 2,
      numberOfColumns: 2,
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
      $("input[name='amountf']").val(2 * $("#dates *").length);
    });
    picker.on('multiselect.deselect', function(date) {
      $("#" + date.dateInstance.getDate() + "-" +
        (date.dateInstance.getMonth() + 1) + "-" +
        date.dateInstance.getFullYear()).remove();
      $("#price").text("$" + (2 * $("#dates *").length));
    });
  </script>
</x-layout>