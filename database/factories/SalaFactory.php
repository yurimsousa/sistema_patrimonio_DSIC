<?php

namespace Database\Factories;

use App\Models\Unidade;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'unidade_id' => Unidade::factory(),
            'nome'       => fake()->word(),
            'numero'     => fake()->numerify('###'),
            'andar'      => fake()->randomElement(['Térreo', '1°', '2°', '3°']),
            'ativo'      => true,
        ];
    }
}
