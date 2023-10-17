<?php

namespace Database\Factories\Pages;

use App\Models\Pages\BlogPage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pages\BlogPage>
 */
class BlogPageFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $title = fake()->sentence;

        return [
            'content'      => [
                'content' => '<p>' . fake()->paragraph . '</p>',
                'title'   => $title,
            ],
            'title'        => $title,
            'is_active'    => 1,
            'is_published' => 1,
            'revision'     => 1,
            'slug'         => BlogPage::createSlug($title),
        ];
    }
}
