<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookStatusFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['Available', 'Borrowed', 'Reserved']),
            'background_color' => $this->faker->hexColor(),
            'text_color' => $this->faker->hexColor(),
        ];
    }
}