@php
use \App\Models\Website;
use \App\Models\User;
use \Carbon\Carbon;

$sort = (request()->get('sort') == 'desc' || request()->get('sort') == '') ? 'asc' : 'desc';
$sort_icon = (request()->get('sort') == 'desc' || request()->get('sort') == '') ? 'down' : 'up';
@endphp

@extends('admin.layout')

@section('page')
List Websites
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>
  <li class="breadcrumb-item active">List Websites</li>
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
    <a class="btn btn-info" href="{{ url('admin/websites/list') }}"><i class="fas fa-reset"></i> Reset Filters</a>
  </div>
</div>
@if (
request()->get('filterByUsername') != '' ||
request()->get('filterByUrl') ||
request()->get('filterByStatus')
)
<div class="px-1 py-3 d-flex"><span class="mr-3">Filters:</span>
  @if (request()->get('filterByUsername') != '')
  <h5 class="mr-2 mb-0"><span class="badge badge-info">Username: {{ request()->get('filterByUsername') }}</span></h5>
  @endif
  @if (request()->get('filterByUrl') != '')
  <h5 class="mr-2 mb-0"><span class="badge badge-info">URL: {{ request()->get('filterByUrl') }}</span></h5>
  @endif
  @if (request()->get('filterByStatus') != '')
  <h5 class="mr-2 mb-0"><span class="badge badge-info">Status: {{ request()->get('filterByStatus') }}</span></h5>
  @endif
</div>
@endif
@if (count($websites))
<p class="mb-0 px-1 py-3"><strong>Viewing:</strong> {{ $websites->firstItem() }} to {{ $websites->lastItem() }} of {{ count(Website::all()) }} total websites</p>
<form action="{{ url('admin/websites/actions') }}" method="POST">
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
            <input class="form-check-input position-static" type="checkbox" id="toggle-all-websites" aria-label="Toggle all websites">
          </div>
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'url', 'sort' => $sort]) }}">URL</a>
          @if (request()->get('sort_by') == "url")
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
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'views_today', 'sort' => $sort]) }}">Views Today</a>
          @if (request()->get('sort_by') == "views_today" || request()->get('sort_by') == '')
          <i class="fas fa-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'max_daily_views', 'sort' => $sort]) }}">Max. Daily Views</a>
          @if (request()->get('sort_by') == "max_daily_views")
          <i class="fas fa-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
        <th scope="col">
          <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'auto_assign', 'sort' => $sort]) }}">Auto Assign</a>
          @if (request()->get('sort_by') == "auto_assign")
          <i class="fas fa-chevron-{{ $sort_icon }}"></i>
          @endif
        </th>
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
      @foreach($websites as $website)
      <tr>
        <td>
          <div class="form-check">
            <input class="form-check-input website" type="checkbox" value="{{ $website->id }}" name="selected_websites[{{ $website->id }}]">
          </div>
        </td>
        <td><a href="{{ $website->url }}" target="_blank" rel="noopener noreferrer">{{ $website->url }}</a></td>
        <td>{{ User::where('id', $website->user_id)->value('username') }}</td>
        <td>{{ $website->assigned }}</td>
        <td>{{ $website->views }}</td>
        <td>{{ $website->views_today }}</td>
        <td>{{ $website->max_daily_views }}</td>
        <td>{{ $website->auto_assign }}%</td>
        <td><span @if ($website->status == "Active")
            class='text-success font-weight-bold'
            @elseif ($website->status == "Pending")
            class='text-muted font-weight-bold'
            @elseif ($website->status == "Paused")
            class='text-black font-weight-bold'
            @else
            class='text-danger font-weight-bold'
            @endif>{{ $website->status }}</span></td>
        <td>
          <div class="btn-group" role="group" aria-label="Manage Website">
            <a class="btn btn-sm btn-primary" href="{{ url('admin/websites/edit', $website->id) }}" title="Edit Website"><i class="fas fa-edit"></i></a>
            @if ($website->status == "Suspended")
            <a href="{{ url('admin/websites/activate', $website->id) }}" class="btn btn-sm btn-info" title="Activate Website"><i class="fas fa-check"></i></a>
            @else
            <a href="{{ url('admin/websites/suspend', $website->id) }}" class="btn btn-sm btn-secondary" title="Suspend Website"><i class="fas fa-ban"></i></a>
            @endif
            @if ($website->status == "Pending")
            <a href="{{ url('admin/websites/activate', $website->id) }}" class="btn btn-sm btn-info" title="Activate Website"><i class="fas fa-check-double"></i></a>
            @endif
            @if ($website->status == "Paused")
            <a href="{{ url('admin/websites/activate', $website->id) }}" class="btn btn-sm btn-info" title="Activate Website"><i class="fas fa-play"></i></a>
            @endif
            @if ($website->status == "Active")
            <a href="{{ url('admin/websites/pause', $website->id) }}" class="btn btn-sm btn-info" title="Pause Website"><i class="fas fa-pause"></i></a>
            @endif
            <a href="{{ url('admin/websites/delete', $website->id) }}" onclick="return confirm('Are you sure?');" class="btn btn-sm btn-danger" title="Delete Website"><i class="fas fa-trash"></i></a>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</form>
{{ $websites->links() }}
@else
<p>No websites found.</p>
@endif
<!-- Fİlters Section -->
<form style="display: none; position: absolute; top: 56.8px; right: 0; width: 250; background-color: lightgray; z-index: 999; padding: 10px 20px; height: calc(100vh - 56.8px); overflow: auto;" id="addFilters" action="{{ url('admin/websites/list') }}" method="GET">
  <div class="form-group">
    <label for="filterByUsername">By Username</label>
    <input type="text" class="form-control" value="{{ request()->get('filterByUsername') }}" id="filterByUsername" name="filterByUsername" aria-describedby="Filter by username">
  </div>
  <div class="form-group">
    <label for="filterByUrl">By URL</label>
    <input type="text" class="form-control" value="{{ request()->get('filterByUrl') }}" id="filterByUrl" name="filterByUrl" aria-describedby="Filter by URL">
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

    $("#toggle-all-websites").change(function() {
      if (this.checked) {
        $(".website").each(function() {
          this.checked = true;
          $("#delete-selected, #suspend-selected, #activate-selected").removeClass("d-none");
        })
      } else {
        $(".website").each(function() {
          this.checked = false;
          $("#delete-selected, #suspend-selected, #activate-selected").addClass("d-none");
        })
      }
    });

    $(".website").click(function() {
      if ($(".website:checked").length > 0) {
        $("#delete-selected, #suspend-selected, #activate-selected").removeClass("d-none");
      } else {
        $("#delete-selected, #suspend-selected, #activate-selected").addClass("d-none");
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
@endsection
