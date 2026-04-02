@extends('layouts.app')
@section('title', 'Usuários')
@section('page-title', 'Usuários')

@section('content')
<div class="card mb-3">
    <div class="card-body py-3 px-4">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <input type="text" name="busca" class="form-control form-control-sm" placeholder="Nome, e-mail, matrícula..." value="{{ request('busca') }}">
                </div>
                <div class="col-md-3">
                    <select name="unidade_id" class="form-select form-select-sm">
                        <option value="">Todas as unidades</option>
                        @foreach($unidades as $u)
                            <option value="{{ $u->id }}" {{ request('unidade_id') == $u->id ? 'selected' : '' }}>{{ $u->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="ativo" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="1" {{ request('ativo') === '1' ? 'selected' : '' }}>Ativos</option>
                        <option value="0" {{ request('ativo') === '0' ? 'selected' : '' }}>Inativos</option>
                    </select>
                </div>
                <div class="col-auto d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-transparent border-0 pt-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0"><i class="bi bi-people me-2 text-primary"></i>{{ $usuarios->total() }} usuário(s)</h6>
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Novo Usuário</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">Nome</th>
                        <th>Matrícula</th>
                        <th>E-mail</th>
                        <th>Cargo</th>
                        <th>Unidade</th>
                        <th>Bens</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <td class="px-4 fw-semibold">{{ $usuario->nome }}</td>
                            <td>{{ $usuario->matricula ?: '-' }}</td>
                            <td>{{ $usuario->email }}</td>
                            <td>{{ $usuario->cargo ?: '-' }}</td>
                            <td>{{ $usuario->unidade->nome ?? '-' }}</td>
                            <td><span class="badge bg-primary">{{ $usuario->bens_count ?? 0 }}</span></td>
                            <td>
                                <span class="badge bg-{{ $usuario->ativo ? 'success' : 'secondary' }}">
                                    {{ $usuario->ativo ? 'Ativo' : 'Inativo' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('usuarios.destroy', $usuario) }}" method="POST" onsubmit="return confirm('Remover este usuário?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-5"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Nenhum usuário encontrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($usuarios->hasPages())
        <div class="card-footer bg-transparent">{{ $usuarios->links() }}</div>
    @endif
</div>
@endsection
