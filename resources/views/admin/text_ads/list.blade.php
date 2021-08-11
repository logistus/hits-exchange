@php
use \App\Models\TextAd;
use \App\Models\User;
use \Carbon\Carbon;

$sort = (request()->get('sort') == 'desc' || request()->get('sort') == '') ? 'asc' : 'desc';
$sort_icon = (request()->get('sort') == 'desc' || request()->get('sort') == '') ? 'down' : 'up';
@endphp

@extends('admin.layout')

@section('page')
List Text Ads
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>
  <li class="breadcrumb-item active">List Text Ads</li>
</ol>
@endsection

@section('content')
<div class="d-flex justify-content-between mb-3">
  <form action="{{ url()->full() }}" class="form-inline" method="GET">
    <div class="form-group">
      <label for="per_page" class="form-label">Per Page:</label>
      <select class="custom-select form-control ml-2" id="per_page" name="per_page">
        <option value="25" {{ $per_page == 25 ? "selected" : "" }}>25</option>
        <option value="50" {{ $per_page == 50 ? "selected" : "" }}>50</option>
        <option value="100" {{ $per_page == 100 ? "selected" : "" }}>100</option>
      </select>
    </div>
    <button class="btn btn-primary ml-2">Apply</button>
  </form>
  <div>
    <button class="btn btn-secondary" id="filtersBtn"><i class="fas fa-filter"></i> Add Filters</button>
    <a class="btn btn-info" href="{{ url('admin/text_ads/list') }}"><i class="fas fa-reset"></i> Reset Filters</a>
  </div>
</div>
@if (
request()->get('filterByUsername') != '' ||
request()->get('filterByBody') ||
request()->get('filterByTargetUrl') ||
request()->get('filterByStatus')
)
<div class="px-1 py-3 d-flex"><span class="mr-3">Filters:</span>
  @if (request()->get('filterByUsername') != '')
  <h5 class="mr-2 mb-0"><span class="badge badge-info">Username: {{ request()->get('filterByUsername') }}</span></h5>
  @endif
  @if (request()->get('filterByBody') != '')
  <h5 class="mr-2 mb-0"><span class="badge badge-info">Body: {{ request()->get('filterByBody') }}</span></h5>
  @endif
  @if (request()->get('filterByTargetUrl') != '')
  <h5 class="mr-2 mb-0"><span class="badge badge-info">Target URL: {{ request()->get('filterByTargetUrl') }}</span></h5>
  @endif
  @if (request()->get('filterByStatus') != '')
  <h5 class="mr-2 mb-0"><span class="badge badge-info">Status: {{ request()->get('filterByStatus') }}</span></h5>
  @endif
