<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            //
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'date_of_birth' => $this->faker->dateTimeBetween('-30 year'),
            'contact_number' => $this->faker->phoneNumber,
            'joining_date' => $this->faker->dateTimeBetween('-180 day' ),
            'residental_address' => $this->faker->address,
        ];
    }
}
