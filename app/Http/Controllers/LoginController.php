<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\LoginRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;

class LoginController extends Controller
{
    public function login(LoginRequest $request): \Illuminate\Http\JsonResponse
    {
        if (! Auth::attempt($request->validated())) {
            return ResponseHelper::error(401, 'Invalid user details');
        }

        if ($request->get('remember_me')) {
            Passport::personalAccessTokensExpireIn(now()->addDays(30));
        }

        $personalAccessToken = Auth::user()->createToken('Personal Access Token');

        $tokenData = [
            'access_token' => $personalAccessToken->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($personalAccessToken->token->expires_at)->toDateTimeString(),
        ];

        return ResponseHelper::success($tokenData, 'User logged in successfully');
    }
}
