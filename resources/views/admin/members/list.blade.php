@php
use \App\Models\User;
use \App\Models\UserType;
use \Carbon\Carbon;

$sort = (request()->get('sort') == 'desc' || request()->get('sort') == '') ? 'asc' : 'desc';
$sort_icon = (request()->get('sort') == 'desc' || request()->get('sort') == '') ? 'down' : 'up';
@endphp

@extends('admin.layout')

@section('page')
List Members
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
    <a class="btn btn-info" href="{{ url('admin/members/list') }}"><i class="fas fa-reset"></i> Reset Filters</a>
  </div>
</div>
@if (
request()->get('filterByUsername') != '' ||
request()->get('filterByEmail') ||
request()->get('filterByUserType') ||
request()->get('filterByVerified') ||
request()->get('filterByUpline') ||
request()->get('filterByStatus') ||
request()->get('filterByNoUpline')
)
<div class="px-1 py-3 d-flex"><span class="mr-3">Filters:</span>
  @if (request()->get('filterByUsername') != '')
  <h5 class="mr-2 mb-0"><span class="badge badge-info">Username: {{ request()->get('filterByUsername') }}</span></h5>
  @endif
  @if (request()->get('filterByEmail') != '')
  <h5 class="mr-2 mb-0"><span class="badge badge-info">Email: {{ request()->get('filterByEmail') }}</span></h5>
  @endif
  @if (request()->get('filterByUserType') != '')
  <h5 class="mr-2 mb-0"><span class="badge badge-info">User Type: {{ UserType::where('id', request()->get('filterByUserType'))->value('name') }}</span></h5>
  @endif
  @if (request()->get('filterByVerified') != '')
  <h5 class="mr-2 mb-0"><span class="badge badge-info">Verified: {{ request()->get('filterByVerified') }}</span></h5>
  @endif
  @if (request()->get('filterByUpline') != '')
  <h5 class="mr-2 mb-0"><span class="badge badge-info">Upline: {{ request()->get('filterByUpline') }}</span></h5>
  @endif
  @if (request()->get('filterByStatus') != '')
  <h5 class="mr-2 mb-0"><span class="badge badge-info">Status: {{ request()->get('filterByStatus') }}</span></h5>
  @endif
  @if (request()->get('filterByNoUpline') != '')
  <h5 class="mr-2 mb-0"><span class="badge badge-info">Members without upline</span></h5>
  @endif
