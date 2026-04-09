<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaBem extends Model
{
    use HasFactory;
    protected $table = 'categorias_bem';

    protected $fillable = ['nome', 'descricao'];

    public function bens()
    {
        return $this->hasMany(Bem::class, 'categoria_id');
    }
}
