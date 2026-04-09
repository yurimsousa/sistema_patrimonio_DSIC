<?php

namespace Database\Factories;

use App\Models\CategoriaBem;
use App\Models\Unidade;
use App\Models\Sala;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class BemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome'              => fake()->words(3, true),
            'numero_patrimonio' => fake()->unique()->numerify('PAT-####-###'),
            'numero_serie'      => fake()->unique()->bothify('SN-????####'),
            'descricao'         => fake()->sentence(),
            'categoria_id'      => CategoriaBem::factory(),
            'unidade_id'        => Unidade::factory(),
            'sala_id'           => null,
            'usuario_id'        => null,
            'data_aquisicao'    => fake()->date(),
            'valor'             => fake()->randomFloat(2, 100, 10000),
            'marca'             => fake()->company(),
            'modelo'            => fake()->bothify('Modelo-??##'),
            'status'            => 'ativo',
            'observacoes'       => null,
        ];
    }

    public function inativo(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'inativo']);
    }

    public function emManutencao(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'manutencao']);
    }

    public function descartado(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'descartado']);
    }
}
