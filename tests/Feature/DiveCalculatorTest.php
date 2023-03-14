<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class DiveCalculatorTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_it_responds_with_proper_structure(): void
    {
        $response = $this->getJson('/api/dive-calculation');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'message_type',
            'request_info' => ['route', 'method', 'date'],
            'data' => ['dive_1_max_time', 'dive_1_pg', 'post_si_pg', 'rnt', 'dive_2_max_time', 'dive_2_pg'],
        ]);
    }

    public function test_it_responds_with_422_on_failed_validation(): void
    {
        $response = $this->getJson('/api/dive-calculation?dive_1_depth=test');
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'message_type',
            'request_info' => ['route', 'method', 'date'],
            'data' => ['errors'],
        ]);
    }
}
