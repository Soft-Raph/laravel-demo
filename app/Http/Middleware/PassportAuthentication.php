<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PassportAuthentication
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::guard('api')->check()) {
            return ResponseHelper::error(401, 'Please, log in to continue');
        }

        return $next($request);
    }
}
