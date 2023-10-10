<?php

namespace Database\Seeders;

use App\Scopes\BlogPageScope;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pages\BlogPage;

class BlogPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $title = fake()->words(3, true);
            $paragraph = fake()->paragraph;
            $blog = new BlogPage();
            $blog->title = $title;
            $blog->content = [
                'title' => $title,
                'content' => "<p>$paragraph</p>",
            ];
            $blog->is_active = true;
            $blog->revision = 1;
            $blog->slug = BlogPage::createSlug($title);
            $blog->save();
            $blog->parent_id = $blog->id;
            $blog->save();
        }
    }
}
