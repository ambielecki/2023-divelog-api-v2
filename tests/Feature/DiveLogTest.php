<?php

namespace Tests\Feature;

use App\Models\DiveLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class DiveLogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_a_creator_can_delete(): void
    {
        $user = User::factory()->create();

        $log = DiveLog::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals(1, DiveLog::count());

        $token = JWTAuth::fromUser($user);
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->deleteJson('/api/dive-log/' . $log->id);

        $response->assertStatus(200);
        $this->assertEquals(0, DiveLog::count());
    }

    public function test_a_non_creator_cannot_delete(): void {
        $user = User::factory()->create();
        $other_user = User::factory()->create();

        $log = DiveLog::factory()->create([
            'user_id' => $user->id,
        ]);

        $other_token = JWTAuth::fromUser($other_user);
        $unauthorized_response = $this->withHeaders([
            'Authorization' => "Bearer $other_token"
        ])->deleteJson('/api/dive-log/' . $log->id);

        $unauthorized_response->assertStatus(403);
        $this->assertEquals(1, DiveLog::count());
    }
}
