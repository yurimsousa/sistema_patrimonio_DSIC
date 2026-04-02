<?php

namespace App\Http\Controllers;

use App\Models\CategoriaBem;
use Illuminate\Http\Request;

class CategoriaBemController extends Controller
{
    public function index()
    {
        $categorias = CategoriaBem::withCount('bens')->orderBy('nome')->paginate(15);
        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'      => 'required|string|max:80',
            'descricao' => 'nullable|string|max:255',
        ]);

        CategoriaBem::create($validated);

        return redirect()->route('categorias.index')->with('success', 'Categoria cadastrada com sucesso!');
    }

    public function show(CategoriaBem $categoria)
    {
        $categoria->load('bens');
        return view('categorias.show', compact('categoria'));
    }

    public function edit(CategoriaBem $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, CategoriaBem $categoria)
    {
        $validated = $request->validate([
            'nome'      => 'required|string|max:80',
            'descricao' => 'nullable|string|max:255',
        ]);

        $categoria->update($validated);

        return redirect()->route('categorias.index')->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(CategoriaBem $categoria)
    {
        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', 'Categoria removida com sucesso!');
    }
}
