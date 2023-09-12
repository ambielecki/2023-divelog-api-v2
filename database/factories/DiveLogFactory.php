<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DiveLog>
 */
class DiveLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'dive_number' => random_int(1, 1000),
            'location' => fake()->country,
            'dive_site' => fake()->word,
            'date_time' => date('Y-m-d H:i:s'),
            'description' => fake()->paragraph(3),
            'notes' => fake()->paragraph(4),
            'dive_details' => json_encode([]),
            'equipment_details' => json_encode([]),
        ];
    }
}
