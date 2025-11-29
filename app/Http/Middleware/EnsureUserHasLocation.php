<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasLocation
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ((empty($user->provinsi) || empty($user->kabupaten))
                && !$request->routeIs('lokasi.*')) {

                return redirect()->route('lokasi.create');
            }
        }

        return $next($request);
    }
}
