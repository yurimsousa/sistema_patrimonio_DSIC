<?php

namespace App\Http\Controllers;

use App\Models\Sala;
use App\Models\Unidade;
use Illuminate\Http\Request;

class SalaController extends Controller
{
    public function index(Request $request)
    {
        $query = Sala::with('unidade')->withCount('bens');

        if ($request->filled('unidade_id')) {
            $query->where('unidade_id', $request->unidade_id);
        }

        if ($request->filled('busca')) {
            $query->where('nome', 'like', '%' . $request->busca . '%')
                  ->orWhere('numero', 'like', '%' . $request->busca . '%');
        }

        $salas    = $query->orderBy('nome')->paginate(15)->withQueryString();
        $unidades = Unidade::where('ativo', true)->orderBy('nome')->get();

        return view('salas.index', compact('salas', 'unidades'));
    }

    public function create()
    {
        $unidades = Unidade::where('ativo', true)->orderBy('nome')->get();
        return view('salas.create', compact('unidades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unidade_id' => 'required|exists:unidades,id',
            'nome'       => 'required|string|max:100',
            'numero'     => 'nullable|string|max:20',
            'andar'      => 'nullable|string|max:20',
            'ativo'      => 'boolean',
        ]);

        $validated['ativo'] = $request->boolean('ativo', true);

        Sala::create($validated);

        return redirect()->route('salas.index')->with('success', 'Sala cadastrada com sucesso!');
    }

    public function show(Sala $sala)
    {
        $sala->load(['unidade', 'bens.categoria', 'bens.usuario']);
        return view('salas.show', compact('sala'));
    }

    public function edit(Sala $sala)
    {
        $unidades = Unidade::where('ativo', true)->orderBy('nome')->get();
        return view('salas.edit', compact('sala', 'unidades'));
    }

    public function update(Request $request, Sala $sala)
    {
        $validated = $request->validate([
            'unidade_id' => 'required|exists:unidades,id',
            'nome'       => 'required|string|max:100',
            'numero'     => 'nullable|string|max:20',
            'andar'      => 'nullable|string|max:20',
            'ativo'      => 'boolean',
        ]);

        $validated['ativo'] = $request->boolean('ativo', true);

        $sala->update($validated);

        return redirect()->route('salas.index')->with('success', 'Sala atualizada com sucesso!');
    }

    public function destroy(Sala $sala)
    {
        $sala->delete();
        return redirect()->route('salas.index')->with('success', 'Sala removida com sucesso!');
    }
}
