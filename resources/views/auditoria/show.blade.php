@extends('layouts.app')
@section('title', 'Detalhe da Auditoria')
@section('page-title', 'Detalhe do Registro de Auditoria')

@section('content')
@php
    $eventoColor = match($log->event) {
        'created' => 'success',
        'updated' => 'warning',
        'deleted' => 'danger',
        default   => 'secondary',
    };
    $eventoLabel = match($log->event) {
        'created' => 'Criação',
        'updated' => 'Atualização',
        'deleted' => 'Remoção',
        default   => $log->event ?? 'Sistema',
    };
@endphp

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body px-4">
                <h6 class="fw-semibold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Informações Gerais</h6>
                <div class="mb-3">
                    <div class="text-muted small">Data/Hora</div>
                    <div class="fw-semibold">{{ $log->created_at->format('d/m/Y \à\s H:i:s') }}</div>
                    <div class="text-muted small">{{ $log->created_at->diffForHumans() }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">Evento</div>
                    <span class="badge bg-{{ $eventoColor }} fs-6">{{ $eventoLabel }}</span>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">Módulo</div>
                    <div class="fw-semibold">{{ class_basename($log->subject_type ?? 'N/A') }}</div>
                    @if($log->subject_id)
                        <div class="text-muted small">ID: {{ $log->subject_id }}</div>
                    @endif
                </div>
                <div class="mb-3">
                    <div class="text-muted small">Log Name</div>
                    <div class="text-muted small font-monospace">{{ $log->log_name }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body px-4">
                <h6 class="fw-semibold mb-3"><i class="bi bi-person me-2 text-info"></i>Operador</h6>
                @if($log->causer)
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="bi bi-person text-primary fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $log->causer->name }}</div>
                            <div class="text-muted small">{{ $log->causer->email }}</div>
                            <span class="badge bg-{{ $log->causer->role_color }}">{{ $log->causer->role_label }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-muted">
                        <i class="bi bi-robot me-2"></i>Ação do sistema (sem operador)
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body px-4">
                <h6 class="fw-semibold mb-3"><i class="bi bi-chat-text me-2 text-success"></i>Descrição</h6>
                <p class="mb-0">{{ $log->description }}</p>
            </div>
        </div>

        @if($log->properties && $log->properties->count())
            @if($log->event === 'updated' && $log->properties->has('old') && $log->properties->has('attributes'))
                <div class="card mb-3">
                    <div class="card-header bg-transparent border-0 pt-3 px-4">
                        <h6 class="fw-semibold mb-0"><i class="bi bi-arrow-left-right me-2 text-warning"></i>Campos Alterados</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4">Campo</th>
                                        <th><span class="text-danger"><i class="bi bi-dash-circle me-1"></i>Antes</span></th>
                                        <th><span class="text-success"><i class="bi bi-plus-circle me-1"></i>Depois</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($log->properties['attributes'] as $campo => $novoValor)
                                        @php $valorAntigo = $log->properties['old'][$campo] ?? '-'; @endphp
                                        <tr>
                                            <td class="px-4 fw-semibold small text-uppercase text-muted">{{ str_replace('_', ' ', $campo) }}</td>
                                            <td>
                                                <span class="badge bg-danger bg-opacity-10 text-danger font-monospace" style="font-size:.8rem;">
                                                    {{ is_array($valorAntigo) ? json_encode($valorAntigo) : ($valorAntigo ?? 'null') }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success bg-opacity-10 text-success font-monospace" style="font-size:.8rem;">
                                                    {{ is_array($novoValor) ? json_encode($novoValor) : ($novoValor ?? 'null') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <div class="card mb-3">
                    <div class="card-header bg-transparent border-0 pt-3 px-4">
                        <h6 class="fw-semibold mb-0">
                            <i class="bi bi-database me-2 text-primary"></i>
                            {{ $log->event === 'created' ? 'Dados Cadastrados' : 'Dados do Registro' }}
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4">Campo</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($log->properties->get('attributes', $log->properties) as $campo => $valor)
                                        <tr>
                                            <td class="px-4 fw-semibold small text-uppercase text-muted">{{ str_replace('_', ' ', $campo) }}</td>
                                            <td class="font-monospace small">{{ is_array($valor) ? json_encode($valor) : ($valor ?? 'null') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        @endif

        <div class="card">
            <div class="card-body px-4">
                <h6 class="fw-semibold mb-2"><i class="bi bi-code-slash me-2 text-secondary"></i>Dados Brutos</h6>
                <pre class="bg-light p-3 rounded small mb-0" style="max-height:200px;overflow:auto;">{{ json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('auditoria.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Voltar para Auditoria
    </a>
</div>
@endsection
