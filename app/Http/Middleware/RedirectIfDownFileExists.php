<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfDownFileExists
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
    if (file_exists(base_path('/storage/framework/down'))) {
      return $next($request);
    } else {
      return redirect()->route('index');
    }
  }
}
