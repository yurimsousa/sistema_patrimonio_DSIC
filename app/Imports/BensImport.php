<?php

namespace App\Imports;

use App\Models\Bem;
use App\Models\CategoriaBem;
use App\Models\Unidade;
use App\Models\Sala;
use App\Models\Usuario;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class BensImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    public int $importados = 0;
    public int $ignorados  = 0;

    public function model(array $row): ?Bem
    {
        // Ignorar linhas sem nome ou patrimônio
        if (empty($row['nome'])) {
            $this->ignorados++;
            return null;
        }

        // Ignorar se número de patrimônio já existe
        $patrimonio = $row['no_patrimonio'] ?? $row['n_patrimonio'] ?? $row['numero_patrimonio'] ?? null;
        if ($patrimonio && Bem::where('numero_patrimonio', $patrimonio)->exists()) {
            $this->ignorados++;
            return null;
        }

        // Resolver categoria por nome
        $categoriaId = null;
        if (!empty($row['categoria'])) {
            $cat = CategoriaBem::whereRaw('LOWER(nome) = LOWER(?)', [$row['categoria']])->first();
            $categoriaId = $cat?->id ?? CategoriaBem::first()?->id;
        }

        // Resolver unidade por nome
        $unidadeId = null;
        if (!empty($row['unidade'])) {
            $uni = Unidade::whereRaw('LOWER(nome) = LOWER(?)', [$row['unidade']])->first();
            $unidadeId = $uni?->id;
        }

        // Resolver sala por nome
        $salaId = null;
        if (!empty($row['sala']) && $unidadeId) {
            $sala = Sala::where('unidade_id', $unidadeId)
                ->whereRaw('LOWER(nome) = LOWER(?)', [$row['sala']])
                ->first();
            $salaId = $sala?->id;
        }

        // Resolver responsável por nome
        $usuarioId = null;
        if (!empty($row['responsavel'])) {
            $usuario = Usuario::whereRaw('LOWER(nome) = LOWER(?)', [$row['responsavel']])->first();
            $usuarioId = $usuario?->id;
        }

        // Mapear status
        $statusMap = [
            'ativo'        => 'ativo',
            'inativo'      => 'inativo',
            'manutenção'   => 'manutencao',
            'manutencao'   => 'manutencao',
            'em manutenção'=> 'manutencao',
            'descartado'   => 'descartado',
        ];
        $statusRaw = strtolower(trim($row['status'] ?? 'ativo'));
        $status = $statusMap[$statusRaw] ?? 'ativo';

        // Converter valor
        $valor = null;
        if (!empty($row['valor_r'])) {
            $valor = (float) str_replace(['.', ','], ['', '.'], $row['valor_r']);
        }

        // Converter data
        $dataAquisicao = null;
        if (!empty($row['data_aquisicao'])) {
            try {
                $dataAquisicao = \Carbon\Carbon::createFromFormat('d/m/Y', $row['data_aquisicao'])->format('Y-m-d');
            } catch (\Exception $e) {
                $dataAquisicao = null;
            }
        }

        $this->importados++;

        return new Bem([
            'nome'              => $row['nome'],
            'numero_patrimonio' => $patrimonio,
            'numero_serie'      => $row['no_serie'] ?? $row['n_serie'] ?? $row['numero_serie'] ?? null,
            'marca'             => $row['marca'] ?? null,
            'modelo'            => $row['modelo'] ?? null,
            'categoria_id'      => $categoriaId,
            'unidade_id'        => $unidadeId,
            'sala_id'           => $salaId,
            'usuario_id'        => $usuarioId,
            'status'            => $status,
            'valor'             => $valor,
            'data_aquisicao'    => $dataAquisicao,
            'observacoes'       => $row['observacoes'] ?? null,
        ]);
    }
}
