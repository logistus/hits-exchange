<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Suspended
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
    if (!Auth::check())
      return redirect('/');
    else if (Auth::check() && $request->user()->isSuspended())
      return redirect('suspended');
    else
      return $next($request);
  }
}
