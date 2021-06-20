@php
use App\Models\User;
use Carbon\Carbon;
@endphp
<x-layout title="{{ $page }}">
  <h4><a href="{{ url('private_messages') }}">Private Messages</a></h4>
  <x-alert />
  <x-private_message_top />
  @if(count($private_messages))
  <form action="{{ url('private_messages/update') }}" method="POST">
    @csrf
    <div class="d-flex justify-content-between align-items-center mb-3" style="min-height: 55px;">
      <p class="mb-0"><strong>Viewing:</strong> {{ $private_messages->firstItem() }} to {{ $private_messages->lastItem() }} of {{ count(Auth::user()->private_messages) }} total messages</p>
      <div>
        <button type="submit" name="action" value="move_to_trash_selected" id="move-to-trash-selected" class="btn btn-outline-secondary d-none">Move to Trash Selected</button>
        <button type="submit" name="action" value="report_selected" id="report-selected" class="btn btn-outline-primary d-none">Report Selected</button>
      </div>
    </div>
    <table class="table table-bordered align-middle">
      <thead>
        <tr class="bg-light">
          <th scope="col" style="width: 1%;">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="toggle-all-private-messages">
            </div>
          </th>
          <th scope="col" style="width: 20%;">From</th>
          <th scope="col" style="width: 55%;">Subject</th>
          <th scope="col" style="width: 20%;">Sent</th>
          <th scope="col" style="width: 4%;">Actions</th>
      </thead>
      <tbody>
        @foreach ($private_messages as $pm)
        <tr @if ($pm->read == 0)
          class="fw-bold"
          @endif>
          <td>
            <div class="form-check">
              <input class="form-check-input pm" type="checkbox" value="{{ $pm->id }}" name="selected_pms[{{ $pm->id }}]">
            </div>
          </td>
          <td>{{ User::where('id', $pm->from_id)->value('username') }}</td>
          <td><a href="{{ url('private_messages', $pm->id) }}">{{ $pm->subject }}</a></td>
          <td>{{ Carbon::createFromFormat('Y-m-d H:i:s', $pm->created_at)->format('F j, Y, g:i a') }}</td>
          <td>
            <div class="d-flex">
              <a href="{{ url('private_messages/move_trash', $pm->id) }}" class="btn btn-outline-secondary me-2"><i class="bi-trash"></i></a>
              <a href="{{ url('private_messages/report', $pm->id) }}" title="Report Spam" class="btn btn-outline-primary"><i class="bi-exclamation-triangle-fill"></i></a>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </form>
  {{ $private_messages->links() }}
  @else
  <p class="alert alert-info">Your inbox is empty.</p>
  @endif
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script>
    $(function() {
      $("#toggle-all-private-messages").change(function() {
        if (this.checked) {
          $(".pm").each(function() {
            this.checked = true;
            $("#move-to-trash-selected, #report-selected").removeClass("d-none");
          })
        } else {
          $(".pm").each(function() {
            this.checked = false;
            $("#move-to-trash-selected, #report-selected").addClass("d-none");
          })
        }
      });

      $(".pm").click(function() {
        if ($(".pm:checked").length > 0) {
          $("#move-to-trash-selected, #report-selected").removeClass("d-none");
        } else {
          $("#move-to-trash-selected, #report-selected").addClass("d-none");
        }
        if ($(this).is(":checked")) {
          var isAllChecked = 1;
          $(".pm").each(function() {
            if (!this.checked) {
              isAllChecked = 0;
            }
          })
          if (isAllChecked) {
            $("#toggle-all-private-messages").prop("checked", true);
          }
        } else {
          $("#toggle-all-private-messages").prop("checked", false);
        }
      });
    });
  </script>
</x-layout>