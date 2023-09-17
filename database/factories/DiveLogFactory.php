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
        $timestamp_start = strtotime('1 year ago');
        $timestamp_end = strtotime('now');

        return [
            'user_id' => 1,
            'dive_number' => random_int(1, 10000),
            'location' => fake()->country,
            'dive_site' => fake()->word,
            'buddy' => fake()->name,
            'max_depth_ft' => random_int(30, 75),
            'bottom_time_min' => 30,
            'surface_interval_min' => 0,
            'used_computer' => true,
            'date_time' => date('Y-m-d H:i:s', rand($timestamp_start, $timestamp_end)),
            'description' => fake()->paragraph(3),
            'notes' => fake()->paragraph(4),
            'dive_details' => json_encode([]),
            'equipment_details' => json_encode([]),
        ];
    }
}
