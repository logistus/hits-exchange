<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SquareBanner extends Model
{
  use HasFactory;
  public $timestamps = false;
  protected $guarded = [];

  public static function select_random()
  {
    $user_id = Auth::check() ? Auth::user()->id : 0;
    $square_banner = SquareBanner::inRandomOrder()->select('image_url', 'id')
      ->where('user_id', '!=', $user_id)
      ->where('assigned', '>', 0)
      ->where('status', 'Active')
      ->limit(1)->get()->first();

    SquareBanner::where('id', $square_banner->id)->decrement('assigned');
    SquareBanner::where('id', $square_banner->id)->increment('views');
    return $square_banner;
  }
}
