<?php

namespace App\Http\Controllers;

use App\Models\Bem;
use App\Models\Unidade;
use App\Models\Sala;
use App\Models\CategoriaBem;
use App\Models\Usuario;
use Illuminate\Http\Request;

class BemController extends Controller
{
    public function index(Request $request)
    {
        $query = Bem::with(['categoria', 'unidade', 'sala', 'usuario']);

        if ($request->filled('unidade_id')) {
            $query->where('unidade_id', $request->unidade_id);
        }

        if ($request->filled('sala_id')) {
            $query->where('sala_id', $request->sala_id);
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('numero_patrimonio', 'like', "%{$busca}%")
                  ->orWhere('numero_serie', 'like', "%{$busca}%")
                  ->orWhere('marca', 'like', "%{$busca}%")
                  ->orWhere('modelo', 'like', "%{$busca}%");
            });
        }

        $bens       = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $unidades   = Unidade::where('ativo', true)->orderBy('nome')->get();
        $salas      = $request->filled('unidade_id')
            ? Sala::where('unidade_id', $request->unidade_id)->where('ativo', true)->orderBy('nome')->get()
            : collect();
        $categorias = CategoriaBem::orderBy('nome')->get();

        return view('bens.index', compact('bens', 'unidades', 'salas', 'categorias'));
    }

    public function create()
    {
        $unidades   = Unidade::where('ativo', true)->orderBy('nome')->get();
        $salas      = collect();
        $categorias = CategoriaBem::orderBy('nome')->get();
        $usuarios   = Usuario::where('ativo', true)->orderBy('nome')->get();

        return view('bens.create', compact('unidades', 'salas', 'categorias', 'usuarios'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'              => 'required|string|max:150',
            'numero_patrimonio' => 'nullable|string|max:50|unique:bens',
            'numero_serie'      => 'nullable|string|max:100',
            'descricao'         => 'nullable|string',
            'categoria_id'      => 'required|exists:categorias_bem,id',
            'unidade_id'        => 'nullable|exists:unidades,id',
            'sala_id'           => 'nullable|exists:salas,id',
            'usuario_id'        => 'nullable|exists:usuarios,id',
            'data_aquisicao'    => 'nullable|date',
            'valor'             => 'nullable|numeric|min:0',
            'marca'             => 'nullable|string|max:100',
            'modelo'            => 'nullable|string|max:100',
            'status'            => 'required|in:ativo,inativo,manutencao,descartado',
            'observacoes'       => 'nullable|string',
        ]);

        Bem::create($validated);

        return redirect()->route('bens.index')->with('success', 'Bem cadastrado com sucesso!');
    }

    public function show(Bem $bem)
    {
        $bem->load(['categoria', 'unidade', 'sala', 'usuario']);
        return view('bens.show', compact('bem'));
    }

    public function edit(Bem $bem)
    {
        $unidades   = Unidade::where('ativo', true)->orderBy('nome')->get();
        $salas      = $bem->unidade_id
            ? Sala::where('unidade_id', $bem->unidade_id)->where('ativo', true)->orderBy('nome')->get()
            : collect();
        $categorias = CategoriaBem::orderBy('nome')->get();
        $usuarios   = Usuario::where('ativo', true)->orderBy('nome')->get();

        return view('bens.edit', compact('bem', 'unidades', 'salas', 'categorias', 'usuarios'));
    }

    public function update(Request $request, Bem $bem)
    {
        $validated = $request->validate([
            'nome'              => 'required|string|max:150',
            'numero_patrimonio' => 'nullable|string|max:50|unique:bens,numero_patrimonio,' . $bem->id,
            'numero_serie'      => 'nullable|string|max:100',
            'descricao'         => 'nullable|string',
            'categoria_id'      => 'required|exists:categorias_bem,id',
            'unidade_id'        => 'nullable|exists:unidades,id',
            'sala_id'           => 'nullable|exists:salas,id',
            'usuario_id'        => 'nullable|exists:usuarios,id',
            'data_aquisicao'    => 'nullable|date',
            'valor'             => 'nullable|numeric|min:0',
            'marca'             => 'nullable|string|max:100',
            'modelo'            => 'nullable|string|max:100',
            'status'            => 'required|in:ativo,inativo,manutencao,descartado',
            'observacoes'       => 'nullable|string',
        ]);

        $bem->update($validated);

        return redirect()->route('bens.index')->with('success', 'Bem atualizado com sucesso!');
    }

    public function destroy(Bem $bem)
    {
        $bem->delete();
        return redirect()->route('bens.index')->with('success', 'Bem removido com sucesso!');
    }
}
