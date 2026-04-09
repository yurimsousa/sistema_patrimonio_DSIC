<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UnidadeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome'     => fake()->company(),
            'sigla'    => strtoupper(fake()->lexify('???')),
            'endereco' => fake()->address(),
            'ativo'    => true,
        ];
    }

    public function inativa(): static
    {
        return $this->state(fn (array $attributes) => ['ativo' => false]);
    }
}
