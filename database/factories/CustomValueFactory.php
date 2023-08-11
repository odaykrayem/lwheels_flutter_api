<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'key' => $this->faker->unique()->randomElement(['min_balance','referral_points_user','referral_points_owner', 'points_price', 'ref_times']),
            'value' => $this->faker->randomElement([1,50,100,150,200])
        ];
    }
}
