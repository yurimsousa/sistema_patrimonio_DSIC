<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaBemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome'     => fake()->unique()->word(),
            'descricao' => fake()->sentence(),
        ];
    }
}
