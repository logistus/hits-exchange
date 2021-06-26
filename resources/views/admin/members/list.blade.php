@php
use \App\Models\User;

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
  <button class="btn btn-secondary" id="filtersBtn"><i class="fas fa-filter"></i> Add Filters</button>
</div>
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
      <td>{{ $user->join_date }}</td>
      <td>{{ $user->last_login ? $user->last_login : "Never" }}</td>
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
      <option value="0">No</option>
      <option value="1">Yes</option>
    </select>
  </div>
  <div class="form-group">
    <label for="filterByUpline">By Upline</label>
    <input type="text" class="form-control" id="filterByUpline" name="filterByUpline" aria-describedby="Filter by upline">
  </div>
  <button class="btn btn-primary" id="applyFilters" type="submit">Apply</button>
  <button class="btn btn-secondary" id="cancelFilters">Cancel</button>
</form>
@section('scripts')
<script>
  $(function() {
    $("#filtersBtn").click(function(e) {
      $("#addFilters").css("display", "block");
    });
    $("#cancelFilters").click(function(e) {
      $("#addFilters").css("display", "none");

    });
  });
</script>
@endsection
@endsection