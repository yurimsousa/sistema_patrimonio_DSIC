<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Unidade;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $query = Usuario::with('unidade');

        if ($request->filled('busca')) {
            $busca = $request->busca;
            $query->where(function ($q) use ($busca) {
                $q->where('nome', 'like', "%{$busca}%")
                  ->orWhere('email', 'like', "%{$busca}%")
                  ->orWhere('matricula', 'like', "%{$busca}%");
            });
        }

        if ($request->filled('unidade_id')) {
            $query->where('unidade_id', $request->unidade_id);
        }

        if ($request->filled('ativo')) {
            $query->where('ativo', $request->ativo);
        }

        $usuarios = $query->orderBy('nome')->paginate(15)->withQueryString();
        $unidades = Unidade::where('ativo', true)->orderBy('nome')->get();

        return view('usuarios.index', compact('usuarios', 'unidades'));
    }

    public function create()
    {
        $unidades = Unidade::where('ativo', true)->orderBy('nome')->get();
        return view('usuarios.create', compact('unidades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'       => 'required|string|max:150',
            'matricula'  => 'nullable|string|max:30|unique:usuarios',
            'email'      => 'required|email|max:150|unique:usuarios',
            'telefone'   => 'nullable|string|max:20',
            'cargo'      => 'nullable|string|max:100',
            'unidade_id' => 'nullable|exists:unidades,id',
            'ativo'      => 'boolean',
        ]);

        $validated['ativo'] = $request->boolean('ativo', true);

        Usuario::create($validated);

        return redirect()->route('usuarios.index')->with('success', 'Usuário cadastrado com sucesso!');
    }

    public function show(Usuario $usuario)
    {
        $usuario->load(['unidade', 'bens.categoria', 'bens.sala']);
        return view('usuarios.show', compact('usuario'));
    }

    public function edit(Usuario $usuario)
    {
        $unidades = Unidade::where('ativo', true)->orderBy('nome')->get();
        return view('usuarios.edit', compact('usuario', 'unidades'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $validated = $request->validate([
            'nome'       => 'required|string|max:150',
            'matricula'  => 'nullable|string|max:30|unique:usuarios,matricula,' . $usuario->id,
            'email'      => 'required|email|max:150|unique:usuarios,email,' . $usuario->id,
            'telefone'   => 'nullable|string|max:20',
            'cargo'      => 'nullable|string|max:100',
            'unidade_id' => 'nullable|exists:unidades,id',
            'ativo'      => 'boolean',
        ]);

        $validated['ativo'] = $request->boolean('ativo', true);

        $usuario->update($validated);

        return redirect()->route('usuarios.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(Usuario $usuario)
    {
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuário removido com sucesso!');
    }
}
