<?php

namespace Database\Factories;

use App\Models;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DownloadData>
 */
class DownloadDataFactory extends Factory
{
    /**
     * Defines the model's default state
     */
    public function definition(): array
    {
        return [
            'data' => json_encode([
                fake()->word() => fake()->word(),
                fake()->word() => fake()->randomNumber(),
                fake()->word() => fake()->boolean(),
                fake()->word() => fake()->words(3),
            ]),
            'project_id' => Models\Project::factory()
        ];
    }
}
