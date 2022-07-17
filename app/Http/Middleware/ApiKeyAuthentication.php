<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;

class ApiKeyAuthentication
{

    public function handle(Request $request, Closure $next)
    {
        $api_public_key = $request->header('public_key');
        if (! $api_public_key){
            return ResponseHelper::error(422, 'Api public key is required');
        }

        $check_api_key = ApiKey::query()
            ->where('public_key', $api_public_key)
            ->whereHas('user')
            ->first();
        if (! $check_api_key){
            return ResponseHelper::error(401, 'Access denied, check the public key used');
        }

        $user = $check_api_key->user;
        $token = $user->createToken('Personal Access Token')->accessToken;
        $request->headers->set('Authorization', 'Bearer '.$token);

        return $next($request);
    }
}
