<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Defines the model's default state
     */
    public function definition(): array
    {
        return [
            'osu_uuid' => fake()->uuid(),
            'onid' => strtolower(fake()->lastName() . fake()->randomLetter())
        ];
    }
}