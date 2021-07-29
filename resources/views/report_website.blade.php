<x-layout title="{{ $page }}">
  <h4><a href="{{ url('report_website', $website->id) }}">Report Website</a></h4>
  <x-alert />
  <form action="{{ url('report_website', $website->id) }}" method="POST">
    @csrf
    <div class="row mb-3">
      <div class="col-3"><strong>URL you are reporting</strong></div>
      <div class="col">{{ $website->url }}</div>
    </div>
    <div class="row mb-3">
      <div class="col-3"><strong>Report Reason</strong> (Optional)</div>
      <div class="col">
        <input type="text" name="report_reason" id="report_reason" class="form-control" />
      </div>
    </div>
    <div class="row mb-3">
      <div class="col offset-3">
        <button type="submit" class="btn btn-primary">Report</button>
      </div>
    </div>
  </form>
</x-layout>
