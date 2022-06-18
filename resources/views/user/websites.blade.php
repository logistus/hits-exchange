<x-layout title="{{ $page }}">
  <link rel="stylesheet" href="{{ asset('css/Chart.min.css') }}">
  <h4><a href="{{ url('websites') }}">Websites</a> ({{ count(Auth::user()->websites) }}/{{ Auth::user()->type->max_websites }})</h4>
  <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center mb-3">
    <div>
      You have <strong>{{ number_format(Auth::user()->credits, 2, '.', '') }}</strong> credits.
    </div>
    <div>
      <button type="button" class="btn btn-primary mt-sm-0 mt-3" data-bs-toggle="modal" data-bs-target="#addWebsiteModal">
        <i class="bi-plus"></i> Add New Website
      </button>
      <a href="{{ url('buy/credits') }}" class="btn btn-success"> <i class="bi bi-cart"></i> Buy Credits</a>
    </div>
  </div>
  <x-alert />
  @if (count($websites))
  <form action="{{ url('websites/update') }}" method="POST">
    @csrf
    <div>
      <a href="#" data-bs-toggle="modal" data-bs-target="#distributeCreditsModal" class="btn btn-dark mb-3">Distribute Credits</a>
      <button type="submit" class="btn btn-danger mb-3 d-none" id="delete-selected" onclick="return confirm('Are you sure?');" name="action" value="delete_selected"><i class="bi-trash"></i> Delete Selected</button>
      <button type="submit" class="btn btn-secondary mb-3 d-none" id="pause-selected" name="action" value="pause_selected"><i class="bi-pause"></i> Pause Selected</button>
      <button type="submit" class="btn btn-secondary mb-3 d-none" id="activate-selected" name="action" value="activate_selected"><i class="bi-play"></i> Activate Selected</button>
    </div>
    <table class="table align-middle">
      <tr class="bg-light">
        <th scope="col">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="toggle-all-websites">
          </div>
        </th>
        <th scope="col">Website</th>
        <th scope="col">Actions</th>
        <th scope="col">Balance</th>
        <th scope="col">Status</th>
        <th scope="col">Assign credits</th>
      </tr>
      <tbody>
        @foreach($websites as $website)
        <tr>
          <td>
            <div class="form-check">
              <input class="form-check-input website" type="checkbox" value="{{ $website->id }}" name="selected_websites[{{ $website->id }}]">
            </div>
          </td>
          <td>
            <a href="{{ $website->url }}" data-bs-toggle="tooltip" data-bs-placement="top" target="_blank" title="{{ $website->url }}" rel="noopener noreferrer">{{ $website->title }}</a>
          </td>
          <td>
            <div class="d-flex">
              <div data-bs-toggle="tooltip" data-bs-placement="top" title="Website Stats">
                <a href="#" data-bs-toggle="modal" data-bs-target="#statsWebsiteModal" data-bs-id="{{ $website->id }}" class="btn btn-outline-secondary me-2"><i class="bi-bar-chart-line"></i></a>
              </div>
              <div data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Website">
                <a href="#" data-bs-toggle="modal" data-bs-target="#editWebsiteModal" data-bs-id="{{ $website->id }}" class="btn btn-outline-secondary me-2"><i class="bi-pencil-square"></i></a>
              </div>
            </div>
          </td>
          <td>{{ round($website->assigned) }}</td>
          {{-- <td>{{ $website->views }}</td>
          <td>{{ $website->views_today }}</td>
          <td>{{ $website->max_daily_views == 0 ? 'Unlimited' : $website->max_daily_views }}</td> --}}
          <td class="@if ($website->status == 'Active')
          {{'text-success'}}
          @elseif ($website->status == 'Suspended')
          {{'text-danger'}}
          @elseif ($website->status == 'Paused')
          {{'text-muted'}}
          @else
          {{'text-dark'}}
          @endif">{{ $website->status }}
            @if ($website->status != 'Pending' && $website->status != 'Suspended')
            @if ($website->status == 'Active')
            <p><a href="{{ url('websites/change_status', $website->id) }}">Pause</a></p>
            @else
            <p><a href="{{ url('websites/change_status', $website->id) }}">Activate</a></p>
            @endif
            @endif
            @if ($website->status == 'Pending')
            <p><a href="{{ url('websites/check_website/' . $website->id) }}">Check</a></p>
            @endif
          </td>
          <td>
            <input type="number" name="assign_websites[{{ $website->id }}]" class="form-control" style="width: 7rem;" min="0" {{ $website->status == 'Suspended' || Auth::user()->credits < 1 ? "disabled" : "" }} />
          </td>
        </tr>
        @endforeach
        <tr>
          <td colspan="3"></td>
          <td class="d-grid"><button type="button" data-bs-toggle="modal" data-bs-target="#transferCreditsModal" class="btn btn-outline-secondary">Transfer</button></td>
          <td></td>
          <td class="d-grid"><button type="submit" name="action" value="assign" class="btn btn-outline-secondary" style="width: 7rem;">Assign</button></td>
        </tr>
      </tbody>
    </table>
  </form>
  @else
  <p class="alert alert-info">You don't have any websites.</p>
  @endif

  <!-- Distribute Credits Modal -->
  <div class="modal fade" id="distributeCreditsModal" tabindex="-1" aria-labelledby="distributeCredits" aria-hidden="true">
    <form action="{{ url('websites/update') }}" method="POST">
      @csrf
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="distributeCredits">Distribute Credits</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="credits_to_distribute" class="form-label">Credits Amount</label>
              <input type="number" class="form-control" name="credits_to_distribute" id="credits_to_distribute" min="0" max="{{ Auth::user()->credits }}" value="0" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="action" value="distribute_credits">Distribute</button>
          </div>
        </div>
      </div>
    </form>
  </div>

  <!-- Transfer Credits Modal -->
  <div class="modal fade" id="transferCreditsModal" tabindex="-1" aria-labelledby="transferCredits" aria-hidden="true">
    <form action="{{ url('websites/update') }}" method="POST">
      @csrf
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addNewWebsite">Transfer Credits</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="credits_to_transfer" class="form-label">Credits Amount</label>
              <input type="number" class="form-control" name="credits_to_transfer" id="credits_to_transfer" min="0" value="0" required>
            </div>
            <div class="mb-3">
              <label for="transfer_from" class="form-label">Transfer From</label>
              <select name="transfer_from" id="transfer_from" class="form-select">
                @foreach ($websites as $website)
                <option value="{{ $website->id }}">{{ $website->title }} ({{ $website->assigned }})</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label for="transfer_to" class="form-label">Transfer To</label>
              <select name="transfer_to" id="transfer_to" class="form-select">
                @foreach ($websites as $website)
                <option value="{{ $website->id }}"><b>{{ $website->title }}</b> ({{ $website->assigned }})</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" name="action" value="transfer_credits">Transfer</button>
          </div>
        </div>
      </div>
    </form>
  </div>

  <!-- Add Website Modal -->
  <div class="modal fade" id="addWebsiteModal" tabindex="-1" aria-labelledby="addNewWebsite" aria-hidden="true">
    <form action="{{ url('websites') }}" method="POST">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addNewWebsite">Add New Website</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            @csrf
            <div class="mb-3">
              <label for="website-title" class="form-label">Website Title</label>
              <input type="text" class="form-control" name="title" id="website-title" required>
            </div>
            <div class="mb-3">
              <label for="website-url" class="form-label">Website URL <small>(<strong>Must</strong> start with https)</small></label>
              <input type="url" class="form-control" name="url" id="website-url" placeholder="https://www.mywebsite.com/" required>
            </div>
            <div class="mb-3">
              <label for="website-max-daily-views" class="form-label">Maximum Daily Views</label>
              <input type="number" class="form-control" name="max_daily_views" id="website-max-daily-views" min="0" value="0" placeholder="Maximum Daily Views" required>
              <small>Leave it 0 for unlimited daily views</small>
            </div>
            <div class="mb-3">
              <label for="website-assign-credits" class="form-label">Credits to Assign <small>(Optional)</small></label>
              <input type="number" class="form-control" name="credits" id="website-assign-credits" min="0" value="0" max="{{ Auth::user()->credits }}">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add Website</button>
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- Edit Website Modal -->
  <div class="modal fade" id="editWebsiteModal" tabindex="-1" aria-labelledby="editWebsite" aria-hidden="true">
    <form action="{{ url('websites') }}" id="edit-website-form" method="POST">
      @method('PUT')
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editWebsite">Edit Website</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            @csrf
            <div class="mb-3">
              <label for="edit-website-title" class="form-label">Website Title</label>
              <input type="text" class="form-control" name="edit_title" id="edit-website-title" required>
            </div>
            <div class="mb-3">
              <label for="website-url" class="form-label">Website URL</label>
              <input type="url" class="form-control" name="edit_url" id="edit-website-url" required>
            </div>
            <div class="mb-3">
              <label for="edit-website-max-daily-views" class="form-label">Maximum Daily Views</label>
              <input type="number" class="form-control" name="edit_max_daily_views" id="edit-website-max-daily-views" min="0" value="0" required>
              <small>Leave it 0 for unlimited daily views</small>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update Website</button>
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- Stats Website Modal -->
  <div class="modal fade" id="statsWebsiteModal" tabindex="-1" aria-labelledby="Website Statistics Modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="statWebsite">Stats</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <canvas id="stat-chart" style="max-height: 450px; max-width: 700px;"></canvas>
          <p id="statsLoading">Loading...</p>
          <div class="row mt-5">
            <div class="col">
              <div class="card text-center bg-light">
                <div class="card-body">
                  <p>Visits Today</p>
                  <img src="{{ asset('images/sand-clock.png') }}" alt="Sand Clock" class="visits-loading" />
                  <h3 id="stats-visits-today">0</h3>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card text-center bg-light">
                <div class="card-body">
                  <p>Visits Yesterday</p>
                  <img src="{{ asset('images/sand-clock.png') }}" alt="Sand Clock" class="visits-loading" />
                  <h3 id="stats-visits-yesterday">0</h3>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card text-center bg-light">
                <div class="card-body">
                  <p>Visits Last 7 Days</p>
                  <img src="{{ asset('images/sand-clock.png') }}" alt="Sand Clock" class="visits-loading" />
                  <h3 id="stats-visits-last-7-days">0</h3>
                </div>
              </div>
            </div>
            <div class="col">
              <div class="card text-center bg-light">
                <div class="card-body">
                  <p>Visits Total</p>
                  <img src="{{ asset('images/sand-clock.png') }}" alt="Sand Clock" class="visits-loading" />
                  <h3 id="stats-visits-total">0</h3>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <a href="#" id="stat-reset-link" class="btn btn-outline-secondary">Reset Stats</a>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="{{ asset('js/Chart.min.js') }}"></script>
  <script>
    $(function() {
      $("#editWebsiteModal").on('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var id = button.getAttribute("data-bs-id");
        $.get("/websites/" + id, function(response) {
          $("#edit-website-title").val(response.title);
          $("#edit-website-url").val(response.url);
          $("#edit-website-max-daily-views").val(response.max_daily_views);
          $("#edit-website-form").attr("action", '/websites/' + response.id);
        })
      });

      $("#statsWebsiteModal").on('show.bs.modal', function(event) {
        $("canvas").hide();
        $("#stats-visits-total").hide();
        $("#stats-visits-last-7-days").hide();
        $("#stats-visits-today").hide();
        $("#stats-visits-yesterday").hide();
        $(".visits-loading").show();

        var button = event.relatedTarget;
        var id = button.getAttribute("data-bs-id");
        let dates = [];
        let visits = [];
        $.get("/websites/" + id, function(response) {
          $("#statWebsite").text("Stats for " + response.title);
          $("#stat-reset-link").attr("href", "/websites/reset/" + response.id);
        });
        $.get("/websites/stats/" + id + "/14", function(stats) {
          for (let i = 0; i < stats.length; i++) {
            dates.push(stats[i].date);
            visits.push(stats[i].visits);
          }
          $("#statsLoading").hide();
          $("canvas").show();
          var ctx = document.getElementById('stat-chart');
          var myChart = new Chart(ctx, {
            type: 'line'
            , data: {
              labels: dates
              , datasets: [{
                label: 'Visits'
                , data: visits
                , borderColor: '#B0CFDA'
                , pointBackgroundColor: '#9B9797'
              , }]
            }
            , options: {
              responsive: true
              , legend: {
                display: false
              , }
              , scales: {
                yAxes: [{
                  ticks: {
                    beginAtZero: true
                  }
                }]
              }
            }
          , });
        });
        // Total Visits
        $.get("/websites/stats/" + id + "/visits/all", function(response) {
          $("#stats-visits-total").show().text(response);
          $(".visits-loading").hide();
        });
        // Visits Last 7 Days
        $.get("/websites/stats/" + id + "/visits/7", function(response) {
          $("#stats-visits-last-7-days").show().text(response);
          $(".visits-loading").hide();
        });
        // Visits Today
        $.get("/websites/stats/" + id + "/visits/today", function(response) {
          $("#stats-visits-today").show().text(response);
          $(".visits-loading").hide();
        });
        // Visits Yesterday
        $.get("/websites/stats/" + id + "/visits/yesterday", function(response) {
          $("#stats-visits-yesterday").show().text(response);
          $(".visits-loading").hide();
        });
      });

      $("#toggle-all-websites").change(function() {
        if (this.checked) {
          $(".website").each(function() {
            this.checked = true;
            $("#delete-selected, #pause-selected, #activate-selected").removeClass("d-none");
          })
        } else {
          $(".website").each(function() {
            this.checked = false;
            $("#delete-selected, #pause-selected, #activate-selected").addClass("d-none");
          })
        }
      });

      $(".website").click(function() {
        if ($(".website:checked").length > 0) {
          $("#delete-selected, #pause-selected, #activate-selected").removeClass("d-none");
        } else {
          $("#delete-selected, #pause-selected, #activate-selected").addClass("d-none");
        }
        if ($(this).is(":checked")) {
          var isAllChecked = 1;
          $(".website").each(function() {
            if (!this.checked) {
              isAllChecked = 0;
            }
          })
          if (isAllChecked) {
            $("#toggle-all-websites").prop("checked", true);
          }
        } else {
          $("#toggle-all-websites").prop("checked", false);
        }
      });
    });

  </script>
</x-layout>
