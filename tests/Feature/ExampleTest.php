<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_visitante_e_redirecionado_ao_acessar_raiz(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_usuario_autenticado_acessa_dashboard(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $this->actingAs($user)->get('/')->assertStatus(200);
    }
}
