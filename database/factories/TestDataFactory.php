<?php

namespace Database\Factories;

use App\Models;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestData>
 */
class TestDataFactory extends Factory
{
    /**
     * Defines the model's default state
     */
    public function definition(): array
    {
        $latest_retrieved = fake()->numberBetween();

        return [
            'data' => json_encode([
                fake()->word() => fake()->word(),
                fake()->word() => fake()->randomNumber(),
                fake()->word() => fake()->boolean(),
                fake()->word() => fake()->words(3),
            ]),
            'latest_times_retrieved' => $latest_retrieved,
            'total_times_retrieved' => fake()->numberBetween() + $latest_retrieved,
            'project_id' => Models\Project::factory()
        ];
    }
}
