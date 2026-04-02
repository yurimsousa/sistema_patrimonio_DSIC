@extends('layouts.app')
@section('title', 'Unidades')
@section('page-title', 'Unidades')

@section('content')
<div class="card">
    <div class="card-header bg-transparent border-0 pt-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0"><i class="bi bi-building me-2 text-primary"></i>{{ $unidades->total() }} unidade(s)</h6>
        <a href="{{ route('unidades.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Nova Unidade</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">Nome</th>
                        <th>Sigla</th>
                        <th>Endereço</th>
                        <th>Salas</th>
                        <th>Bens</th>
                        <th>Usuários</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($unidades as $unidade)
                        <tr>
                            <td class="px-4 fw-semibold">{{ $unidade->nome }}</td>
                            <td>{{ $unidade->sigla ?: '-' }}</td>
                            <td>{{ $unidade->endereco ?: '-' }}</td>
                            <td><span class="badge bg-info text-dark">{{ $unidade->salas_count }}</span></td>
                            <td><span class="badge bg-primary">{{ $unidade->bens_count }}</span></td>
                            <td><span class="badge bg-secondary">{{ $unidade->usuarios_count }}</span></td>
                            <td><span class="badge bg-{{ $unidade->ativo ? 'success' : 'secondary' }}">{{ $unidade->ativo ? 'Ativa' : 'Inativa' }}</span></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('unidades.show', $unidade) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('unidades.edit', $unidade) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('unidades.destroy', $unidade) }}" method="POST" onsubmit="return confirm('Remover esta unidade?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-5"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Nenhuma unidade cadastrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($unidades->hasPages())
        <div class="card-footer bg-transparent">{{ $unidades->links() }}</div>
    @endif
</div>
@endsection
