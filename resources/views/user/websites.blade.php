<x-layout title="{{ $page }}">
  <h4><a href="{{ url('websites') }}">Websites</a> ({{ count(Auth::user()->websites) }}/{{ Auth::user()->type->max_websites }})</h4>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>You have <strong>{{ number_format(Auth::user()->credits, 2) }}</strong> credits.</div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWebsiteModal">
      <i class="bi-plus"></i> Add New Website
    </button>
  </div>
  @if (count($websites))
  <form action="{{ url('websites/update') }}" method="POST">
    @csrf
    <div class="d-flex align-items-center justify-content-center mb-3 alert alert-info">
      <div>Evenly distribute </div>
      <div>
        <input type="number" name="credits_to_distribute" min="0" max="{{ Auth::user()->credits }}" style="width: 7rem;" class="form-control mx-2">
      </div>
      <div>credits to my active websites.</div>
      <button type="submit" class="btn btn-dark ms-2" name="action" value="distribute_credits">Distribute</button>
    </div>
    <div style="min-height: 55px;">
      <button type="submit" class="btn btn-danger mb-3 d-none" id="delete-selected" onclick="return confirm('Are you sure?');" name="action" value="delete_selected"><i class="bi-trash"></i> Delete Selected</button>
      <button type="submit" class="btn btn-secondary mb-3 d-none" id="pause-selected" name="action" value="pause_selected"><i class="bi-pause"></i> Pause Selected</button>
      <button type="submit" class="btn btn-secondary mb-3 d-none" id="activate-selected" name="action" value="activate_selected"><i class="bi-play"></i> Activate Selected</button>
    </div>
    <x-alert />
    <table class="table table-bordered align-middle">
      <tr class="bg-light">
        <th scope="col">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="toggle-all-websites">
          </div>
        </th>
        <th scope="col">URL</th>
        <th scope="col">Credits Assigned</th>
        <th scope="col">Views</th>
        <th scope="col">Views Today</th>
        <th scope="col">Max. Daily Views</th>
        <th scope="col">Status</th>
        <th scope="col">Assign Credits</th>
        <th scope="col">Actions</th>
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
            <a href="{{ $website->url }}" target="_blank" rel="noopener noreferrer">{{ $website->url }}</a> <i class="bi-box-arrow-up-right" style="font-size: .8rem;"></i>
          </td>
          <td>{{ $website->assigned }}</td>
          <td>{{ $website->views }}</td>
          <td>{{ $website->views_today }}</td>
          <td>{{ $website->max_daily_views == 0 ? 'Unlimited' : $website->max_daily_views }}</td>
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
          </td>
          <td>
            <input type="number" name="assign_websites[{{ $website->id }}]" class="form-control" style="width: 7rem;" min="0" {{ $website->status == 'Suspended' || Auth::user()->credits < 1 ? "disabled" : "" }} />
          </td>
          <td>
            <div class="d-flex">
              <a href="#" title="Edit this URL" data-bs-toggle="modal" data-bs-target="#editWebsiteModal" data-bs-id="{{ $website->id }}" class="btn btn-outline-primary me-2"><i class="bi-pencil-square"></i></a>
              <a href="{{ url('websites/reset', $website->id) }}" title="Reset stats" class="btn btn-outline-secondary me-2"><i class="bi-arrow-counterclockwise"></i></a>
              <a href="{{ url('websites/delete', $website->id) }}" title="Delete this URL" onclick="return confirm('Are you sure?');" class="btn btn-outline-danger"><i class="bi-trash"></i></a>
            </div>
          </td>
        </tr>
        @endforeach
        <tr>
          <td colspan="7"></td>
          <td class="d-grid"><button type="submit" name="action" value="assign" class="btn btn-success">Assign</button></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </form>
  @else
  <p class="alert alert-info">You don't have any websites.</p>
  @endif
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
              <label for="website-url" class="form-label">Website URL</label>
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script>
    $(function() {
      $("#editWebsiteModal").on('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var id = button.getAttribute("data-bs-id");
        $.get("/websites/" + id, function(response) {
          $("#edit-website-url").val(response.url);
          $("#edit-website-max-daily-views").val(response.max_daily_views);
          $("#edit-website-form").attr("action", '/websites/' + response.id);
        })
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