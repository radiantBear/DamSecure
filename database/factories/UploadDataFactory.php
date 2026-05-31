<?php

namespace Database\Factories;

use App\Models;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UploadData>
 */
class UploadDataFactory extends Factory
{
    /**
     * Defines the model's default state
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['json', 'csv', 'unknown']);

        return [
            ...(
                $type === 'json'
                ? self::generate_json()
                : (
                    $type === 'csv'
                    ? self::generate_csv()
                    : self::generate_unknown()
                )
            ),
            'project_id' => Models\Project::factory()
        ];
    }

    public function json(): static
    {
        return $this->state(fn () => self::generate_json());
    }

    public function csv(): static
    {
        return $this->state(fn () => self::generate_csv());
    }

    public function unknown(): static
    {
        return $this->state(fn () => self::generate_unknown());
    }

    private static function generate_json(): array
    {
        return [
            'type' => 'json',
            'data' => json_encode([
                fake()->word() => fake()->word(),
                fake()->word() => fake()->randomNumber(),
                fake()->word() => fake()->boolean(),
                fake()->word() => fake()->words(3),
            ])
        ];
    }

    private static function generate_csv(): array
    {
        return [
            'type' => 'csv',
            'data' => implode(',', [
                fake()->word(),
                fake()->randomNumber(),
                fake()->boolean()
            ])
        ];
    }

    private static function generate_unknown(): array
    {
        return [
            'type' => 'unknown',
            'data' => 'Miscellaneous special characters that could break stuff: ,./\<{[("\';!@#$%^&*`~>}])'
        ];
    }
}
