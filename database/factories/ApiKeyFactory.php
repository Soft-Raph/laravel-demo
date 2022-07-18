<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ApiKeyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'user_id' => 1,
            'app_id' => $this->faker->randomNumber(),
            'service' => $this->faker->text(),
            'type' => $this->faker->text(),
            'username' => $this->faker->userName(),
            'password' => $this->faker->password(),
            'public_key' => $this->faker->uuid(),
            'private_key' => $this->faker->uuid(),
        ];
    }
}
