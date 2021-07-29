<x-layout title="{{ $page }}">
  <h4><a href="{{ url('square_banners') }}">Square Banners</a> ({{ count(Auth::user()->square_banners) }}/{{ Auth::user()->type->max_square_banners }})</h4>
  <x-alert />
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>You have <strong>{{ number_format(Auth::user()->square_banner_imps) }}</strong> square banner impressions.</div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSquareBannerModal">
      <i class="bi-plus"></i> Add New Square Banner
    </button>
  </div>
  @if (count($square_banners))
  <form action="{{ url('square_banners/update') }}" method="POST">
    @csrf
    <div class="d-flex align-items-center justify-content-center mb-3 alert alert-info">
      <div>Evenly distribute </div>
      <div>
        <input type="number" name="imps_to_distribute" min="0" max="{{ Auth::user()->square_banner_imps }}" style="width: 7rem;" class="form-control mx-2">
      </div>
      <div>impressions to my active banners.</div>
      <button type="submit" class="btn btn-dark ms-2" name="action" value="distribute_imps">Distribute</button>
    </div>
    <div style="min-height: 55px;">
      <button type="submit" class="btn btn-danger mb-3 d-none" id="delete-selected" onclick="return confirm('Are you sure?');" name="action" value="delete_selected"><i class="bi-trash"></i> Delete Selected</button>
      <button type="submit" class="btn btn-secondary mb-3 d-none" id="pause-selected" name="action" value="pause_selected"><i class="bi-pause"></i> Pause Selected</button>
      <button type="submit" class="btn btn-secondary mb-3 d-none" id="activate-selected" name="action" value="activate_selected"><i class="bi-play"></i> Activate Selected</button>
    </div>
    <table class="table table-bordered align-middle">
      <thead>
        <tr class="bg-light">
          <th scope="col">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="toggle-all-banners">
            </div>
          </th>
          <th scope="col">Square Banner</th>
          <th scope="col">Impressions Assigned</th>
          <th scope="col">Views</th>
          <th scope="col">Clicks</th>
          <th scope="col">Status</th>
          <th scope="col">Assign Impressions</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($square_banners as $square_banner)
        <tr>
          <td>
            <div class="form-check">
              <input class="form-check-input square_banner" type="checkbox" value="{{ $square_banner->id }}" name="selected_square_banners[{{ $square_banner->id }}]">
            </div>
          </td>
          <td>
            <a href="{{ $square_banner->target_url }}" target="_blank" rel="noopener noreferrer">
              <img src="{{ $square_banner->image_url }}" width="125" height="125" />
            </a>
          </td>
          <td>{{ $square_banner->assigned }}</td>
          <td>{{ $square_banner->views }}</td>
          <td>{{ $square_banner->clicks }}</td>
          <td class="@if ($square_banner->status == 'Active')
          {{'text-success'}}
          @elseif ($square_banner->status == 'Suspended')
          {{'text-danger'}}
          @elseif ($square_banner->status == 'Paused')
          {{'text-muted'}}
          @else
          {{'text-dark'}}
          @endif">{{ $square_banner->status }}
            @if ($square_banner->status != 'Pending' && $square_banner->status != 'Suspended')
            @if ($square_banner->status == 'Active')
            <p><a href="{{ url('square_banners/change_status', $square_banner->id) }}">Pause</a></p>
            @else
            <p><a href="{{ url('square_banners/change_status', $square_banner->id) }}">Activate</a></p>
            @endif
            @endif
          </td>
          <td>
            <input type="number" name="assign_square_banners[{{ $square_banner->id }}]" class="form-control" style="width: 7rem;" min="0" />
          </td>
          <td>
            <div class="d-flex">
              <a href="#" title="Edit this Banner" data-bs-toggle="modal" data-bs-target="#editSquareBannerModal" data-bs-id="{{ $square_banner->id }}" class="btn btn-outline-primary me-2"><i class="bi-pencil-square"></i></a>
              <a href="{{ url('square_banners/reset', $square_banner->id) }}" title="Reset stats" class="btn btn-outline-secondary me-2"><i class="bi-arrow-counterclockwise"></i></a>
              <a href="{{ url('square_banners/delete', $square_banner->id) }}" title="Delete this banner" onclick="return confirm('Are you sure?');" class="btn btn-outline-danger"><i class="bi-trash"></i></a>
            </div>
          </td>
        </tr>
        @endforeach
        <tr>
          <td colspan="6"></td>
          <td class="d-grid"><button type="submit" name="action" value="assign" class="btn btn-success">Assign</button></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </form>
  @else
  <p class="alert alert-info">You don't have any square banners.</p>
  @endif
  <!-- Add Square Banner Modal -->
  <div class="modal fade" id="addSquareBannerModal" tabindex="-1" aria-labelledby="addNewSquareBanner" aria-hidden="true">
    <form action="{{ url('square_banners') }}" method="POST">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addNewSquareBanner">Add New Square Banner</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            @csrf
            <div class="mb-3">
              <label for="banner-image-url" class="form-label">Square Banner Image URL</label>
              <input type="url" class="form-control" name="image_url" id="banner-image-url" placeholder="https://www.mywebsite.com/mybanner.gif" required>
            </div>
            <div class="mb-3">
              <label for="banner-target-url" class="form-label">Square Banner Target URL</label>
              <input type="url" class="form-control" name="target_url" id="banner-target-url" placeholder="https://www.mywebsite.com/" required>
            </div>
            <div class="mb-3">
              <label for="banner-assign-imps" class="form-label">Impressions to Assign <small>(Optional)</small></label>
              <input type="number" class="form-control" name="imps" id="banner-assign-imps" min="0" value="0" max="{{ Auth::user()->banner_imps }}">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add Square Banner</button>
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- Edit Square Banner Modal -->
  <div class="modal fade" id="editSquareBannerModal" tabindex="-1" aria-labelledby="editSquareBanner" aria-hidden="true">
    <form action="{{ url('square_banners') }}" id="edit-banner-form" method="POST">
      @method('PUT')
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editSquareBanner">Edit Square Banner</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            @csrf
            <div class="mb-3">
              <label for="edit-banner-image-url" class="form-label">Square Banner Image URL</label>
              <input type="url" class="form-control" name="edit_image_url" id="edit-banner-image-url" required>
            </div>
            <div class="mb-3">
              <label for="edit-banner-target-url" class="form-label">Square Banner Target URL</label>
              <input type="url" class="form-control" name="edit_target_url" id="edit-banner-target-url" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update Banner</button>
          </div>
        </div>
      </div>
    </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script>
    $(function() {
      $("#editSquareBannerModal").on('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var id = button.getAttribute("data-bs-id");
        $.get("/square_banners/" + id, function(response) {
          $("#edit-banner-target-url").val(response.target_url);
          $("#edit-banner-image-url").val(response.image_url);
          $("#edit-banner-form").attr("action", '/square_banners/' + response.id);
        });
      });
      $("#toggle-all-banners").change(function() {
        if (this.checked) {
          $(".square_banner").each(function() {
            this.checked = true;
            $("#delete-selected, #pause-selected, #activate-selected").removeClass("d-none");
          })
        } else {
          $(".square_banner").each(function() {
            this.checked = false;
            $("#delete-selected, #pause-selected, #activate-selected").addClass("d-none");
          })
        }
      });

      $(".square_banner").click(function() {
        if ($(".square_banner:checked").length > 0) {
          $("#delete-selected, #pause-selected, #activate-selected").removeClass("d-none");
        } else {
          $("#delete-selected, #pause-selected, #activate-selected").addClass("d-none");
        }
        if ($(this).is(":checked")) {
          var isAllChecked = 1;
          $(".square_banner").each(function() {
            if (!this.checked) {
              isAllChecked = 0;
            }
          })
          if (isAllChecked) {
            $("#toggle-all-banners").prop("checked", true);
          }
        } else {
          $("#toggle-all-banners").prop("checked", false);
        }
      });
    });

  </script>
</x-layout>
