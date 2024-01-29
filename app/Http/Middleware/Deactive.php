<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Deactive
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
        if (Auth::guard('vendor')->user()->status == 0) {
            if ($request->isMethod('POST') || $request->isMethod('PUT')) {
                session()->flash('warning', 'Your account is deactive or pending now. Please Contact with admin!');
                return redirect()->back();
            }
        }
        return $next($request);
    }
}
