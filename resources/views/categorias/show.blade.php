@extends('layouts.app')
@section('title', $categoria->nome)
@section('page-title', 'Detalhes da Categoria')

@section('content')
<div class="card">
    <div class="card-header bg-transparent border-0 pt-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0"><i class="bi bi-tag me-2 text-primary"></i>{{ $categoria->nome }}</h6>
        <a href="{{ route('categorias.edit', $categoria) }}" class="btn btn-primary btn-sm"><i class="bi bi-pencil me-1"></i>Editar</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">Bem</th>
                        <th>Unidade</th>
                        <th>Sala</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categoria->bens as $bem)
                        <tr>
                            <td class="px-4"><a href="{{ route('bens.show', $bem->id) }}">{{ $bem->nome }}</a></td>
                            <td>{{ $bem->unidade->nome ?? '-' }}</td>
                            <td>{{ $bem->sala->nome ?? '-' }}</td>
                            <td><span class="badge bg-{{ $bem->status_color }}">{{ $bem->status_label }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">Nenhum bem nesta categoria.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">
    <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Voltar</a>
</div>
@endsection
