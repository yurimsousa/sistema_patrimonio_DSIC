<?php

namespace Database\Factories;

use App\Models\Unidade;
use Illuminate\Database\Eloquent\Factories\Factory;

class UsuarioFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome'       => fake()->name(),
            'matricula'  => fake()->unique()->numerify('####'),
            'email'      => fake()->unique()->safeEmail(),
            'telefone'   => fake()->phoneNumber(),
            'cargo'      => fake()->jobTitle(),
            'unidade_id' => Unidade::factory(),
            'ativo'      => true,
        ];
    }

    public function inativo(): static
    {
        return $this->state(fn (array $attributes) => ['ativo' => false]);
    }
}