</div>
@endif
@if (count($text_ads))
<p class="mb-0 px-1 py-3"><strong>Viewing:</strong> {{ $text_ads->firstItem() }} to {{ $text_ads->lastItem() }} of {{ count(TextAd::all()) }} total text ads</p>
<form action="{{ url('admin/text_ads/actions') }}" method="POST">
  @csrf
  <div style="min-height: 55px;">
    <button type="submit" class="btn btn-danger mb-3 d-none" id="delete-selected" onclick="return confirm('Are you sure?');" name="action" value="delete_selected">Delete Selected</button>
    <button type="submit" class="btn btn-secondary mb-3 d-none" id="suspend-selected" name="action" value="suspend_selected">Suspend Selected</button>
    <button type="submit" class="btn btn-success mb-3 d-none" id="activate-selected" name="action" value="activate_selected">Activate Selected</button>
  </div>
  <table class="table table-bordered table-hover table-head-fixed">
    <thead>
      <tr>
        <th scope="col">
          <div class="form-check">
            <input class="form-check-input position-static" type="checkbox" id="toggle-all-text-ads" aria-label="Toggle all text ads">
          </div>
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'id', 'sort' => $sort]) }}">ID</a>
          @if (request()->get('sort_by') == "id" || request()->get('sort_by') == '')
          <i class="fas fa-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">Preview</th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'body', 'sort' => $sort]) }}">Body</a>
          @if (request()->get('sort_by') == "body")
          <i class="fas fa-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'target_url', 'sort' => $sort]) }}">Target URL</a>
          @if (request()->get('sort_by') == "target_url")
          <i class="fas fa-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'user_id', 'sort' => $sort]) }}">Username</a>
          @if (request()->get('sort_by') == "user_id")
          <i class="fas fa-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'assigned', 'sort' => $sort]) }}">Assigned</a>
          @if (request()->get('sort_by') == "assigned")
          <i class="fas fa-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'views', 'sort' => $sort]) }}">Views</a>
          @if (request()->get('sort_by') == "views")
          <i class="fas fa-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'clicks', 'sort' => $sort]) }}">Clicks</a>
          @if (request()->get('sort_by') == "clicks")
          <i class="fas fa-chevron-{{ $sort_icon }}"></i>
          @endif
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'status', 'sort' => $sort]) }}">Status</a>
          @if (request()->get('sort_by') == "status")
          <i class="fas fa-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody class="bg-light">
      @foreach($text_ads as $text_ad)
      <tr>
        <td>
          <div class="form-check">
            <input class="form-check-input text_ad" type="checkbox" value="{{ $text_ad->id }}" name="selected_banners[{{ $text_ad->id }}]">
          </div>
        </td>
        <td>{{ $text_ad->id }}</td>
        <td>
          <span class="p-2 text-center" style="
              text-decoration: none;
              color: {{ $text_ad->text_color }};
              background-color: {{ $text_ad->bg_color }};
              @if ($text_ad->text_bold)
              {{ 'font-weight: bold;' }}
              @endif
              " rel="noopener noreferrer">
            {{ $text_ad->body }}
          </span>
        </td>
        <td>{{ $text_ad->body }}</td>
        <td>
          <a href="{{ $text_ad->target_url }}" target="_blank" class="d-block" rel="noopener noreferrer">{{ $text_ad->target_url }}</a>
        </td>
        <td>{{ User::where('id', $text_ad->user_id)->value('username') }}</td>
        <td>{{ $text_ad->assigned }}</td>
        <td>{{ $text_ad->views }}</td>
        <td>{{ $text_ad->clicks }}</td>
        <td><span @if ($text_ad->status == "Active")
            class='text-success font-weight-bold'
            @elseif ($text_ad->status == "Pending")
            class='text-muted font-weight-bold'
            @elseif ($text_ad->status == "Paused")
            class='text-black font-weight-bold'
            @else
            class='text-danger font-weight-bold'
            @endif>{{ $text_ad->status }}</span></td>
        <td>
          <div class="btn-group" role="group" aria-label="Manage Text Ad">
            <a class="btn btn-sm btn-primary" href="{{ url('admin/text_ads/edit', $text_ad->id) }}" title="Edit Text Ad"><i class="fas fa-edit"></i></a>
            @if ($text_ad->status == "Suspended")
            <a href="{{ url('admin/text_ads/activate', $text_ad->id) }}" class="btn btn-sm btn-info" title="Activate Text Ad"><i class="fas fa-check"></i></a>
            @else
            <a href="{{ url('admin/text_ads/suspend', $text_ad->id) }}" class="btn btn-sm btn-secondary" title="Suspend Text Ad"><i class="fas fa-ban"></i></a>
            @endif
            @if ($text_ad->status == "Pending")
            <a href="{{ url('admin/text_ads/activate', $text_ad->id) }}" class="btn btn-sm btn-info" title="Activate Text Ad"><i class="fas fa-check-double"></i></a>
            @endif
            @if ($text_ad->status == "Paused")
            <a href="{{ url('admin/text_ads/activate', $text_ad->id) }}" class="btn btn-sm btn-info" title="Activate Text Ad"><i class="fas fa-play"></i></a>
            @endif
            @if ($text_ad->status == "Active")
            <a href="{{ url('admin/text_ads/pause', $text_ad->id) }}" class="btn btn-sm btn-info" title="Pause Text Ad"><i class="fas fa-pause"></i></a>
            @endif
            <a href="{{ url('admin/text_ads/delete', $text_ad->id) }}" onclick="return confirm('Are you sure?');" class="btn btn-sm btn-danger" title="Delete Text Ad"><i class="fas fa-trash"></i></a>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</form>
{{ $text_ads->links() }}
@else
<p>No text ads found.</p>
@endif
<!-- Fİlters Section -->
<form style="display: none; position: absolute; top: 56.8px; right: 0; width: 250; background-color: lightgray; z-index: 999; padding: 10px 20px; height: calc(100vh - 56.8px); overflow: auto;" id="addFilters" action="{{ url('admin/text_ads/list') }}" method="GET">
  <div class="form-group">
    <label for="filterByUsername">By Username</label>
    <input type="text" class="form-control" value="{{ request()->get('filterByUsername') }}" id="filterByUsername" name="filterByUsername" aria-describedby="Filter by username">
  </div>
  <div class="form-group">
    <label for="filterByBody">By Body</label>
    <input type="text" class="form-control" value="{{ request()->get('filterByBody') }}" id="filterByBody" name="filterByBody" aria-describedby="Filter by Body">
  </div>
  <div class="form-group">
    <label for="filterByUrl">By Target URL</label>
    <input type="text" class="form-control" value="{{ request()->get('filterByTargetUrl') }}" id="filterByTargetUrl" name="filterByTargetUrl" aria-describedby="Filter by Target URL">
  </div>
  <div class="form-group">
    <label for="filterByStatus">By Status</label>
    <select class="custom-select" id="filterByStatus" name="filterByStatus">
      <option value="" selected>Select</option>
      <option value="Pending" {{ request()->get('filterByStatus') === "Pending" ? "selected" : "" }}>Pending</option>
      <option value="Paused" {{ request()->get('filterByStatus') === "Paused" ? "selected" : "" }}>Paused</option>
      <option value="Active" {{ request()->get('filterByStatus') === "Active" ? "selected" : "" }}>Active</option>
      <option value="Suspended" {{ request()->get('filterByStatus') === "Suspended" ? "selected" : "" }}>Suspended</option>
    </select>
  </div>
  <button class="btn btn-primary" id="applyFilters" type="submit">Apply</button>
  <button class="btn btn-secondary" id="cancelFilters" type="button">Close</button>
