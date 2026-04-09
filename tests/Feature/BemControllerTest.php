<?php

namespace Tests\Feature;

use App\Models\Bem;
use App\Models\CategoriaBem;
use App\Models\Unidade;
use App\Models\Sala;
use App\Models\User;
use App\Models\Usuario;
use Tests\TestCase;

class BemControllerTest extends TestCase
{
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    // ─── Index / Listagem ─────────────────────────────────────────────────────

    public function test_listagem_exibe_bens_cadastrados(): void
    {
        $bens = Bem::factory()->count(3)->create();

        $this->actingAs($this->admin)
            ->get('/bens')
            ->assertStatus(200)
            ->assertSee($bens->first()->nome);
    }

    public function test_listagem_com_filtro_por_status(): void
    {
        Bem::factory()->create(['status' => 'ativo', 'nome' => 'Notebook Ativo']);
        Bem::factory()->inativo()->create(['nome' => 'Impressora Inativa']);

        $this->actingAs($this->admin)
            ->get('/bens?status=ativo')
            ->assertStatus(200)
            ->assertSee('Notebook Ativo')
            ->assertDontSee('Impressora Inativa');
    }

    public function test_listagem_com_filtro_por_busca(): void
    {
        Bem::factory()->create(['nome' => 'Monitor Dell Especial']);
        Bem::factory()->create(['nome' => 'Teclado Comum']);

        $this->actingAs($this->admin)
            ->get('/bens?busca=Dell')
            ->assertStatus(200)
            ->assertSee('Monitor Dell Especial')
            ->assertDontSee('Teclado Comum');
    }

    public function test_listagem_com_filtro_por_unidade(): void
    {
        $unidade1 = Unidade::factory()->create(['nome' => 'Sede']);
        $unidade2 = Unidade::factory()->create(['nome' => 'Filial']);

        Bem::factory()->create(['nome' => 'Notebook Sede', 'unidade_id' => $unidade1->id]);
        Bem::factory()->create(['nome' => 'Notebook Filial', 'unidade_id' => $unidade2->id]);

        $this->actingAs($this->admin)
            ->get("/bens?unidade_id={$unidade1->id}")
            ->assertStatus(200)
            ->assertSee('Notebook Sede')
            ->assertDontSee('Notebook Filial');
    }

    // ─── Show ─────────────────────────────────────────────────────────────────

    public function test_show_exibe_detalhes_do_bem(): void
    {
        $bem = Bem::factory()->create(['nome' => 'Tablet Samsung Pro']);

        $this->actingAs($this->admin)
            ->get("/bens/{$bem->id}")
            ->assertStatus(200)
            ->assertSee('Tablet Samsung Pro');
    }

    public function test_show_retorna_404_para_bem_inexistente(): void
    {
        $this->actingAs($this->admin)
            ->get('/bens/99999')
            ->assertStatus(404);
    }

    // ─── Create / Store ───────────────────────────────────────────────────────

    public function test_formulario_de_criacao_carrega_categorias_e_unidades(): void
    {
        CategoriaBem::factory()->create(['nome' => 'Notebook']);
        Unidade::factory()->create(['nome' => 'Sede Central']);

        $this->actingAs($this->admin)
            ->get('/bens/create')
            ->assertStatus(200)
            ->assertSee('Notebook')
            ->assertSee('Sede Central');
    }

    public function test_cadastro_de_bem_valido(): void
    {
        $categoria = CategoriaBem::factory()->create();

        $this->actingAs($this->admin)
            ->post('/bens', [
                'nome'        => 'Computador Desktop',
                'categoria_id' => $categoria->id,
                'status'      => 'ativo',
            ])
            ->assertRedirect('/bens');

        $this->assertDatabaseHas('bens', ['nome' => 'Computador Desktop']);
    }

    public function test_cadastro_persiste_todos_os_campos(): void
    {
        $categoria = CategoriaBem::factory()->create();
        $unidade   = Unidade::factory()->create();
        $sala      = Sala::factory()->create(['unidade_id' => $unidade->id]);
        $usuario   = Usuario::factory()->create(['unidade_id' => $unidade->id]);

        $this->actingAs($this->admin)->post('/bens', [
            'nome'              => 'Notebook Completo',
            'numero_patrimonio' => 'PAT-2026-999',
            'numero_serie'      => 'SN-ABC123',
            'descricao'         => 'Descrição do bem',
            'categoria_id'      => $categoria->id,
            'unidade_id'        => $unidade->id,
            'sala_id'           => $sala->id,
            'usuario_id'        => $usuario->id,
            'data_aquisicao'    => '2026-01-01',
            'valor'             => 4500.00,
            'marca'             => 'Dell',
            'modelo'            => 'Vostro 15',
            'status'            => 'ativo',
            'observacoes'       => 'Obs de teste',
        ]);

        $this->assertDatabaseHas('bens', [
            'nome'              => 'Notebook Completo',
            'numero_patrimonio' => 'PAT-2026-999',
            'marca'             => 'Dell',
            'status'            => 'ativo',
        ]);
    }

    public function test_cadastro_falha_sem_nome(): void
    {
        $categoria = CategoriaBem::factory()->create();

        $this->actingAs($this->admin)
            ->post('/bens', [
                'categoria_id' => $categoria->id,
                'status'       => 'ativo',
            ])
            ->assertSessionHasErrors('nome');
    }

