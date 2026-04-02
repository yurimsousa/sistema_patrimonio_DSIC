@extends('layouts.app')
@section('title', 'Salas')
@section('page-title', 'Salas')

@section('content')
<div class="card mb-3">
    <div class="card-body py-3 px-4">
        <form method="GET">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="busca" class="form-control form-control-sm" placeholder="Nome ou número da sala..." value="{{ request('busca') }}">
                </div>
                <div class="col-md-3">
                    <select name="unidade_id" class="form-select form-select-sm">
                        <option value="">Todas as unidades</option>
                        @foreach($unidades as $u)
                            <option value="{{ $u->id }}" {{ request('unidade_id') == $u->id ? 'selected' : '' }}>{{ $u->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                    <a href="{{ route('salas.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-transparent border-0 pt-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0"><i class="bi bi-door-open me-2 text-primary"></i>{{ $salas->total() }} sala(s)</h6>
        <a href="{{ route('salas.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Nova Sala</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">Nome</th>
                        <th>Número</th>
                        <th>Andar</th>
                        <th>Unidade</th>
                        <th>Bens</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salas as $sala)
                        <tr>
                            <td class="px-4 fw-semibold">{{ $sala->nome }}</td>
                            <td>{{ $sala->numero ?: '-' }}</td>
                            <td>{{ $sala->andar ?: '-' }}</td>
                            <td>{{ $sala->unidade->nome ?? '-' }}</td>
                            <td><span class="badge bg-primary">{{ $sala->bens_count }}</span></td>
                            <td><span class="badge bg-{{ $sala->ativo ? 'success' : 'secondary' }}">{{ $sala->ativo ? 'Ativa' : 'Inativa' }}</span></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('salas.show', $sala) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('salas.edit', $sala) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('salas.destroy', $sala) }}" method="POST" onsubmit="return confirm('Remover esta sala?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-5"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Nenhuma sala encontrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($salas->hasPages())
        <div class="card-footer bg-transparent">{{ $salas->links() }}</div>
    @endif
</div>
@endsection
