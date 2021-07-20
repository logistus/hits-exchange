@php
use App\Models\User;
@endphp
<x-layout title="{{ $page }}">
  <h4><a href="{{ url('private_messages') }}">Private Messages</a></h4>
  <x-alert />
  <x-private_message_top />
  <script src="https://cdn.ckeditor.com/ckeditor5/28.0.0/classic/ckeditor.js"></script>
  <h3 class="mb-3">Compose</h3>
  @if (count(Auth::user()->referrals) > 0)
  <form action="{{ url('private_messages') }}" method="POST">
    @csrf
    <div class="row mb-3">
      <label for="pm_to" class="col-sm-2 col-form-label">
        To
        <i class="bi-question-circle-fill" data-bs-toggle="tooltip" data-bs-placement="top" title="You can send private messages to your referrals."></i>
      </label>
      <div class="col-sm-3">
        <select name="pm_to" id="pm_to" class="form-select @error('pm_to') border border-danger @enderror" required>
          <option>Select Referral</option>
          @foreach (Auth::user()->referrals as $referral)
          <option value="{{ $referral->id }}" {{ (old("pm_to") == $referral->id || session('pm_to') == $referral->id) ? "selected" : "" }}>{{ User::where('id', $referral->id)->value('username') }}</option>
          @endforeach
        </select>
      </div>
      @error('pm_to')
      <div class="text-danger offset-sm-2">Please select a referral.</div>
      @enderror
    </div>
    <div class="row mb-3">
      <label for="pm_subject" class="col-sm-2 col-form-label">Subject</label>
      <div class="col-sm-10">
        <input type="text" class="form-control @error('pm_subject') border border-danger @enderror" name="pm_subject" id="pm_subject" value="{{ old('pm_subject') }}">
      </div>
      @error('pm_subject')
      <div class="text-danger offset-sm-2">Please write a subject.</div>
      @enderror
    </div>
    <div class="row mb-3">
      <label for="pm_message" class="col-sm-2 col-form-label">Message</label>
      <div class="col-sm-10">
        <textarea id="editor" name="pm_message" class="form-control @error('pm_message') border border-danger @enderror" placeholder="Enter your message here">
        {{ old('pm_message') }}
        </textarea>
      </div>
      @error('pm_message')
      <div class="text-danger offset-sm-2">Please write a message.</div>
      @enderror
    </div>
    <button type="submit" class="btn btn-primary offset-sm-2 mt-3">Send</button>
  </form>
  @else
  <p>You can send private message only to your referrals. You don't have any referrals.</p>
  @endif
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script>
    ClassicEditor
      .create(document.querySelector('#editor'), {
        toolbar: ['bold', 'italic', 'link', 'blockquote', 'undo', 'redo']
        , height: 150
      , });
    // $(function() {
    //   $.get("https://ipapi.co/json/", function(result) {
    //     console.log(result);
    //   });
    //});

  </script>
</x-layout>
