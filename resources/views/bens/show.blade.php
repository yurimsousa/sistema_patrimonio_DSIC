@extends('layouts.app')
@section('title', $bem->nome)
@section('page-title', 'Detalhes do Bem')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header bg-transparent border-0 pt-3 px-4 d-flex justify-content-between align-items-center">
                <h6 class="fw-semibold mb-0"><i class="bi bi-laptop me-2 text-primary"></i>{{ $bem->nome }}</h6>
                <span class="badge bg-{{ $bem->status_color }} fs-6">{{ $bem->status_label }}</span>
            </div>
            <div class="card-body px-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="text-muted small">N° Patrimônio</div>
                        <div class="fw-semibold">{{ $bem->numero_patrimonio ?: '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted small">N° Série</div>
                        <div class="fw-semibold">{{ $bem->numero_serie ?: '-' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Categoria</div>
                        <div class="fw-semibold">{{ $bem->categoria->nome ?? '-' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Marca</div>
                        <div class="fw-semibold">{{ $bem->marca ?: '-' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Modelo</div>
                        <div class="fw-semibold">{{ $bem->modelo ?: '-' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Data Aquisição</div>
                        <div class="fw-semibold">{{ $bem->data_aquisicao?->format('d/m/Y') ?: '-' }}</div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-muted small">Valor</div>
                        <div class="fw-semibold">{{ $bem->valor ? 'R$ ' . number_format($bem->valor, 2, ',', '.') : '-' }}</div>
                    </div>
                    @if($bem->descricao)
                        <div class="col-12">
                            <div class="text-muted small">Descrição</div>
                            <div>{{ $bem->descricao }}</div>
                        </div>
                    @endif
                    @if($bem->observacoes)
                        <div class="col-12">
                            <div class="text-muted small">Observações</div>
                            <div class="alert alert-light py-2 mb-0">{{ $bem->observacoes }}</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body px-4">
                <h6 class="fw-semibold mb-3"><i class="bi bi-geo-alt me-2 text-success"></i>Localização</h6>
                <div class="mb-2">
                    <div class="text-muted small">Unidade</div>
                    <div class="fw-semibold">
                        @if($bem->unidade)
                            <a href="{{ route('unidades.show', $bem->unidade) }}">{{ $bem->unidade->nome }}</a>
                        @else
                            -
                        @endif
                    </div>
                </div>
                <div class="mb-2">
                    <div class="text-muted small">Sala</div>
                    <div class="fw-semibold">
                        @if($bem->sala)
                            <a href="{{ route('salas.show', $bem->sala) }}">{{ $bem->sala->nome }}</a>
                        @else
                            -
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body px-4">
                <h6 class="fw-semibold mb-3"><i class="bi bi-person me-2 text-info"></i>Responsável</h6>
                @if($bem->usuario)
                    <div class="fw-semibold">
                        <a href="{{ route('usuarios.show', $bem->usuario) }}">{{ $bem->usuario->nome }}</a>
                    </div>
                    <div class="text-muted small">{{ $bem->usuario->cargo ?: '' }}</div>
                    <div class="text-muted small">{{ $bem->usuario->email }}</div>
                @else
                    <span class="text-muted">Sem responsável atribuído</span>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-body px-4">
                <h6 class="fw-semibold mb-3"><i class="bi bi-clock me-2 text-secondary"></i>Registro</h6>
                <div class="text-muted small">Cadastrado em</div>
                <div class="fw-semibold">{{ $bem->created_at?->format('d/m/Y H:i') ?? '-' }}</div>
                <div class="text-muted small mt-2">Atualizado em</div>
                <div class="fw-semibold">{{ $bem->updated_at?->format('d/m/Y H:i') ?? '-' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-2">
    <a href="{{ route('bens.edit', $bem->id) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
    <a href="{{ route('bens.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Voltar</a>
</div>
@endsection
