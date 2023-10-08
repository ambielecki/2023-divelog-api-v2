<?php

namespace Tests\Feature;

use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_gets_jwt_from_login(): void
    {
        $this->seed(UsersTableSeeder::class);

        $response = $this->postJson('/api/login', [
            'email' => config('app.test_user_email'),
            'password' => config('app.test_user_password'),
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'message_type',
            'request_info' => ['route', 'method', 'date'],
            'data' => ['access_token', 'token_type', 'expires_in'],
        ]);

        $response->assertJsonPath('data.access_token', fn ($token) => is_string($token));
    }

    public function test_it_can_access_guarded_with_jwt(): void
    {
        $this->seed(UsersTableSeeder::class);

        $login = $this->postJson('/api/login', [
            'email' => config('app.test_user_email'),
            'password' => config('app.test_user_password'),
        ]);

        $content = $login->decodeResponseJson();
        $token = $content['data']['access_token'];

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
        ])->getJson('/api/user');

        $response->assertStatus(200);

        $response->assertJsonPath('data.user.email', config('app.test_user_email'));
    }

    public function test_it_fails_access_without_jwt(): void
    {
        $response = $this->getJson('/api/user');
        $response->assertStatus(401);
    }
}
