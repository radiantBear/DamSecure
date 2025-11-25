<?php

namespace Database\Factories;

use App\Models;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Data>
 */
class DataFactory extends Factory
{
    /**
     * Defines the model's default state
     */
    public function definition(): array
    {
        $data = [
            fake()->word() => fake()->word(),
            fake()->word() => fake()->randomNumber(),
            fake()->word() => fake()->boolean(),
            fake()->word() => fake()->words(3),
        ];

        return [
            'data' => json_encode($data),
            'type' => fake()->randomElement(['json', 'csv', 'unknown']),
            'project_id' => Models\Project::factory()
        ];
    }
}