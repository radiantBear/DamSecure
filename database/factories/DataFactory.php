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
        $type = fake()->randomElement(['json', 'csv', 'unknown']);

        switch ($type)
        {
            case 'json':
                $data = json_encode([
                    fake()->word() => fake()->word(),
                    fake()->word() => fake()->randomNumber(),
                    fake()->word() => fake()->boolean(),
                    fake()->word() => fake()->words(3),
                ]);
                break;
            case 'csv':
                $data = implode(',', [
                    fake()->word(),
                    fake()->randomNumber(),
                    fake()->boolean()
                ]);
                break;
            case 'unknown':
                $data = 'Miscellaneous special characters that could break stuff: ,./\<{[("\';!@#$%^&*`~>}])';
                break;
        }

        return [
            'data' => $data,
            'type' => $type,
            'project_id' => Models\Project::factory()
        ];
    }
}