@extends('admin.layout')

@section('page')
Add Website
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ url('admin/websites/list') }}">Websites</a></li>
  <li class="breadcrumb-item active">Add Website</li>
</ol>
@endsection

@section('content')
<div class="container">
  <form action="{{ url('admin/websites/add') }}" method="POST">
    @csrf
    <div class="form-row mb-3">
      <label for="url" class="col-sm-2 col-form-label">URL</label>
      <div class="col-sm-5">
        <input type="url" name="url" class="form-control @error('url') border border-danger @enderror">
        @error('url')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="username" class="col-sm-2 col-form-label">Username</label>
      <div class="col-sm-5">
        <input type="text" name="username" value="{{ old('username') }}" class="form-control @error('username') border border-danger @enderror">
        @error('username')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="assigned" class="col-sm-2 col-form-label">Assigned</label>
      <div class="col-sm-5">
        <input type="text" name="assigned" value="{{ old('assigned') ?  old('assigned') : '0' }}" class="form-control @error('assigned') border border-danger @enderror">
        @error('assigned')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="max_daily_views" class="col-sm-2 col-form-label">Max. Daily Views</label>
      <div class="col-sm-5">
        <input type="text" name="max_daily_views" value="0" class="form-control @error('max_daily_views') border border-danger @enderror">
        <small>Leave it 0 for unlimited daily views</small>
        @error('max_daily_views')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="auto_assign" class="col-sm-2 col-form-label">Auto Assign</label>
      <div class="col-sm-5">
        <input type="text" name="auto_assign" value="0" class="form-control @error('auto_assign') border border-danger @enderror">
        @error('auto_assign')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="status" class="col-sm-2 col-form-label">Status</label>
      <div class="col-sm-5">
        <select name="status" id="status" class="custom-select">
          <option value="Pending">Pending</option>
          <option value="Active" selected>Active</option>
          <option value="Paused">Paused</option>
          <option value="Suspended">Suspended</option>
        </select>
      </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Add Website</button>
  </form>
</div>
@endsection
