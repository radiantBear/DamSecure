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
        $firstName = fake()->lastName();
        $lastName = fake()->lastName();
        $onid = strtolower($lastName . $firstName[0]);

        return [
            'osuuid' => fake()->numerify('###########'),
            'onid' => $onid,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $onid . '@oregonstate.edu'
        ];
    }
}