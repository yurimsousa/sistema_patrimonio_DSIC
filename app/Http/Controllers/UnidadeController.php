<?php

namespace App\Http\Controllers;

use App\Models\Unidade;
use Illuminate\Http\Request;

class UnidadeController extends Controller
{
    public function index(Request $request)
    {
        $query = Unidade::withCount(['salas', 'bens', 'usuarios']);

        if ($request->filled('busca')) {
            $query->where('nome', 'like', '%' . $request->busca . '%')
                  ->orWhere('sigla', 'like', '%' . $request->busca . '%');
        }

        $unidades = $query->orderBy('nome')->paginate(15)->withQueryString();

        return view('unidades.index', compact('unidades'));
    }

    public function create()
    {
        return view('unidades.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'      => 'required|string|max:100',
            'sigla'     => 'nullable|string|max:20',
            'endereco'  => 'nullable|string|max:255',
            'ativo'     => 'boolean',
        ]);

        $validated['ativo'] = $request->boolean('ativo', true);

        Unidade::create($validated);

        return redirect()->route('unidades.index')->with('success', 'Unidade cadastrada com sucesso!');
    }

    public function show(Unidade $unidade)
    {
        $unidade->load(['salas', 'usuarios', 'bens.categoria']);
        return view('unidades.show', compact('unidade'));
    }

    public function edit(Unidade $unidade)
    {
        return view('unidades.edit', compact('unidade'));
    }

    public function update(Request $request, Unidade $unidade)
    {
        $validated = $request->validate([
            'nome'      => 'required|string|max:100',
            'sigla'     => 'nullable|string|max:20',
            'endereco'  => 'nullable|string|max:255',
            'ativo'     => 'boolean',
        ]);

        $validated['ativo'] = $request->boolean('ativo', true);

        $unidade->update($validated);

        return redirect()->route('unidades.index')->with('success', 'Unidade atualizada com sucesso!');
    }

    public function destroy(Unidade $unidade)
    {
        $unidade->delete();
        return redirect()->route('unidades.index')->with('success', 'Unidade removida com sucesso!');
    }
}
