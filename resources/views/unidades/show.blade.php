@extends('layouts.app')
@section('title', $unidade->nome)
@section('page-title', 'Detalhes da Unidade')

@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body px-4">
                <h5 class="fw-bold">{{ $unidade->nome }}</h5>
                @if($unidade->sigla)<span class="badge bg-primary mb-2">{{ $unidade->sigla }}</span>@endif
                <div class="text-muted small mt-1">{{ $unidade->endereco ?: 'Sem endereço' }}</div>
                <hr>
                <div class="row text-center">
                    <div class="col-4">
                        <div class="fs-5 fw-bold text-primary">{{ $unidade->salas->count() }}</div>
                        <div class="text-muted small">Salas</div>
                    </div>
                    <div class="col-4">
                        <div class="fs-5 fw-bold text-success">{{ $unidade->bens->count() }}</div>
                        <div class="text-muted small">Bens</div>
                    </div>
                    <div class="col-4">
                        <div class="fs-5 fw-bold text-info">{{ $unidade->usuarios->count() }}</div>
                        <div class="text-muted small">Usuários</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0 pt-3 px-4">
                <h6 class="fw-semibold mb-0"><i class="bi bi-door-open me-2"></i>Salas</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Sala</th>
                                <th>Número</th>
                                <th>Andar</th>
                                <th>Bens</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($unidade->salas as $sala)
                                <tr>
                                    <td class="px-4"><a href="{{ route('salas.show', $sala) }}">{{ $sala->nome }}</a></td>
                                    <td>{{ $sala->numero ?: '-' }}</td>
                                    <td>{{ $sala->andar ?: '-' }}</td>
                                    <td><span class="badge bg-primary">{{ $sala->bens->count() }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">Nenhuma sala cadastrada.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2">
    <a href="{{ route('unidades.edit', $unidade) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
    <a href="{{ route('unidades.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Voltar</a>
</div>
@endsection
