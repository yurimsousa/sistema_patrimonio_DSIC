@extends('layouts.app')
@section('title', $sala->nome)
@section('page-title', 'Detalhes da Sala')

@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body px-4">
                <h5 class="fw-bold">{{ $sala->nome }}</h5>
                <div class="text-muted small mb-2">
                    @if($sala->numero) Número: {{ $sala->numero }} @endif
                    @if($sala->andar) | Andar: {{ $sala->andar }} @endif
                </div>
                <div class="mb-2">
                    <span class="text-muted small">Unidade: </span>
                    <a href="{{ route('unidades.show', $sala->unidade) }}">{{ $sala->unidade->nome }}</a>
                </div>
                <span class="badge bg-{{ $sala->ativo ? 'success' : 'secondary' }}">{{ $sala->ativo ? 'Ativa' : 'Inativa' }}</span>
                <hr>
                <div class="text-center">
                    <div class="fs-4 fw-bold text-primary">{{ $sala->bens->count() }}</div>
                    <div class="text-muted small">Bens nesta sala</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-3 px-4">
                <h6 class="fw-semibold mb-0"><i class="bi bi-laptop me-2 text-primary"></i>Bens nesta Sala</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Bem</th>
                                <th>Categoria</th>
                                <th>Responsável</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sala->bens as $bem)
                                <tr>
                                    <td class="px-4">
                                        <a href="{{ route('bens.show', $bem->id) }}" class="fw-semibold text-decoration-none">{{ $bem->nome }}</a>
                                        @if($bem->numero_patrimonio)<br><small class="text-muted">{{ $bem->numero_patrimonio }}</small>@endif
                                    </td>
                                    <td>{{ $bem->categoria->nome ?? '-' }}</td>
                                    <td>{{ $bem->usuario->nome ?? '-' }}</td>
                                    <td><span class="badge bg-{{ $bem->status_color }}">{{ $bem->status_label }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">Nenhum bem nesta sala.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex gap-2">
    <a href="{{ route('salas.edit', $sala) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
    <a href="{{ route('salas.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Voltar</a>
</div>
@endsection
