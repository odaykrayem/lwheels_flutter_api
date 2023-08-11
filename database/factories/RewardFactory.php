<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RewardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'duration' => $this->faker->randomElement([6,24]),
            'value' => $this->faker->numberBetween(100,200)
        ];
    }
}
