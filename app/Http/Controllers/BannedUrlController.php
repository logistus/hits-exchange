<?php

namespace App\Http\Controllers;

use App\Models\BannedUrl;
use Illuminate\Http\Request;

class BannedUrlController extends Controller
{
  public static function check_banned($url)
  {
    $domain = str_replace("www.", "", parse_url($url, PHP_URL_HOST));
    $isBanned = BannedUrl::where('url', $domain)->get()->first();
    if ($isBanned) {
      $warning_message = "$domain is banned!";
      if ($isBanned->reason)
        $warning_message .= " Reason: " . $isBanned->reason;
      return $warning_message;
    } else {
      return false;
    }
  }
}
