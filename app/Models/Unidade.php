<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Unidade extends Model
{
    use LogsActivity;

    protected $fillable = ['nome', 'sigla', 'endereco', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => match($eventName) {
                'created' => "Unidade '{$this->nome}' foi cadastrada",
                'updated' => "Unidade '{$this->nome}' foi atualizada",
                'deleted' => "Unidade '{$this->nome}' foi removida",
                default   => $eventName,
            });
    }

    public function salas()
    {
        return $this->hasMany(Sala::class);
    }

    public function usuarios()
    {
        return $this->hasMany(Usuario::class);
    }

    public function bens()
    {
        return $this->hasMany(Bem::class);
    }
}
