<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HasPermission
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle(Request $request, Closure $next, $menuName)
  {
    $authAdmin = Auth::guard('admin')->user();
    $role = null;

    if (!is_null($authAdmin->role_id)) {
      $role = $authAdmin->role()->first();
    }

    if (!is_null($role)) {
      $rolePermissions = json_decode($role->permissions);
    }

    if (is_null($role) || (!empty($rolePermissions) && in_array($menuName, $rolePermissions))) {
      return $next($request);
    }

    return redirect()->back();
  }
}
