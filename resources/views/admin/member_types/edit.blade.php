@extends('admin.layout')

@section('page')
Edit Member Type <strong>{{ $user_type->name }}</strong>
@endsection

@section('breadcrumb')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{ url('admin') }}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ url('admin/member_types') }}">Member Types</a></li>
  <li class="breadcrumb-item active">Edit Member Type</li>
</ol>
@endsection

@section('content')
<div class="container">
  <form action="{{ url('admin/member_types', $user_type->id) }}" method="POST">
    @method('PUT')
    @csrf
    <div class="form-row mb-3">
      <label class="col-sm-3 col-form-label" for="name">Name</label>
      <div class="col-sm-5">
        <input type="text" class="form-control @error('name') border border-danger @enderror" value="{{ $user_type->name }}" id="name" name="name" aria-describedby="Type Name">
        @error('name')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-3 col-form-label" for="surf_timer">Surf Timer <small>(as seconds)</small></label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('surf_timer') border border-danger @enderror" value="{{ $user_type->surf_timer }}" id="surf_timer" name="surf_timer" aria-describedby="Surf Timer">
        @error('surf_timer')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-3 col-form-label" for="surf_ratio">Surf Ratio</label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('surf_ratio') border border-danger @enderror" value="{{ $user_type->surf_ratio }}" id="surf_ratio" name="surf_ratio" aria-describedby="Surf Ratio">
        @error('surf_ratio')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-3 col-form-label" for="commission_ratio">Commission Ratio <small>(as percent)</small></label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('commission_ratio') border border-danger @enderror" value="{{ $user_type->commission_ratio }}" id="commission_ratio" name="commission_ratio" aria-describedby="Commission Ratio">
        @error('commission_ratio')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-3 col-form-label" for="max_websites">Max Websites</label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('max_websites') border border-danger @enderror" value="{{ $user_type->max_websites }}" id="max_websites" name="max_websites" aria-describedby="Max Websites">
        @error('max_websites')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-3 col-form-label" for="max_banners">Max Banners</label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('max_banners') border border-danger @enderror" id="max_banners" value="{{ $user_type->max_banners }}" name="max_banners" aria-describedby="Max Banners">
        @error('max_banners')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-3 col-form-label" for="max_square_banners">Max Square Banners</label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('max_square_banners') border border-danger @enderror" value="{{ $user_type->max_square_banners }}" id="max_square_banners" name="max_square_banners" aria-describedby="Max Square Banners">
        @error('max_square_banners')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-3 col-form-label" for="max_texts">Max Text Ads</label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('max_texts') border border-danger @enderror" value="{{ $user_type->max_texts }}" id="max_texts" name="max_texts" aria-describedby="Max Text Ads">
        @error('max_texts')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-3 col-form-label" for="min_auto_assign">Min Auto Assign</label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('min_auto_assign') border border-danger @enderror" value="{{ $user_type->min_auto_assign }}" id="min_auto_assign" name="min_auto_assign" aria-describedby="Min Auto Assign">
        @error('min_auto_assign')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-3 col-form-label" for="credit_reward_ratio">Credits from referrals</label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('credit_reward_ratio') border border-danger @enderror" value="{{ $user_type->credit_reward_ratio }}" id="credit_reward_ratio" name="credit_reward_ratio" aria-describedby="Credits from referrals">
        @error('credit_reward_ratio')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-3 col-form-label" for="credits_to_banner">Credits to banners</label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('credits_to_banner') border border-danger @enderror" value="{{ $user_type->credits_to_banner }}" id="credits_to_banner" name="credits_to_banner" aria-describedby="Credits to banners">
        @error('credits_to_banner')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-3 col-form-label" for="credits_to_square_banner">Credits to square banners</label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('credits_to_square_banner') border border-danger @enderror" value="{{ $user_type->credits_to_square_banner }}" id="credits_to_square_banner" name="credits_to_square_banner" aria-describedby="Credits to square banners">
        @error('credits_to_square_banner')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-3 col-form-label" for="credits_to_text">Credits to text ads</label>
      <div class="col-sm-5">
        <input type="number" class="form-control @error('credits_to_text') border border-danger @enderror" value="{{ $user_type->credits_to_text }}" id="credits_to_text" name="credits_to_text" aria-describedby="Credits to text ads">
        @error('credits_to_text')
        <div class="text-danger">{{ $message }}</div>
        @enderror
      </div>
    </div>
    <div class="form-row mb-3">
      <label class="col-sm-3 col-form-label" for="customize_text_ads">Can customize text ads</label>
      <div class="col-sm-5">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="1" name="customize_text_ads" id="customize_text_ads" {{ $user_type->customize_text_ads ? "checked" : ""}}>
        </div>
      </div>
      <div class="col-sm-5">
      </div>
    </div>
    <div class="form-row mb-3 default_text_ad_color">
      <label class="col-sm-3 col-form-label" for="default_text_ad_color">Default text ad color</label>
      <div class="col-sm-5">
        <input class="form-control" type="color" value="{{ $user_type->default_text_ad_color }}" name="default_text_ad_color" id="default_text_ad_color">
      </div>
    </div>
    <div class="form-row mb-3 default_text_ad_bg_color">
      <label class="col-sm-3 col-form-label" for="default_text_ad_bg_color">Default text ad background color</label>
      <div class="col-sm-5">
        <input class="form-control" type="color" value="{{ $user_type->default_text_ad_bg_color }}" name="default_text_ad_bg_color" id="default_text_ad_bg_color">
      </div>
    </div>
    <a href="{{ url('admin/member_types') }}" class="btn btn-secondary">Cancel</a>
    <button type="submit" class="btn btn-primary">Update Type</button>
  </form>
</div>
@endsection
