@php
use App\Models\User;
use Carbon\Carbon;
@endphp
<x-layout title="{{ $page }}">
  <h4><a href="{{ url('private_messages') }}">Private Messages</a></h4>
  <x-alert />
  <x-private_message_top />
  <h3>Subject: {{ $private_message->subject }} <span class="fs-5 fw-light"> (
      @if ($private_message->from_id == Auth::id())
      Sent
      @else
      {{ $private_message->folder_receiver }}
      @endif
      )</span></h3>
  <div class="d-flex justify-content-between">
    <div>From:
      <img src="{{ User::generate_gravatar($private_message->from_id) }}" alt="{{ User::where('id', $private_message->from_id)->value('username') }}" height="24" class="rounded-circle ms-1">
      <a href="#">{{ User::where('id', $private_message->from_id)->value('username') }}</a>
    </div>
    <div>To:
      <img src="{{ User::generate_gravatar($private_message->to_id) }}" alt="{{ User::where('id', $private_message->to_id)->value('username') }}" height="24" class="rounded-circle ms-1">
      <a href="#">{{ User::where('id', $private_message->to_id)->value('username') }}</a>
    </div>
    <p class="text-muted">{{ Carbon::createFromFormat('Y-m-d H:i:s', $private_message->created_at)->format('F j, Y, g:i a') }}</p>
  </div>
  <div class="bg-light p-3 mt-3 pb-0 message">{!! $private_message->message !!}</div>
  <div class="mt-2 d-flex">
    @if ($private_message->from_id != Auth::id())
    @if ($private_message->folder_receiver != 'Trash')
    <a href="{{ url('private_messages/reply', $private_message->id) }}" class="btn btn-outline-secondary me-2"><i class="bi-reply"></i> Reply</a>
    <a href="{{ url('private_messages/move_trash', $private_message->id) }}" class="btn btn-outline-secondary me-2"><i class="bi-trash"></i> Move Trash</a>
    @else
    <a href="{{ url('private_messages/move_inbox', $private_message->id) }}" class="btn btn-outline-secondary me-2"><i class="bi-arrow-left"></i> Move Inbox</a>
    @endif
    <a href="{{ url('private_messages/report', $private_message->id) }}" class="btn btn-outline-secondary me-2"><i class="bi-exclamation-triangle-fill"></i> Report</a>
    @endif
    @if ($private_message->from_id == Auth::id())
    <a href="{{ url('private_messages/delete_from_sender', $private_message->id) }}" class="btn btn-outline-danger me-2"><i class="bi-trash"></i> Delete</a>
    @endif
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script>
    $(function() {
      $(".message a").attr("target", "_blank").attr("rel", "noopener noreferrrer");
    });
  </script>
</x-layout>