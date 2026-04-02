<?php

namespace App\Http\Controllers;

use App\Models\Bem;
use App\Models\Usuario;
use App\Models\Unidade;
use App\Models\CategoriaBem;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBens       = Bem::count();
        $totalUsuarios   = Usuario::where('ativo', true)->count();
        $totalUnidades   = Unidade::where('ativo', true)->count();
        $bensAtivos      = Bem::where('status', 'ativo')->count();
        $bensManutencao  = Bem::where('status', 'manutencao')->count();
        $bensDescartados = Bem::where('status', 'descartado')->count();

        $bensPorCategoria = CategoriaBem::withCount('bens')->orderByDesc('bens_count')->get();

        $bensPorUnidade = Unidade::withCount('bens')->orderByDesc('bens_count')->take(5)->get();

        $ultimosBens = Bem::with(['categoria', 'unidade', 'sala', 'usuario'])
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'totalBens',
            'totalUsuarios',
            'totalUnidades',
            'bensAtivos',
            'bensManutencao',
            'bensDescartados',
            'bensPorCategoria',
            'bensPorUnidade',
            'ultimosBens'
        ));
    }
}
