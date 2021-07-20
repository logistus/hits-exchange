<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Banner extends Model
{
  use HasFactory;
  public $timestamps = false;
  protected $guarded = [];

  public static function select_random()
  {
    $user_id = Auth::check() ? Auth::user()->id : 0;
    $banner = Banner::inRandomOrder()->select('image_url', 'id')
      ->where('user_id', '!=', $user_id)
      ->where('assigned', '>', 0)
      ->where('status', 'Active')
      ->limit(1)->get()->first();
    Banner::where('id', $banner->id)->decrement('assigned');
    Banner::where('id', $banner->id)->increment('views');
    return $banner;
  }
}
