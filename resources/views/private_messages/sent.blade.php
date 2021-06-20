@php
use App\Models\User;
use Carbon\Carbon;
@endphp
<x-layout title="{{ $page }}">
  <h4><a href="{{ url('private_messages') }}">Private Messages</a></h4>
  <x-alert />
  <x-private_message_top />
  @if(count($private_messages_sent))
  <form action="{{ url('private_messages/update') }}" method="POST">
    @csrf
    <div class="d-flex justify-content-between align-items-center mb-3" style="min-height: 55px;">
      <p class="mb-0"><strong>Viewing:</strong> {{ $private_messages_sent->firstItem() }} to {{ $private_messages_sent->lastItem() }} of {{ count(Auth::user()->private_messages_sent) }} total messages</p>
      <div>
        <button type="submit" name="action" value="delete_from_sender_selected" id="delete-selected" class="btn btn-outline-danger d-none">Delete Selected</button>
      </div>
    </div>
    <table class="table table-bordered align-middle">
      <thead>
        <tr class="bg-light">
          <th scope="col" style="width: 1%;">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="toggle-all-private-messages-sent">
            </div>
          </th>
          <th scope="col" style="width: 20%;">To</th>
          <th scope="col" style="width: 45%;">Subject</th>
          <th scope="col" style="width: 20%;">Sent</th>
          <th scope="col" style="width: 10%;">Read</th>
          <th scope="col" style="width: 4%;">Actions</th>
      </thead>
      <tbody>
        @foreach ($private_messages_sent as $pm)
        <tr>
          <td>
            <div class="form-check">
              <input class="form-check-input pm" type="checkbox" value="{{ $pm->id }}" name="selected_pms[{{ $pm->id }}]">
            </div>
          </td>
          <td>{{ User::where('id', $pm->to_id)->value('username') }}</td>
          <td><a href="{{ url('private_messages', $pm->id) }}">{{ $pm->subject }}</a></td>
          <td>{{ Carbon::createFromFormat('Y-m-d H:i:s', $pm->created_at)->format('F j, Y, g:i a') }}</td>
          <td>{{ $pm->read == 0 ? "Unread" : "Read" }}</td>
          <td>
            <a href="{{ url('private_messages/delete_from_sender', $pm->id) }}" class="btn btn-outline-danger me-2"><i class="bi-trash"></i></button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </form>
  {{ $private_messages_sent->links() }}
  @else
  <p class="alert alert-info">Your sent folder is empty.</p>
  @endif
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script>
    $(function() {
      $("#toggle-all-private-messages-sent").change(function() {
        if (this.checked) {
          $(".pm").each(function() {
            this.checked = true;
            $("#delete-selected").removeClass("d-none");
          })
        } else {
          $(".pm").each(function() {
            this.checked = false;
            $("#delete-selected").addClass("d-none");
          })
        }
      });

      $(".pm").click(function() {
        if ($(".pm:checked").length > 0) {
          $("#delete-selected").removeClass("d-none");
        } else {
          $("#delete-selected").addClass("d-none");
        }
        if ($(this).is(":checked")) {
          var isAllChecked = 1;
          $(".pm").each(function() {
            if (!this.checked) {
              isAllChecked = 0;
            }
          })
          if (isAllChecked) {
            $("#toggle-all-private-messages-sent").prop("checked", true);
          }
        } else {
          $("#toggle-all-private-messages-sent").prop("checked", false);
        }
      });
    });
  </script>
</x-layout>