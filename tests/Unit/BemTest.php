<?php

namespace Tests\Unit;

use App\Models\Bem;
use Tests\TestCase;

class BemTest extends TestCase
{
    public function test_status_label_retorna_ativo(): void
    {
        $bem = new Bem(['status' => 'ativo']);

        $this->assertEquals('Ativo', $bem->status_label);
    }

    public function test_status_label_retorna_em_manutencao(): void
    {
        $bem = new Bem(['status' => 'manutencao']);

        $this->assertEquals('Em Manutenção', $bem->status_label);
    }

    public function test_status_label_retorna_inativo(): void
    {
        $bem = new Bem(['status' => 'inativo']);

        $this->assertEquals('Inativo', $bem->status_label);
    }

    public function test_status_label_retorna_descartado(): void
    {
        $bem = new Bem(['status' => 'descartado']);

        $this->assertEquals('Descartado', $bem->status_label);
    }

    public function test_status_label_retorna_indefinido_para_valor_desconhecido(): void
    {
        $bem = new Bem(['status' => null]);

        $this->assertEquals('Indefinido', $bem->status_label);
    }

    public function test_status_color_ativo_retorna_success(): void
    {
        $bem = new Bem(['status' => 'ativo']);

        $this->assertEquals('success', $bem->status_color);
    }

    public function test_status_color_manutencao_retorna_warning(): void
    {
        $bem = new Bem(['status' => 'manutencao']);

        $this->assertEquals('warning', $bem->status_color);
    }

    public function test_status_color_descartado_retorna_danger(): void
    {
        $bem = new Bem(['status' => 'descartado']);

        $this->assertEquals('danger', $bem->status_color);
    }

    public function test_status_color_inativo_retorna_secondary(): void
    {
        $bem = new Bem(['status' => 'inativo']);

        $this->assertEquals('secondary', $bem->status_color);
    }

    public function test_fillable_contem_campos_esperados(): void
    {
        $bem = new Bem();

        $this->assertContains('nome', $bem->getFillable());
        $this->assertContains('numero_patrimonio', $bem->getFillable());
        $this->assertContains('categoria_id', $bem->getFillable());
        $this->assertContains('status', $bem->getFillable());
        $this->assertContains('valor', $bem->getFillable());
    }

    public function test_valor_e_convertido_para_decimal(): void
    {
        $bem = Bem::factory()->make(['valor' => 1500.5]);

        $this->assertEquals('1500.50', $bem->valor);
    }

    public function test_data_aquisicao_e_convertida_para_carbon(): void
    {
        $bem = Bem::factory()->make(['data_aquisicao' => '2024-01-15']);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $bem->data_aquisicao);
        $this->assertEquals('2024-01-15', $bem->data_aquisicao->format('Y-m-d'));
    }
}
