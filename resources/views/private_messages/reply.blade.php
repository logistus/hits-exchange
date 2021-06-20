@php
use App\Models\User;
@endphp
<x-layout title="{{ $page }}">
  <h4><a href="{{ url('private_messages') }}">Private Messages</a></h4>
  <x-alert />
  <x-private_message_top />
  <script src="https://cdn.ckeditor.com/ckeditor5/28.0.0/classic/ckeditor.js"></script>
  <h3 class="mb-3">Reply</h3>
  <form action="{{ url('private_messages') }}" method="POST">
    @csrf
    <div class="row mb-3">
      <label for="pm_to" class="col-sm-2 col-form-label">To</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name="pm_to_username" id="pm_to_username" value="{{ User::where('id', $private_message->from_id)->value('username') }}" readonly>
        <input type="hidden" class="form-control" name="pm_to" value="{{ $private_message->from_id }}">
      </div>
    </div>
    <div class="row mb-3">
      <label for="pm_subject" class="col-sm-2 col-form-label">Subject</label>
      <div class="col-sm-10">
        <input type="text" class="form-control @error('pm_subject') border border-danger @enderror" name="pm_subject" id="pm_subject" value="RE: {{ $private_message->subject }}">
      </div>
      @error('pm_subject')
      <div class="text-danger offset-sm-2">Please write a subject.</div>
      @enderror
    </div>
    <div class="row mb-3">
      <label for="pm_message" class="col-sm-2 col-form-label">Message</label>
      <div class="col-sm-10">
        <textarea id="editor" name="pm_message" class="form-control" placeholder="Enter your message here">
        <p></p>
        <p>Original Message:</p>
        <blockquote>{{ $private_message->message }}</blockquote>
        </textarea>
      </div>
      @error('pm_message')
      <div class="text-danger offset-sm-2">Please write a message.</div>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary offset-sm-2 mt-3">Send</button>
  </form>
  <script>
    ClassicEditor
      .create(document.querySelector('#editor'), {
        toolbar: ['bold', 'italic', 'link', 'blockquote', 'undo', 'redo']
      });
  </script>
</x-layout>