</div>
@endif
@if (count($users))
<p class="mb-0 px-1 py-3"><strong>Viewing:</strong> {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ count($users) }} total users</p>
<table class="table table-bordered table-hover table-head-fixed">
  <thead>
    <tr>
      <th scope="col">Gravatar</th>
      <th scope="col">
        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'username', 'sort' => $sort]) }}">Username</a>
        @if (request()->get('sort_by') == "username")
        <i class="fas fa-chevron-{{ $sort_icon }}"></i>
        @endif
      </th>
      <th scope="col">
        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'email', 'sort' => $sort]) }}">Email</a>
        @if (request()->get('sort_by') == "email")
        <i class="fas fa-chevron-{{ $sort_icon }}"></i>
        @endif
      </th>
      <th scope="col">
        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'credits', 'sort' => $sort]) }}">Credits</a>
        @if (request()->get('sort_by') == "credits")
        <i class="fas fa-chevron-{{ $sort_icon }}"></i>
        @endif
      </th>
      <th scope="col">Type</th>
      <th scope="col">Referrals</th>
      <th scope="col">Upline</th>
      <th scope="col">Verified</th>
      <th scope="col">
        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'join_date', 'sort' => $sort]) }}">Join Date</a>
        @if (request()->get('sort_by') == "join_date" || request()->get('sort_by') == '')
        <i class="fas fa-chevron-{{ $sort_icon }}"></i>
        @endif
      </th>
      <th scope="col">
        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'last_login', 'sort' => $sort]) }}">Last Login</a>
        @if (request()->get('sort_by') == "last_login")
        <i class="fas fa-chevron-{{ $sort_icon }}"></i>
        @endif
      </th>
      <th scope="col">Status</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody class="bg-light">
    @foreach($users as $user)
    <tr>
      <td><img src="{{ $user->gravatar() }}" alt="{{ $user->username }}" height="48" /></td>
      <td>{{ $user->username }}</td>
      <td>{{ $user->email }}</td>
      <td>{{ $user->credits }}</td>
      <td>{{ $user->type->name }}</td>
      <td>{{ count($user->referrals) }}</td>
      <td>{{ User::where('id', $user->upline)->value('username') }}</td>
      <td>{!! $user->email_verified_at != "" ? "<span class='text-success font-weight-bold'>Yes</span>" : "<span class='text-danger font-weight-bold'>No</span>" !!}</td>
      <td>{{ Carbon::createFromFormat('Y-m-d H:i:s', $user->join_date)->format('j.m.Y') }}</td>
      <td>{{ $user->last_login ? Carbon::createFromFormat('Y-m-d H:i:s', $user->last_login)->format('j.m.Y') : "Never" }}</td>
      <td><span class='text-{{ $user->status == "Active" ? "success" : "danger" }} font-weight-bold'>{{ $user->status }}</span></td>
      <td>
        <div class="btn-group" role="group" aria-label="Manage Member">
          <button type="button" class="btn btn-sm btn-primary" title="Edit Member"><i class="fas fa-edit"></i></button>
          <button type="button" class="btn btn-sm btn-danger" title="Delete Member"><i class="fas fa-trash"></i></button>
          <button type="button" class="btn btn-sm btn-secondary" title="Suspend Member"><i class="fas fa-ban"></i></button>
        </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
{{ $users->links() }}
@else
<p>No user found.</p>
@endif
<!-- Fİlters Section -->
<form style="display: none; position: absolute; top: 56.8px; right: 0; width: 250; background-color: lightgray; z-index: 999; padding: 10px 20px; height: calc(100vh - 56.8px); overflow: auto;" id="addFilters" action="{{ url('admin/members/list') }}" method="GET">
  <div class="form-group">
    <label for="filterByUsername">By Username</label>
    <input type="text" class="form-control" value="{{ request()->get('filterByUsername') }}" id="filterByUsername" name="filterByUsername" aria-describedby="Filter by username">
  </div>
  <div class="form-group">
    <label for="filterByEmail">By Email</label>
    <input type="email" class="form-control" value="{{ request()->get('filterByEmail') }}" id="filterByEmail" name="filterByEmail" aria-describedby="Filter by email">
  </div>
  <div class="form-group">
    <label for="filterByUserType">By User Type</label>
    <select class="custom-select" id="filterByUserType" name="filterByUserType">
      <option value="" selected>Select</option>
      @foreach ($user_types as $user_type)
      <option value="{{ $user_type->id }}" {{ request()->get('filterByUserType') == $user_type->id ? "selected" : "" }}>{{ $user_type->name }}</option>
      @endforeach
    </select>
  </div>
  <div class="form-group">
    <label for="filterByVerified">By Verified</label>
    <select class="custom-select" id="filterByVerified" name="filterByVerified">
      <option value="" selected>Select</option>
      <option value="Yes" {{ request()->get('filterByVerified') === "Yes" ? "selected" : "" }}>Yes</option>
      <option value="No" {{ request()->get('filterByVerified') === "No" ? "selected" : "" }}>No</option>
    </select>
  </div>
  <div class="form-group">
    <label for="filterByUpline">By Upline</label>
    <input type="text" class="form-control" id="filterByUpline" value="{{ request()->get('filterByUpline') }}" name="filterByUpline" aria-describedby="Filter by upline">
  </div>
  <div class="form-group">
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" id="filterByNoUpline" name="filterByNoUpline" {{ request()->get('filterByNoUpline') ? "checked" : "" }}>
      <label class="custom-control-label" for="filterByNoUpline">Members without upline</label>
    </div>
  </div>
  <div class="form-group">
    <label for="filterByStatus">By Status</label>
    <select class="custom-select" id="filterByStatus" name="filterByStatus">
      <option value="" selected>Select</option>
      <option value="Active" {{ request()->get('filterByStatus') === "Active" ? "selected" : "" }}>Active</option>
      <option value="Suspended" {{ request()->get('filterByStatus') === "Suspended" ? "selected" : "" }}>Suspended</option>
    </select>
  </div>
  <button class="btn btn-primary" id="applyFilters" type="submit">Apply</button>
  <a class="btn btn-info" id="resetFilters" href="{{ url('admin/members/list') }}">Reset</a>
  <button class="btn btn-secondary" id="cancelFilters" type="button">Close</button>
</form>
@section('scripts') <script>
  $(function() {
    $("#filtersBtn").click(function(e) {
      $("#addFilters").css("display", "block");
      console.log(1);
    });
    $("#cancelFilters").click(function(e) {
      $("#addFilters").css("display", "none");
    });
  });
</script>
@endsection
@endsection