    public function test_cadastro_falha_sem_categoria(): void
    {
        $this->actingAs($this->admin)
            ->post('/bens', [
                'nome'   => 'Bem Sem Categoria',
                'status' => 'ativo',
            ])
            ->assertSessionHasErrors('categoria_id');
    }

    public function test_cadastro_falha_com_status_invalido(): void
    {
        $categoria = CategoriaBem::factory()->create();

        $this->actingAs($this->admin)
            ->post('/bens', [
                'nome'        => 'Bem Status Inválido',
                'categoria_id' => $categoria->id,
                'status'      => 'perdido',
            ])
            ->assertSessionHasErrors('status');
    }

    public function test_cadastro_falha_com_numero_patrimonio_duplicado(): void
    {
        $categoria = CategoriaBem::factory()->create();
        Bem::factory()->create(['numero_patrimonio' => 'PAT-DUP-001']);

        $this->actingAs($this->admin)
            ->post('/bens', [
                'nome'              => 'Outro Bem',
                'categoria_id'      => $categoria->id,
                'status'            => 'ativo',
                'numero_patrimonio' => 'PAT-DUP-001',
            ])
            ->assertSessionHasErrors('numero_patrimonio');
    }

    public function test_cadastro_falha_com_valor_negativo(): void
    {
        $categoria = CategoriaBem::factory()->create();

        $this->actingAs($this->admin)
            ->post('/bens', [
                'nome'        => 'Bem Valor Negativo',
                'categoria_id' => $categoria->id,
                'status'      => 'ativo',
                'valor'       => -100,
            ])
            ->assertSessionHasErrors('valor');
    }

    public function test_cadastro_falha_com_categoria_inexistente(): void
    {
        $this->actingAs($this->admin)
            ->post('/bens', [
                'nome'        => 'Bem Categoria Inválida',
                'categoria_id' => 99999,
                'status'      => 'ativo',
            ])
            ->assertSessionHasErrors('categoria_id');
    }

    // ─── Edit / Update ────────────────────────────────────────────────────────

    public function test_formulario_de_edicao_carrega_bem_existente(): void
    {
        $bem = Bem::factory()->create(['nome' => 'Bem Para Editar']);

        $this->actingAs($this->admin)
            ->get("/bens/{$bem->id}/edit")
            ->assertStatus(200)
            ->assertSee('Bem Para Editar');
    }

    public function test_atualizacao_de_bem_valido(): void
    {
        $bem      = Bem::factory()->create(['nome' => 'Nome Antigo']);
        $categoria = CategoriaBem::factory()->create();

        $this->actingAs($this->admin)
            ->put("/bens/{$bem->id}", [
                'nome'        => 'Nome Atualizado',
                'categoria_id' => $categoria->id,
                'status'      => 'inativo',
            ])
            ->assertRedirect('/bens');

        $this->assertDatabaseHas('bens', ['id' => $bem->id, 'nome' => 'Nome Atualizado', 'status' => 'inativo']);
    }

    public function test_atualizacao_permite_mesmo_numero_patrimonio_do_proprio_bem(): void
    {
        $bem      = Bem::factory()->create(['numero_patrimonio' => 'PAT-PROPRIO-001']);
        $categoria = CategoriaBem::factory()->create();

        $this->actingAs($this->admin)
            ->put("/bens/{$bem->id}", [
                'nome'              => $bem->nome,
                'categoria_id'      => $categoria->id,
                'status'            => 'ativo',
                'numero_patrimonio' => 'PAT-PROPRIO-001',
            ])
            ->assertSessionDoesntHaveErrors('numero_patrimonio');
    }

    public function test_atualizacao_falha_com_numero_patrimonio_de_outro_bem(): void
    {
        Bem::factory()->create(['numero_patrimonio' => 'PAT-OUTRO-001']);
        $bem      = Bem::factory()->create(['numero_patrimonio' => 'PAT-MEU-002']);
        $categoria = CategoriaBem::factory()->create();

        $this->actingAs($this->admin)
            ->put("/bens/{$bem->id}", [
                'nome'              => $bem->nome,
                'categoria_id'      => $categoria->id,
                'status'            => 'ativo',
                'numero_patrimonio' => 'PAT-OUTRO-001',
            ])
            ->assertSessionHasErrors('numero_patrimonio');
    }

    // ─── Destroy ──────────────────────────────────────────────────────────────

    public function test_exclusao_remove_bem_do_banco(): void
    {
        $bem = Bem::factory()->create();

        $this->actingAs($this->admin)
            ->delete("/bens/{$bem->id}")
            ->assertRedirect('/bens');

        $this->assertDatabaseMissing('bens', ['id' => $bem->id]);
    }

    public function test_exclusao_retorna_404_para_bem_inexistente(): void
    {
        $this->actingAs($this->admin)
            ->delete('/bens/99999')
            ->assertStatus(404);
    }

    // ─── Cautela ─────────────────────────────────────────────────────────────

    public function test_pagina_de_cautela_carrega_bem(): void
    {
        $bem = Bem::factory()->create(['nome' => 'Bem Cautela Teste']);

        $this->actingAs($this->admin)
            ->get("/bens/{$bem->id}/cautela")
            ->assertStatus(200)
            ->assertSee('Bem Cautela Teste');
    }
}
