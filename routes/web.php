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

    // Bens - listagem
    Route::get('bens', [BemController::class, 'index'])->name('bens.index');

    // Exportar - admin e auditor
    Route::get('bens-exportar', [BemController::class, 'exportar'])
        ->middleware('role:admin,auditor')
        ->name('bens.exportar');

    // Escrita de bens + gestão de tabelas auxiliares - somente admin
    // IMPORTANTE: rotas estáticas (create) devem vir antes das dinâmicas ({bem})
    Route::middleware('role:admin')->group(function () {
        Route::get('bens/create', [BemController::class, 'create'])->name('bens.create');
        Route::post('bens', [BemController::class, 'store'])->name('bens.store');
        Route::post('bens-importar', [BemController::class, 'importar'])->name('bens.importar');

        Route::resource('usuarios', UsuarioController::class);
        Route::resource('unidades', UnidadeController::class);
        Route::resource('salas', SalaController::class);
        Route::resource('categorias', CategoriaBemController::class)->parameters(['categorias' => 'categoria']);
    });

    // Bens - rotas dinâmicas (após as estáticas para não capturar 'create')
    Route::get('bens/{bem}', [BemController::class, 'show'])->name('bens.show');
    Route::get('bens/{bem}/cautela', [BemController::class, 'cautela'])->name('bens.cautela');

    Route::middleware('role:admin')->group(function () {
        Route::get('bens/{bem}/edit', [BemController::class, 'edit'])->name('bens.edit');
        Route::put('bens/{bem}', [BemController::class, 'update'])->name('bens.update');
        Route::patch('bens/{bem}', [BemController::class, 'update']);
        Route::delete('bens/{bem}', [BemController::class, 'destroy'])->name('bens.destroy');
    });

    // API AJAX — throttle + validação de tipo no parâmetro
    Route::get('api/salas-por-unidade/{unidade}', function ($unidade) {
        $salas = Sala::where('unidade_id', $unidade)->where('ativo', true)->orderBy('nome')->get(['id', 'nome', 'numero']);
        return response()->json($salas);
    })->where('unidade', '\d+')->middleware('throttle:60,1')->name('api.salas');

    // Auditoria - apenas admin e auditor
    Route::middleware('role:admin,auditor')->group(function () {
        Route::get('auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
        Route::get('auditoria/{log}', [AuditoriaController::class, 'show'])->name('auditoria.show');
    });

});
