@extends('admin.layout')

@section('page')
Add Text Ad
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ url('admin/text_ads/list') }}">Text Ads</a></li>
  <li class="breadcrumb-item active">Add Text Ad</li>
</ol>
@endsection

@section('content')
<div class="container">
  <form action="{{ url('admin/text_ads/add') }}" method="POST">
    @csrf
    <div class="form-row mb-3">
      <label for="body" class="col-sm-2 col-form-label">Body</label>
      <div class="col-sm-5">
        <input type="text" name="body" value="{{ old('body') }}" class="form-control @error('body') border border-danger @enderror">
        @error('body')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="target_url" class="col-sm-2 col-form-label">Target URL</label>
      <div class="col-sm-5">
        <input type="url" name="target_url" value="{{ old('target_url') }}" class="form-control @error('target_url') border border-danger @enderror">
        @error('target_url')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="text_color" class="col-sm-2 col-form-label">Text Color</label>
      <div class="col-sm-1">
        <input type="color" name="text_color" value="{{ old('text_color') ? old('text_color') : '#ffffff' }}" class="form-control @error('text_color') border border-danger @enderror">
        @error('text_color')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="bg_color" class="col-sm-2 col-form-label">Background Color</label>
      <div class="col-sm-1">
        <input type="color" name="bg_color" value="{{ old('bg_color') ? old('bg_color') : '#1246e2' }}" class="form-control @error('bg_color') border border-danger @enderror">
        @error('bg_color')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="text_bold" class="col-sm-2 col-form-label">Bold Text</label>
      <div class="col-sm-1">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="1" name="text_bold" id="text_bold">
        </div>
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
    <button type="submit" class="btn btn-primary mt-3">Add Text Ad</button>
  </form>
</div>
@endsection
