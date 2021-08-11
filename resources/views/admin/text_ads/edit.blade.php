@php
use \App\Models\User;
@endphp

@extends('admin.layout')

@section('page')
Edit Text Ad
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ url('admin/text_ads/list') }}">Text Ads</a></li>
  <li class="breadcrumb-item active">Edit Text Ad</li>
</ol>
@endsection

@section('content')
<div class="container">
  <form action="{{ url()->current() }}" method="POST">
    @csrf
    <div class="form-row mb-3">
      <label for="body" class="col-sm-2 col-form-label">Body</label>
      <div class="col-sm-5">
        <input type="text" name="body" value="{{ $text_ad->body }}" class="form-control @error('body') border border-danger @enderror">
        @error('body')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="target_url" class="col-sm-2 col-form-label">Target URL</label>
      <div class="col-sm-5">
        <input type="url" name="target_url" value="{{ $text_ad->target_url }}" class="form-control @error('target_url') border border-danger @enderror">
        @error('target_url')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="text_color" class="col-sm-2 col-form-label">Text Color</label>
      <div class="col-sm-1">
        <input type="color" name="text_color" value="{{ $text_ad->text_color }}" class="form-control @error('text_color') border border-danger @enderror">
        @error('text_color')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="bg_color" class="col-sm-2 col-form-label">Background Color</label>
      <div class="col-sm-1">
        <input type="color" name="bg_color" value="{{ $text_ad->bg_color }}" class="form-control @error('bg_color') border border-danger @enderror">
        @error('bg_color')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="text_bold" class="col-sm-2 col-form-label">Bold Text</label>
      <div class="col-sm-1">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="1" name="text_bold" id="text_bold" {{ $text_ad->text_bold ? "checked" : ""}}>
        </div>
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="username" class="col-sm-2 col-form-label">Username</label>
      <div class="col-sm-5">
        <input type="text" name="username" value="{{ User::where('id', $text_ad->user_id)->value('username') }}" class="form-control @error('username') border border-danger @enderror">
        @error('username')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="assigned" class="col-sm-2 col-form-label">Assigned</label>
      <div class="col-sm-5">
        <input type="text" name="assigned" value="{{ $text_ad->assigned }}" class="form-control @error('assigned') border border-danger @enderror">
        @error('assigned')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label for="status" class="col-sm-2 col-form-label">Status</label>
      <div class="col-sm-5">
        <select name="status" id="status" class="custom-select">
          <option value="Pending" {{ $text_ad->status == "Pending" ? "selected" : ""}}>Pending</option>
          <option value="Active" {{ $text_ad->status == "Active" ? "selected" : ""}}>Active</option>
          <option value="Paused" {{ $text_ad->status == "Paused" ? "selected" : ""}}>Paused</option>
          <option value="Suspended" {{ $text_ad->status == "Suspended" ? "selected" : ""}}>Suspended</option>
        </select>
      </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
    <a href="{{ url('admin/text_ads/list') }}" class="btn btn-secondary mt-3">Cancel</a>
  </form>
</div>
@endsection
