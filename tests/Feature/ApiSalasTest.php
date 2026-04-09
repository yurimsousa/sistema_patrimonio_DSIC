<?php

namespace Tests\Feature;

use App\Models\Sala;
use App\Models\Unidade;
use App\Models\User;
use Tests\TestCase;

class ApiSalasTest extends TestCase
{
    public function test_api_retorna_salas_da_unidade(): void
    {
        $unidade = Unidade::factory()->create();
        Sala::factory()->create(['unidade_id' => $unidade->id, 'nome' => 'Sala TI', 'ativo' => true]);

        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson("/api/salas-por-unidade/{$unidade->id}")
            ->assertStatus(200)
            ->assertJsonFragment(['nome' => 'Sala TI']);
    }

    public function test_api_nao_retorna_salas_inativas(): void
    {
        $unidade = Unidade::factory()->create();
        Sala::factory()->create(['unidade_id' => $unidade->id, 'nome' => 'Sala Inativa', 'ativo' => false]);

        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson("/api/salas-por-unidade/{$unidade->id}")
            ->assertStatus(200)
            ->assertJsonMissing(['nome' => 'Sala Inativa']);
    }

    public function test_api_nao_retorna_salas_de_outra_unidade(): void
    {
        $unidade1 = Unidade::factory()->create();
        $unidade2 = Unidade::factory()->create();
        Sala::factory()->create(['unidade_id' => $unidade2->id, 'nome' => 'Sala Outra Unidade']);

        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson("/api/salas-por-unidade/{$unidade1->id}")
            ->assertStatus(200)
            ->assertJsonMissing(['nome' => 'Sala Outra Unidade']);
    }

    public function test_api_retorna_lista_vazia_para_unidade_sem_salas(): void
    {
        $unidade = Unidade::factory()->create();

        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson("/api/salas-por-unidade/{$unidade->id}")
            ->assertStatus(200)
            ->assertExactJson([]);
    }

    public function test_api_rejeita_parametro_nao_numerico(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/salas-por-unidade/abc')
            ->assertStatus(404);
    }

    public function test_api_exige_autenticacao(): void
    {
        // get() sem Accept: application/json → redireciona para /login
        $this->get('/api/salas-por-unidade/1')
            ->assertRedirect('/login');
    }
}
