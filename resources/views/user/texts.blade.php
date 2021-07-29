<x-layout title="{{ $page }}">
  <h4><a href="{{ url('texts') }}">Text Ads</a> ({{ count(Auth::user()->texts) }}/{{ Auth::user()->type->max_texts }})</h4>
  <x-alert />
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>You have <strong>{{ number_format(Auth::user()->text_imps) }}</strong> text ad impressions.</div>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTextAdModal">
      <i class="bi-plus"></i> Add New Text Ad
    </button>
  </div>
  @if (count($texts))
  <form action="{{ url('texts/update') }}" method="POST">
    @csrf
    <div class="d-flex align-items-center justify-content-center mb-3 alert alert-info">
      <div>Evenly distribute </div>
      <div>
        <input type="number" name="imps_to_distribute" min="0" max="{{ Auth::user()->text_imps }}" style="width: 7rem;" class="form-control mx-2">
      </div>
      <div>impressions to my active text ads.</div>
      <button type="submit" class="btn btn-dark ms-2" name="action" value="distribute_imps">Distribute</button>
    </div>
    <div style="min-height: 55px;">
      <button type="submit" class="btn btn-danger mb-3 d-none" id="delete-selected" onclick="return confirm('Are you sure?');" name="action" value="delete_selected"><i class="bi-trash"></i> Delete Selected</button>
      <button type="submit" class="btn btn-secondary mb-3 d-none" id="pause-selected" name="action" value="pause_selected"><i class="bi-pause"></i> Pause Selected</button>
      <button type="submit" class="btn btn-secondary mb-3 d-none" id="activate-selected" name="action" value="activate_selected"><i class="bi-play"></i> Activate Selected</button>
    </div>
    <table class="table table-bordered align-middle">
      <tr class="bg-light">
        <th scope="col">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="toggle-all-texts">
          </div>
        </th>
        <th scope="col">Text Ad</th>
        <th scope="col">Impressions Assigned</th>
        <th scope="col">Views</th>
        <th scope="col">Clicks</th>
        <th scope="col">Status</th>
        <th scope="col">Assign Impressions</th>
        <th scope="col">Actions</th>
      </tr>
      <tbody>
        @foreach ($texts as $text)
        <tr>
          <td>
            <div class="form-check">
              <input class="form-check-input text" type="checkbox" value="{{ $text->id }}" name="selected_texts[{{ $text->id }}]">
            </div>
          </td>
          <td>

            <a href="{{ $text->target_url }}" target="_blank" class="p-2 text-center" style="
              text-decoration: none;
              color: {{ $text->text_color }};
              background-color: {{ $text->bg_color }};
              @if ($text->text_bold)
              {{ 'font-weight: bold;' }}
              @endif
              " rel="noopener noreferrer">
              {{ $text->body }}
            </a>
          </td>
          <td>{{ $text->assigned }}</td>
          <td>{{ $text->views }}</td>
          <td>{{ $text->clicks }}</td>
          <td class=" @if ($text->status == 'Active')
              {{'text-success'}}
              @elseif ($text->status == 'Suspended')
              {{'text-danger'}}
              @elseif ($text->status == 'Paused')
              {{'text-muted'}}
              @else
              {{'text-dark'}}
              @endif">{{ $text->status }}
            @if ($text->status != 'Pending' && $text->status != 'Suspended')
            @if ($text->status == 'Active')
            <p><a href="{{ url('texts/change_status', $text->id) }}">Pause</a></p>
            @else
            <p><a href="{{ url('texts/change_status', $text->id) }}">Activate</a></p>
            @endif
            @endif
          </td>
          <td>
            <input type="number" name="assign_texts[{{ $text->id }}]" class="form-control w-100" style="width: 7rem;" min="0" />
          </td>
          <td>
            <div class="d-flex">
              <a href="#" title="Edit this Text Ad" data-bs-toggle="modal" data-bs-target="#editTextAdModal" data-bs-id="{{ $text->id }}" class="btn btn-outline-primary me-2"><i class="bi-pencil-square"></i></a>
              <a href="{{ url('texts/reset', $text->id) }}" title="Reset stats" class="btn btn-outline-secondary me-2"><i class="bi-arrow-counterclockwise"></i></a>
              <a href="{{ url('texts/delete', $text->id) }}" title="Delete this text ad" onclick="return confirm('Are you sure?');" class="btn btn-outline-danger"><i class="bi-trash"></i></a>
            </div>
          </td>
        </tr>
        @endforeach
        <tr>
          <td colspan="6"></td>
          <td><button type="submit" name="action" value="assign" class="btn btn-success w-100">Assign</button></td>
          <td></td>
        </tr>
      </tbody>
    </table>
  </form>
  @else
  <p class="alert alert-info">You don't have any text ads.</p>
  @endif
  <!-- Add Text Ad Modal -->
  <div class="modal fade" id="addTextAdModal" tabindex="-1" aria-labelledby="addNewTextAd" aria-hidden="true">
    <form action="{{ url('texts') }}" method="POST">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addNewTextAd">Add New Text Ad</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            @csrf
            <div class="mb-3">
              <label for="text-body" class="form-label">Text Body</label>
              <input type="text" class="form-control" name="text_body" maxlength="50" id="text-body" value="Check this awesome website" required>
            </div>
            <div class="mb-3">
              <label for="text-target-url" class="form-label">Target URL</label>
              <input type="url" class="form-control" name="target_url" id="text-target-url" placeholder="https://www.mywebsite.com/" required>
            </div>
            <p class="mb-1">Style Options (Upgraded Members Only)</p>
            <div class="d-flex justify-content-between align-items-center p-2 border mb-3 @if (Auth::user()->type->name == 'Free') {{' bg-light'}} @endif">
              <div>
                <label for="text-color">Text Color</label>
                <input type="color" name="text_color" id="text-color" value="#FFFFFF" @if (Auth::user()->type->name == 'Free') {{'disabled'}} @endif>
              </div>
              <div>
                <label for="bg-color">Background Color</label>
                <input type="color" name="bg_color" id="bg-color" value="#1246e2" @if (Auth::user()->type->name == 'Free') {{'disabled'}} @endif>
              </div>
              <div class="form-check">
                <label for="text-bold">Bold</label>
                <input class="form-check-input" id="text-bold" value="1" type="checkbox" name="text_bold" @if (Auth::user()->type->name == 'Free') {{'disabled'}} @endif>
              </div>
            </div>
            <div class="mb-3">
              <label for="preview" class="form-label">Text Ad Preview</label>
              <div class="p-2 text-center" id="preview"></div>
            </div>
            <div class="mb-3">
              <label for="text-assign-imps" class="form-label">Impressions to Assign <small>(Optional)</small></label>
              <input type="number" class="form-control" name="imps" id="text-assign-imps" min="0" value="0" max="{{ Auth::user()->text_imps }}">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Add Text Ad</button>
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- Edit Text Ad Modal -->
  <div class="modal fade" id="editTextAdModal" tabindex="-1" aria-labelledby="editTextAd" aria-hidden="true">
    <form action="{{ url('texts') }}" id="edit-text-form" method="POST">
      @method('PUT')
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editTextAd">Edit Text Ad</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            @csrf
            <div class="mb-3">
              <label for="edit-text-body" class="form-label">Text Body</label>
              <input type="text" class="form-control" name="edit_text_body" id="edit-text-body" required>
            </div>
            <div class="mb-3">
              <label for="edit-text-target-url" class="form-label">Target URL</label>
              <input type="url" class="form-control" name="edit_target_url" id="edit-text-target-url" required>
            </div>
            <p class="mb-1">Style Options (Upgraded Members Only)</p>
            <div class="d-flex justify-content-between align-items-center p-2 border mb-3 @if (Auth::user()->type->name == 'Free') {{ ' bg-light' }} @endif">
              <div>
                <label for="edit-text-color">Text Color</label>
                <input type="color" name="edit_text_color" id="edit-text-color" @if (Auth::user()->type->name == 'Free') {{'disabled'}} @endif>
              </div>
              <div>
                <label for="edit-bg-color">Background Color</label>
                <input type="color" name="edit_bg_color" id="edit-bg-color" @if (Auth::user()->type->name == 'Free') {{'disabled'}} @endif>
              </div>
              <div class="form-check">
                <label for="edit-text-bold">Bold</label>
                <input class="form-check-input" id="edit-text-bold" type="checkbox" value="1" name="edit_text_bold" @if (Auth::user()->type->name == 'Free') {{'disabled'}} @endif>
              </div>
            </div>
            <div class="mb-3">
              <label for="edit-preview" class="form-label">Text Ad Preview</label>
              <div class="p-2 text-center" id="edit-preview"></div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update Text Ad</button>
          </div>
        </div>
      </div>
    </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script>
    $(function() {
      $("#addTextAdModal").on('show.bs.modal', function(event) {
        $("#preview").text($("#text-body").val()).css("color", $("#text-color").val()).css("background-color", $("#bg-color").val());
      });

      $("#text-body").keyup(function() {
        $("#preview").text($(this).val());
      });

      $("#text-color").change(function() {
        $("#preview").css("color", $(this).val())
      });

      $("#bg-color").change(function() {
        $("#preview").css("background-color", $(this).val())
      });

      $("#text-bold").change(function() {
        if ($(this).is(":checked")) {
          $("#preview").css("font-weight", "bold");
        } else {
          $("#preview").css("font-weight", "normal");
        }
      });

      $("#edit-text-body").keyup(function() {
        $("#edit-preview").text($(this).val());
      });

      $("#edit-text-color").change(function() {
        $("#edit-preview").css("color", $(this).val())
      });

      $("#edit-bg-color").change(function() {
        $("#edit-preview").css("background-color", $(this).val())
      });

      $("#edit-text-bold").change(function() {
        if ($(this).is(":checked")) {
          $("#edit-preview").css("font-weight", "bold");
        } else {
          $("#edit-preview").css("font-weight", "normal");
        }
      });

      $("#editTextAdModal").on('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var id = button.getAttribute("data-bs-id");
        $.get("/texts/" + id, function(response) {
          $("#edit-text-body").val(response.body);
          $("#edit-text-target-url").val(response.target_url);
          $("#edit-text-color").val(response.text_color);
          $("#edit-bg-color").val(response.bg_color);
          $("#edit-text-form").attr("action", '/texts/' + response.id);
          $("#edit-preview").text($("#edit-text-body").val()).css("color", $("#edit-text-color").val()).css("background-color", $("#edit-bg-color").val());

          if (response.text_bold) {
            $("#edit-text-bold").prop("checked", true);
          } else {
            $("#edit-text-bold").prop("checked", false);
          }

          if ($("#edit-text-bold").is(":checked")) {
            $("#edit-preview").css("font-weight", "bold");
          } else {
            $("#edit-preview").css("font-weight", "normal");
          }
        });
      });
      $("#toggle-all-texts").change(function() {
        if (this.checked) {
          $(".text").each(function() {
            this.checked = true;
            $("#delete-selected, #pause-selected, #activate-selected").removeClass("d-none");
          })
        } else {
          $(".text").each(function() {
            this.checked = false;
            $("#delete-selected, #pause-selected, #activate-selected").addClass("d-none");
          })
        }
      });

      $(".text").click(function() {
        if ($(".text:checked").length > 0) {
          $("#delete-selected, #pause-selected, #activate-selected").removeClass("d-none");
        } else {
          $("#delete-selected, #pause-selected, #activate-selected").addClass("d-none");
        }
        if ($(this).is(":checked")) {
          var isAllChecked = 1;
          $(".text").each(function() {
            if (!this.checked) {
              isAllChecked = 0;
            }
          })
          if (isAllChecked) {
            $("#toggle-all-texts").prop("checked", true);
          }
        } else {
          $("#toggle-all-texts").prop("checked", false);
        }
      });
    });

  </script>
</x-layout>
