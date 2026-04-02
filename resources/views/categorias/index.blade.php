@extends('layouts.app')
@section('title', 'Categorias')
@section('page-title', 'Categorias de Bens')

@section('content')
<div class="card">
    <div class="card-header bg-transparent border-0 pt-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0"><i class="bi bi-tags me-2 text-primary"></i>{{ $categorias->total() }} categoria(s)</h6>
        <a href="{{ route('categorias.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg me-1"></i>Nova Categoria</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">Nome</th>
                        <th>Descrição</th>
                        <th>Bens</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categorias as $categoria)
                        <tr>
                            <td class="px-4 fw-semibold">{{ $categoria->nome }}</td>
                            <td>{{ $categoria->descricao ?: '-' }}</td>
                            <td><span class="badge bg-primary">{{ $categoria->bens_count }}</span></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('categorias.destroy', $categoria) }}" method="POST" onsubmit="return confirm('Remover esta categoria?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-5"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Nenhuma categoria cadastrada.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($categorias->hasPages())
        <div class="card-footer bg-transparent">{{ $categorias->links() }}</div>
    @endif
</div>
@endsection
