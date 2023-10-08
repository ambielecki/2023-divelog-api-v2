<?php

namespace Tests\Feature;

use App\Models\Pages\HomePage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_it_gets_home_page(): void
    {
        $page = HomePage::factory()->create([
            'is_active' => 1,
        ]);

        $response = $this->get('/api/page/home');
        $page_response = $response->decodeResponseJson();

        $this->assertEquals($page->content['content'], $page_response['data']['home_page']['content']['content']);

        $response->assertStatus(200);
    }

    public function test_it_creates_revision(): void
    {
        $original_page = HomePage::factory()->create([
            'is_active' => 1,
        ]);
    }
}
