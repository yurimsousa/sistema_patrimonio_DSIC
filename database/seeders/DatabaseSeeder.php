<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Unidade;
use App\Models\Sala;
use App\Models\CategoriaBem;
use App\Models\Usuario;
use App\Models\Bem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuários do sistema (login)
        User::create(['name' => 'Administrador', 'email' => 'admin@patrimonio.com', 'password' => Hash::make('admin123'), 'role' => 'admin']);
        User::create(['name' => 'Auditor Geral', 'email' => 'auditor@patrimonio.com', 'password' => Hash::make('auditor123'), 'role' => 'auditor']);
        User::create(['name' => 'Operador', 'email' => 'operador@patrimonio.com', 'password' => Hash::make('operador123'), 'role' => 'usuario']);

        // Categorias
        $categorias = [
            ['nome' => 'Computador', 'descricao' => 'Desktops e computadores fixos'],
            ['nome' => 'Notebook', 'descricao' => 'Laptops e computadores portáteis'],
            ['nome' => 'Celular/Smartphone', 'descricao' => 'Telefones celulares e smartphones'],
            ['nome' => 'Tablet', 'descricao' => 'Tablets e iPads'],
            ['nome' => 'Televisão/Monitor', 'descricao' => 'TVs e monitores'],
            ['nome' => 'Impressora', 'descricao' => 'Impressoras e multifuncionais'],
            ['nome' => 'Projetor', 'descricao' => 'Projetores e datashow'],
            ['nome' => 'Mobiliário', 'descricao' => 'Mesas, cadeiras e armários'],
            ['nome' => 'Equipamento de Rede', 'descricao' => 'Switches, roteadores, access points'],
            ['nome' => 'Outros', 'descricao' => 'Outros equipamentos'],
        ];

        foreach ($categorias as $cat) {
            CategoriaBem::create($cat);
        }

        // Unidades
        $unidade1 = Unidade::create(['nome' => 'Sede Administrativa', 'sigla' => 'SEDE', 'endereco' => 'Rua Principal, 100 - Centro', 'ativo' => true]);
        $unidade2 = Unidade::create(['nome' => 'Unidade de TI', 'sigla' => 'UTI', 'endereco' => 'Av. Tecnologia, 200 - Bloco B', 'ativo' => true]);
        $unidade3 = Unidade::create(['nome' => 'Filial Norte', 'sigla' => 'FN', 'endereco' => 'Rua Norte, 50 - Bairro Norte', 'ativo' => true]);

        // Salas
        $sala1 = Sala::create(['unidade_id' => $unidade1->id, 'nome' => 'Diretoria', 'numero' => '101', 'andar' => '1°']);
        $sala2 = Sala::create(['unidade_id' => $unidade1->id, 'nome' => 'Sala de Reuniões', 'numero' => '102', 'andar' => '1°']);
        $sala3 = Sala::create(['unidade_id' => $unidade1->id, 'nome' => 'RH', 'numero' => '201', 'andar' => '2°']);
        $sala4 = Sala::create(['unidade_id' => $unidade2->id, 'nome' => 'Suporte', 'numero' => '001', 'andar' => 'Térreo']);
        $sala5 = Sala::create(['unidade_id' => $unidade2->id, 'nome' => 'Desenvolvimento', 'numero' => '002', 'andar' => 'Térreo']);
        $sala6 = Sala::create(['unidade_id' => $unidade3->id, 'nome' => 'Atendimento', 'numero' => '01', 'andar' => 'Térreo']);

        // Usuários
        $u1 = Usuario::create(['nome' => 'Carlos Mendes', 'matricula' => '001', 'email' => 'carlos@empresa.com', 'cargo' => 'Diretor', 'telefone' => '(11) 99001-1111', 'unidade_id' => $unidade1->id]);
        $u2 = Usuario::create(['nome' => 'Ana Paula Silva', 'matricula' => '002', 'email' => 'ana@empresa.com', 'cargo' => 'Analista de RH', 'telefone' => '(11) 99002-2222', 'unidade_id' => $unidade1->id]);
        $u3 = Usuario::create(['nome' => 'Roberto Costa', 'matricula' => '003', 'email' => 'roberto@empresa.com', 'cargo' => 'Analista de TI', 'telefone' => '(11) 99003-3333', 'unidade_id' => $unidade2->id]);
        $u4 = Usuario::create(['nome' => 'Fernanda Lima', 'matricula' => '004', 'email' => 'fernanda@empresa.com', 'cargo' => 'Desenvolvedora', 'telefone' => '(11) 99004-4444', 'unidade_id' => $unidade2->id]);

        // Bens
        $bens = [
            ['nome' => 'Notebook Dell Vostro', 'numero_patrimonio' => 'PAT-2024-001', 'numero_serie' => 'DL2024001', 'categoria_id' => 2, 'marca' => 'Dell', 'modelo' => 'Vostro 15', 'unidade_id' => $unidade1->id, 'sala_id' => $sala1->id, 'usuario_id' => $u1->id, 'status' => 'ativo', 'valor' => 3500.00, 'data_aquisicao' => '2024-01-15'],
            ['nome' => 'iPhone 14 Pro', 'numero_patrimonio' => 'PAT-2024-002', 'numero_serie' => 'IP2024002', 'categoria_id' => 3, 'marca' => 'Apple', 'modelo' => 'iPhone 14 Pro', 'unidade_id' => $unidade1->id, 'sala_id' => $sala1->id, 'usuario_id' => $u1->id, 'status' => 'ativo', 'valor' => 7200.00, 'data_aquisicao' => '2024-02-01'],
            ['nome' => 'Monitor LG 27"', 'numero_patrimonio' => 'PAT-2024-003', 'categoria_id' => 5, 'marca' => 'LG', 'modelo' => '27MK400H', 'unidade_id' => $unidade1->id, 'sala_id' => $sala3->id, 'usuario_id' => $u2->id, 'status' => 'ativo', 'valor' => 1200.00, 'data_aquisicao' => '2024-01-20'],
            ['nome' => 'Notebook Lenovo ThinkPad', 'numero_patrimonio' => 'PAT-2024-004', 'numero_serie' => 'LN2024004', 'categoria_id' => 2, 'marca' => 'Lenovo', 'modelo' => 'ThinkPad E14', 'unidade_id' => $unidade2->id, 'sala_id' => $sala4->id, 'usuario_id' => $u3->id, 'status' => 'ativo', 'valor' => 4200.00, 'data_aquisicao' => '2024-03-10'],
            ['nome' => 'Computador Desktop HP', 'numero_patrimonio' => 'PAT-2024-005', 'numero_serie' => 'HP2024005', 'categoria_id' => 1, 'marca' => 'HP', 'modelo' => 'EliteDesk 800', 'unidade_id' => $unidade2->id, 'sala_id' => $sala5->id, 'usuario_id' => $u4->id, 'status' => 'ativo', 'valor' => 3000.00, 'data_aquisicao' => '2024-01-05'],
            ['nome' => 'Impressora HP LaserJet', 'numero_patrimonio' => 'PAT-2024-006', 'categoria_id' => 6, 'marca' => 'HP', 'modelo' => 'LaserJet Pro M428fdw', 'unidade_id' => $unidade1->id, 'sala_id' => $sala2->id, 'status' => 'ativo', 'valor' => 2500.00, 'data_aquisicao' => '2024-02-15'],
            ['nome' => 'Projetor Epson', 'numero_patrimonio' => 'PAT-2024-007', 'categoria_id' => 7, 'marca' => 'Epson', 'modelo' => 'PowerLite 2247U', 'unidade_id' => $unidade1->id, 'sala_id' => $sala2->id, 'status' => 'manutencao', 'valor' => 4800.00, 'data_aquisicao' => '2023-06-01'],
            ['nome' => 'Switch Cisco 24 portas', 'numero_patrimonio' => 'PAT-2024-008', 'categoria_id' => 9, 'marca' => 'Cisco', 'modelo' => 'Catalyst 2960', 'unidade_id' => $unidade2->id, 'sala_id' => $sala4->id, 'status' => 'ativo', 'valor' => 6500.00, 'data_aquisicao' => '2022-11-10'],
            ['nome' => 'Notebook Samsung Galaxy Book', 'numero_patrimonio' => 'PAT-2024-009', 'categoria_id' => 2, 'marca' => 'Samsung', 'modelo' => 'Galaxy Book 3', 'unidade_id' => $unidade3->id, 'sala_id' => $sala6->id, 'status' => 'ativo', 'valor' => 3800.00, 'data_aquisicao' => '2024-04-01'],
            ['nome' => 'Tablet iPad 10ª geração', 'numero_patrimonio' => 'PAT-2024-010', 'categoria_id' => 4, 'marca' => 'Apple', 'modelo' => 'iPad 10ª Gen', 'unidade_id' => $unidade3->id, 'sala_id' => $sala6->id, 'status' => 'ativo', 'valor' => 3200.00, 'data_aquisicao' => '2024-03-20'],
        ];

        foreach ($bens as $bem) {
            Bem::create($bem);
        }
    }
}
