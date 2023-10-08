<?php

namespace Tests\Feature;

use App\Models\Pages\HomePage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

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

        $new_page = HomePage::setHomePage([
            'page' => [
                'content' => [
                    'content' => fake()->paragraphs(3),
                    'title' => fake()->sentence,
                ]
            ]
        ]);

        $home_page = HomePage::query()->where('is_active', 1)->get();

        $this->assertEquals(1, $home_page->count());
        $this->assertEquals($new_page->id, $home_page[0]->id);
        $this->assertEquals(2, $home_page[0]->revision);
        $this->assertEquals($original_page->id, $home_page[0]->parent_id);

        $response = $this->get('/api/page/home');
        $page_response = $response->decodeResponseJson();
        $this->assertEquals($new_page->id, $page_response['data']['home_page']['id']);
    }

    public function test_an_admin_can_edit(): void {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $original_page = HomePage::factory()->create([
            'is_active' => 1,
        ]);

        $token = JWTAuth::fromUser($admin);
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/admin/home', [
            'page' => [
                'content' => [
                    'content' => fake()->paragraph,
                    'title' => fake()->sentence,
                ],
            ],
        ]);

        $response->assertStatus(200);
        $page_response = $response->decodeResponseJson();
        $this->assertEquals($original_page->id, $page_response['data']['home_page']['parent_id']);
    }

    public function test_a_non_admin_cannot_edit(): void {
        $admin = User::factory()->create([
            'is_admin' => false,
        ]);

        $original_page = HomePage::factory()->create([
            'is_active' => 1,
        ]);

        $token = JWTAuth::fromUser($admin);
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/admin/home', [
            'page' => [
                'content' => [
                    'content' => fake()->paragraph,
                    'title' => fake()->sentence,
                ],
            ],
        ]);

        $response->assertStatus(403);
        $home_page = HomePage::where('is_active', 1)->first();
        $this->assertEquals($original_page->id, $home_page->id);
    }
}
