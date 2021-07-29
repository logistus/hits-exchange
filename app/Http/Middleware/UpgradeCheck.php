<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpgradeCheck
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next)
  {
    if (Auth::check() && now()->timestamp > $request->user()->upgrade_expires) {
      $request->user()->user_type = 1;
      $request->user()->upgrade_expires = null;
      $request->user()->save();
      return $next($request);
    }
    return $next($request);
  }
}
