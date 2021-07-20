<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TextAd extends Model
{
  use HasFactory;
  public $timestamps = false;
  protected $guarded = [];

  public static function select_random()
  {
    $user_id = Auth::check() ? Auth::user()->id : 0;
    $textad = TextAd::inRandomOrder()->select('id', 'body', 'text_color', 'bg_color', 'text_bold')
      ->where('user_id', '!=', $user_id)
      ->where('assigned', '>', 0)
      ->where('status', 'Active')
      ->limit(1)->get()->first();

    TextAd::where('id', $textad->id)->decrement('assigned');
    TextAd::where('id', $textad->id)->increment('views');
    return $textad;
  }
}
