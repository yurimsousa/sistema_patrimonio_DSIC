<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaBem extends Model
{
    protected $table = 'categorias_bem';

    protected $fillable = ['nome', 'descricao'];

    public function bens()
    {
        return $this->hasMany(Bem::class, 'categoria_id');
    }
}
