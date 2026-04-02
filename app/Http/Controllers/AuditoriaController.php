<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer')->latest();

        if ($request->filled('evento')) {
            $query->where('event', $request->evento);
        }

        if ($request->filled('modelo')) {
            $query->where('subject_type', 'like', '%' . $request->modelo . '%');
        }

        if ($request->filled('usuario_id')) {
            $query->where('causer_id', $request->usuario_id)
                  ->where('causer_type', \App\Models\User::class);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('created_at', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('created_at', '<=', $request->data_fim);
        }

        if ($request->filled('busca')) {
            $query->where('description', 'like', '%' . $request->busca . '%');
        }

        $logs     = $query->paginate(25)->withQueryString();
        $usuarios = \App\Models\User::orderBy('name')->get();

        // Resumo do período filtrado
        $totalLogs   = Activity::count();
        $hojeCount   = Activity::whereDate('created_at', today())->count();
        $semanaCount = Activity::where('created_at', '>=', now()->subDays(7))->count();

        return view('auditoria.index', compact('logs', 'usuarios', 'totalLogs', 'hojeCount', 'semanaCount'));
    }

    public function show(Activity $log)
    {
        return view('auditoria.show', compact('log'));
    }
}
