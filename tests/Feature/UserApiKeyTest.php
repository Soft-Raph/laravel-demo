<?php

namespace Tests\Feature;

use App\Models\ApiKey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserApiKeyTest extends TestCase
{
    use RefreshDatabase;

    private function jsonResponse(string $status, string $message, $data = null): array
    {
        return [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
    }

    public function test_a_user_cannot_create_api_key_without_login()
    {
        Cache::flush();
        $response = $this->post(route('api_keys.store'), []);
        $response->assertStatus(401);
    }

    public function test_a_user_cannot_create_api_key_with_invalid_data()
    {
        Cache::flush();
        Artisan::call('passport:install');
        $user = User::factory()->create();
        $token = $user->createToken('Personal Access Token');
        $access_token = $token->accessToken;
        $response = $this->post(route('api_keys.store'), [], [
            'Authorization' => 'Bearer '.$access_token
        ]);
        $response->assertStatus(422);
    }

    public function test_a_user_can_create_api_key()
    {
        Cache::flush();
        Artisan::call('passport:install');
        $user = User::factory()->create();
        $token = $user->createToken('Personal Access Token');
        $access_token = $token->accessToken;
        $response = $this->post(route('api_keys.store'), [
            'service' => 'vfd',
            'type' => 'v4',
            'username' => Str::slug($user->name),
            'password' => 'password',
            'password_confirmation' => 'password',
        ],
            [
            'Authorization' => 'Bearer '.$access_token
        ]);

        $response->assertStatus(200);
        $response->assertJson($this->jsonResponse(
            'success',
            'Api key created successfully',
            []
        ));
    }

    public function test_a_user_can_fetch_api_key()
    {
        Cache::flush();
        Artisan::call('passport:install');
        $user = User::factory()->create();
        $token = $user->createToken('Personal Access Token');
        $access_token = $token->accessToken;
        $response = $this->get(route('api_keys'), [
            'Authorization' => 'Bearer '.$access_token
        ]);
        $response->assertStatus(200);
        $response->assertJson($this->jsonResponse(
            'success',
            'Api keys fetched successfully',
            []
        ));
    }

    public function test_a_user_can_delete_api_key()
    {
        Cache::flush();
        Artisan::call('passport:install');
        $user = User::factory()->create();
        $token = $user->createToken('Personal Access Token');
        $access_token = $token->accessToken;
        $api_key = ApiKey::factory()->create([
            'user_id' => $user->id
        ]);
        $response = $this->delete(route('api_keys.destroy', $api_key->uuid), [], [
            'Authorization' => 'Bearer '.$access_token
        ]);
        $response->assertStatus(200);
        $response->assertJson($this->jsonResponse(
            'success',
            'Api key deleted successfully'
        ));
    }
}
