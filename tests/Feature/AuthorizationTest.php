<?php

namespace Tests\Feature;

use App\Models\Bem;
use App\Models\User;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    // ─── Acesso sem autenticação ───────────────────────────────────────────────

    public function test_visitante_e_redirecionado_para_login(): void
    {
        $this->get('/bens')->assertRedirect('/login');
        $this->get('/')->assertRedirect('/login');
    }

    // ─── Usuário comum ────────────────────────────────────────────────────────

    public function test_usuario_comum_pode_listar_bens(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/bens')
            ->assertStatus(200);
    }

    public function test_usuario_comum_nao_pode_acessar_formulario_de_criacao(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/bens/create')
            ->assertStatus(403);
    }

    public function test_usuario_comum_nao_pode_cadastrar_bem(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/bens', [])
            ->assertStatus(403);
    }

    public function test_usuario_comum_nao_pode_editar_bem(): void
    {
        $bem = Bem::factory()->create();

        $this->actingAs(User::factory()->create())
            ->get("/bens/{$bem->id}/edit")
            ->assertStatus(403);
    }

    public function test_usuario_comum_nao_pode_excluir_bem(): void
    {
        $bem = Bem::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete("/bens/{$bem->id}")
            ->assertStatus(403);
    }

    public function test_usuario_comum_nao_pode_exportar(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/bens-exportar')
            ->assertStatus(403);
    }

    public function test_usuario_comum_nao_pode_acessar_auditoria(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/auditoria')
            ->assertStatus(403);
    }

    // ─── Auditor ──────────────────────────────────────────────────────────────

    public function test_auditor_pode_exportar(): void
    {
        $this->actingAs(User::factory()->auditor()->create())
            ->get('/bens-exportar')
            ->assertStatus(200);
    }

    public function test_auditor_pode_acessar_auditoria(): void
    {
        $this->actingAs(User::factory()->auditor()->create())
            ->get('/auditoria')
            ->assertStatus(200);
    }

    public function test_auditor_nao_pode_cadastrar_bem(): void
    {
        $this->actingAs(User::factory()->auditor()->create())
            ->post('/bens', [])
            ->assertStatus(403);
    }

    public function test_auditor_nao_pode_excluir_bem(): void
    {
        $bem = Bem::factory()->create();

        $this->actingAs(User::factory()->auditor()->create())
            ->delete("/bens/{$bem->id}")
            ->assertStatus(403);
    }

    // ─── Admin ────────────────────────────────────────────────────────────────

    public function test_admin_pode_acessar_formulario_de_criacao(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/bens/create')
            ->assertStatus(200);
    }

    public function test_admin_pode_acessar_auditoria(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/auditoria')
            ->assertStatus(200);
    }

    public function test_admin_pode_exportar(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/bens-exportar')
            ->assertStatus(200);
    }
}
