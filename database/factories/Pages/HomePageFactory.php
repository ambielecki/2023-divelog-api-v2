<?php

namespace Database\Factories\Pages;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class HomePageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content' => [
                'content' => fake()->paragraphs(3),
                'image_description' => fake()->paragraph,
                'carousel_images' => [],
            ],
            'is_active' => 0,
            'revision' => 1,
        ];
    }
}
