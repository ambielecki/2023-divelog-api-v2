<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\Pages\BlogPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_it_only_gets_active_blogs(): void
    {
        $active_page = BlogPage::factory()->create();
        BlogPage::factory()->create(['is_active' => 0]);
        $this->assertEquals(2, BlogPage::count());

        $response = $this->getJson('/api/blog');
        $response->assertStatus(200);
        $blogs = $response['data']['blog_pages'];
        $this->assertCount(1, $blogs);
        $this->assertEquals($active_page->id, $response['data']['blog_pages'][0]['id']);
    }

    public function test_it_substitutes_image_links(): void {
        $image = new Image();
        $image->folder ='/app/public/images/2023-10';
        $image->file_name = '2023-10-09_65247138669f6.jpg';
        $image->alt_tag = 'Test';
        $image->description = 'Test';
        $image->is_hero = 0;
        $image->has_sizes = 0;
        $image->save();

        $id = $image->id;

        $active_page = BlogPage::factory()->create([
            'content' => [
                'content' => "<p>Paragraph 1</p><p>|--$id--|</p><p>Paragraph 2</p>"
            ]
        ]);

        $this->assertEquals("<p>Paragraph 1</p>", $active_page->content['first_paragraph']);
        $this->assertCount(1, $active_page->content['images']);
        $this->assertStringContainsString('<img src=', $active_page->content['content_with_images']);
    }
}
