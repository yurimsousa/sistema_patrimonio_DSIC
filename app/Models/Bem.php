<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Bem extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'bens';

    protected $fillable = [
        'nome', 'numero_patrimonio', 'numero_serie', 'descricao',
        'categoria_id', 'unidade_id', 'sala_id', 'usuario_id',
        'data_aquisicao', 'valor', 'marca', 'modelo', 'status', 'observacoes',
    ];

    protected $casts = [
        'data_aquisicao' => 'date',
        'valor'          => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nome', 'numero_patrimonio', 'categoria_id', 'unidade_id', 'sala_id', 'usuario_id', 'status', 'valor', 'data_aquisicao'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => match($eventName) {
                'created' => "Bem '{$this->nome}' foi cadastrado",
                'updated' => "Bem '{$this->nome}' foi atualizado",
                'deleted' => "Bem '{$this->nome}' foi removido",
                default   => $eventName,
            });
    }

    public function categoria()
    {
        return $this->belongsTo(CategoriaBem::class, 'categoria_id');
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class);
    }

    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'ativo'      => 'Ativo',
            'inativo'    => 'Inativo',
            'manutencao' => 'Em Manutenção',
            'descartado' => 'Descartado',
            default      => $this->status ?? 'Indefinido',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'ativo'      => 'success',
            'inativo'    => 'secondary',
            'manutencao' => 'warning',
            'descartado' => 'danger',
            default      => 'secondary',
        };
    }
}
