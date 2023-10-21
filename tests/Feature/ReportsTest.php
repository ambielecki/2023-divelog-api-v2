<?php

namespace Tests\Feature;

use App\Models\DiveLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReportsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_gets_users_report(): void
    {
        $admin_user = User::factory()->create([
            'is_admin' => true,
        ]);

        User::factory()->create();
        User::factory()->create([
            'created_at' => date('Y-m-d', strtotime('60 days ago')),
        ]);

        $token = JWTAuth::fromUser($admin_user);
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/admin/reports/users');
        $response_data = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(3, $response_data['data']['total']);
        $this->assertEquals(2, $response_data['data']['last_thirty_days']);
    }

    public function test_it_gets_dive_logs_report(): void
    {
        $admin_user = User::factory()->create([
            'is_admin' => true,
        ]);

        DiveLog::factory()->create([
            'user_id' => $admin_user->id,
        ]);
        DiveLog::factory()->create([
            'user_id' => $admin_user->id,
        ]);
        DiveLog::factory()->create([
            'user_id' => $admin_user->id,
            'created_at' => date('Y-m-d', strtotime('60 days ago')),
        ]);

        $token = JWTAuth::fromUser($admin_user);
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/admin/reports/logs');
        $response_data = $response->decodeResponseJson();

        $response->assertStatus(200);
        $this->assertEquals(3, $response_data['data']['total']);
        $this->assertEquals(2, $response_data['data']['last_thirty_days']);
    }
}
