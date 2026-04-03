<?php

namespace App\Exports;

use App\Models\Bem;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BensExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    public function __construct(private array $filtros = []) {}

    public function query()
    {
        $query = Bem::with(['categoria', 'unidade', 'sala', 'usuario']);

        if (!empty($this->filtros['unidade_id'])) {
            $query->where('unidade_id', $this->filtros['unidade_id']);
        }
        if (!empty($this->filtros['sala_id'])) {
            $query->where('sala_id', $this->filtros['sala_id']);
        }
        if (!empty($this->filtros['categoria_id'])) {
            $query->where('categoria_id', $this->filtros['categoria_id']);
        }
        if (!empty($this->filtros['status'])) {
            $query->where('status', $this->filtros['status']);
        }
        if (!empty($this->filtros['busca'])) {
            $busca = $this->filtros['busca'];
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('numero_patrimonio', 'like', "%{$busca}%")
                  ->orWhere('numero_serie', 'like', "%{$busca}%")
                  ->orWhere('marca', 'like', "%{$busca}%")
                  ->orWhere('modelo', 'like', "%{$busca}%");
            });
        }

        return $query->orderBy('nome');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nome',
            'Nº Patrimônio',
            'Nº Série',
            'Marca',
            'Modelo',
            'Categoria',
            'Unidade',
            'Sala',
            'Responsável',
            'Status',
            'Valor (R$)',
            'Data Aquisição',
            'Observações',
        ];
    }

    public function map($bem): array
    {
        return [
            $bem->id,
            $bem->nome,
            $bem->numero_patrimonio ?? '',
            $bem->numero_serie ?? '',
            $bem->marca ?? '',
            $bem->modelo ?? '',
            $bem->categoria->nome ?? '',
            $bem->unidade->nome ?? '',
            $bem->sala->nome ?? '',
            $bem->usuario->nome ?? '',
            $bem->status_label,
            $bem->valor ? number_format($bem->valor, 2, ',', '.') : '',
            $bem->data_aquisicao ? $bem->data_aquisicao->format('d/m/Y') : '',
            $bem->observacoes ?? '',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF1a3a5c']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
