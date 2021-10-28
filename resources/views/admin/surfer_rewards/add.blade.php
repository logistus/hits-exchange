@extends('admin.layout')

@section('page')
Add Surfer Reward
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ url('admin/surfer_rewards') }}">Surfer Rewards</a></li>
  <li class="breadcrumb-item active">Add Surfer Reward</li>
</ol>
@endsection

@section('content')
<div class="container">
  <form action='{{ url("admin/surfer_rewards") }}' method='POST'>
    @csrf
    <div class="form-row mb-3">
      <label class="col-sm-2 col-form-label" for="page">Page</label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('page') border border-danger @enderror" value="{{ old('page') }}" id="page" name="page" aria-describedby="Reward Page">
        @error('page')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-2 col-form-label" for="credit_prize">Credit Prize</label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('credit_prize') border border-danger @enderror" value="{{ old('credit_prize') }}" id="credit_prize" name="credit_prize" aria-describedby="Credit Prize">
        @error('credit_prize')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-2 col-form-label" for="banner_prize">Banner Prize</label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('banner_prize') border border-danger @enderror" value="{{ old('banner_prize') }}" id="banner_prize" name="banner_prize" aria-describedby="Banner Prize">
        @error('banner_prize')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-2 col-form-label" for="square_banner_prize">Square Banner Prize</label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('square_banner_prize') border border-danger @enderror" value="{{ old('square_banner_prize') }}" id="square_banner_prize" name="square_banner_prize" aria-describedby="Square Banner Prize">
        @error('square_banner_prize')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-2 col-form-label" for="text_ad_prize">Text Ad Prize</label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('text_ad_prize') border border-danger @enderror" value="{{ old('text_ad_prize') }}" id="text_ad_prize" name="text_ad_prize" aria-describedby="Text Ad Prize">
        @error('text_ad_prize')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-2 col-form-label" for="purchase_balance">Purchase Balance</label>
      <div class="col-sm-5">
        <input type="text" class="form-control @error('purchase_balance') border border-danger @enderror" value="{{ old('purchase_balance') }}" id="purchase_balance" name="purchase_balance" aria-describedby="Purchase Balance">
        @error('purchase_balance')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <a href="{{ url('admin/surfer_rewards') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Add New Reward</button>
  </form>
</div>
@endsection
