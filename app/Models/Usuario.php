<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Usuario extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'usuarios';

    protected $fillable = ['nome', 'matricula', 'email', 'telefone', 'cargo', 'unidade_id', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nome', 'matricula', 'cargo', 'unidade_id', 'ativo'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => match($eventName) {
                'created' => "Usuário '{$this->nome}' foi cadastrado",
                'updated' => "Usuário '{$this->nome}' foi atualizado",
                'deleted' => "Usuário '{$this->nome}' foi removido",
                default   => $eventName,
            });
    }

    public function unidade()
    {
        return $this->belongsTo(Unidade::class);
    }

    public function bens()
    {
        return $this->hasMany(Bem::class);
    }
}
