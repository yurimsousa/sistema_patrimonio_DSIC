<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Sala extends Model
{
    use LogsActivity;

    protected $fillable = ['unidade_id', 'nome', 'numero', 'andar', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['unidade_id', 'nome', 'numero', 'andar', 'ativo'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => match($eventName) {
                'created' => "Sala '{$this->nome}' foi cadastrada",
                'updated' => "Sala '{$this->nome}' foi atualizada",
                'deleted' => "Sala '{$this->nome}' foi removida",
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
