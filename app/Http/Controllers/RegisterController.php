<?php

namespace App\Http\Controllers;

use App\Helpers\LoggingHelper;
use App\Helpers\ResponseHelper;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    private $personalAccessToken;

    public function register(RegisterRequest $request): \Illuminate\Http\JsonResponse
    {
        try {
            $data = $request->validated();
            $user_data = $this->userData($data);

            DB::transaction(function () use ($user_data) {
                $create_user = User::create($user_data);
                if (! $create_user) {
                    return ResponseHelper::error(500, 'An error occurred, try again.');
                }
                Auth::login($create_user);
                $this->personalAccessToken = Auth::user()->createToken('Personal Access Token');
            });

            $tokenData = [
                'access_token' => $this->personalAccessToken->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($this->personalAccessToken->token->expires_at)->toDateTimeString(),
            ];

            return ResponseHelper::success($tokenData, 'User registration successfully');
        } catch (\Exception $exception) {
            LoggingHelper::make('error', $exception->getMessage(), $exception->getLine(), $exception->getFile());

            return ResponseHelper::error(500, 'An error occurred');
        }
    }

    private function userData($data)
    {
        $data['password'] = Hash::make($data['password']);
        //Other data can follow
        return $data;
    }
}
