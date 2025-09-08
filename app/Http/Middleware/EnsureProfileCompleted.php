<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureProfileCompleted
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $isSetupRoute = $request->routeIs('profile.setup.show') || $request->routeIs('profile.setup.store');
            if (is_null($user->profile_completed_at) && !$isSetupRoute) {
                return redirect()->route('profile.setup.show');
            }
        }
        return $next($request);
    }
}



