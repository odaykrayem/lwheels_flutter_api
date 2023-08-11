<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Reward;

class RewardsRegistryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'reward_id' => Reward::factory(),
            'started_at' =>$this->faker->dateTimeThisDecade(),
            'is_finished' => $this->faker->randomElement([true,false])
        ];
    }
}
