<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BemController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\CategoriaBemController;
use App\Http\Controllers\AuditoriaController;
use App\Models\Sala;

// Autenticação (sem registro público)
Auth::routes(['register' => false]);

// Rotas protegidas
Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('bens', BemController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('unidades', UnidadeController::class);
    Route::resource('salas', SalaController::class);
    Route::resource('categorias', CategoriaBemController::class)->parameters(['categorias' => 'categoria']);

    // API AJAX
    Route::get('api/salas-por-unidade/{unidade}', function ($unidade) {
        $salas = Sala::where('unidade_id', $unidade)->where('ativo', true)->orderBy('nome')->get(['id', 'nome', 'numero']);
        return response()->json($salas);
    })->name('api.salas');

    // Auditoria - apenas admin e auditor
    Route::middleware('role:admin,auditor')->group(function () {
        Route::get('auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
        Route::get('auditoria/{log}', [AuditoriaController::class, 'show'])->name('auditoria.show');
    });

});
