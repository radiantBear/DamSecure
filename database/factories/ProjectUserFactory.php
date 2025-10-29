<?php

namespace Database\Factories;

use App\Models;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProjectUser>
 */
class ProjectUserFactory extends Factory
{
    /**
     * Defines the model's default state
     */
    public function definition(): array
    {
        return [
            'user_id' => Models\User::factory(),
            'project_id' => Models\Project::factory(),
            'role' => fake()->randomElement(['owner', 'contributor', 'viewer'])
        ];
    }
}