</form>
<!-- End filters section -->
@endsection

@section('scripts')
<script>
  $(function() {
    $("#filtersBtn").click(function(e) {
      $("#addFilters").css("display", "block");
    });
    $("#cancelFilters, body").click(function(e) {
      $("#addFilters").css("display", "none");
    });
    $("#cancelFilters, #addFilters, #filtersBtn, #filterByUsername, #filterByUrl, #filterByStatus").click(function(e) {
      e.stopPropagation();
    });

    $("#toggle-all-text-ads").change(function() {
      if (this.checked) {
        $(".text_ad").each(function() {
          this.checked = true;
          $("#delete-selected, #suspend-selected, #activate-selected").removeClass("d-none");
        })
      } else {
        $(".text_ad").each(function() {
          this.checked = false;
          $("#delete-selected, #suspend-selected, #activate-selected").addClass("d-none");
        })
      }
    });

    $(".text_ad").click(function() {
      if ($(".text_ad:checked").length > 0) {
        $("#delete-selected, #suspend-selected, #activate-selected").removeClass("d-none");
      } else {
        $("#delete-selected, #suspend-selected, #activate-selected").addClass("d-none");
      }
      if ($(this).is(":checked")) {
        var isAllChecked = 1;
        $(".text_ad").each(function() {
          if (!this.checked) {
            isAllChecked = 0;
          }
        })
        if (isAllChecked) {
          $("#toggle-all-text-ads").prop("checked", true);
        }
      } else {
        $("#toggle-all-text-ads").prop("checked", false);
      }
    });

  });

</script>
@endsection
