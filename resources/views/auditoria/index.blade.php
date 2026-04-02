@extends('layouts.app')
@section('title', 'Auditoria')
@section('page-title', 'Log de Auditoria')

@section('content')

<!-- Cards resumo -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                    <i class="bi bi-journal-text text-primary fs-4"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4">{{ number_format($totalLogs) }}</div>
                    <div class="text-muted small">Total de Registros</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                    <i class="bi bi-calendar-check text-success fs-4"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4">{{ $hojeCount }}</div>
                    <div class="text-muted small">Ações Hoje</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 p-3">
                    <i class="bi bi-calendar-week text-info fs-4"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4">{{ $semanaCount }}</div>
                    <div class="text-muted small">Últimos 7 dias</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card mb-3">
    <div class="card-body py-3 px-4">
        <form method="GET" action="{{ route('auditoria.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small mb-1">Buscar descrição</label>
                    <input type="text" name="busca" class="form-control form-control-sm" placeholder="Ex: Bem, usuário..." value="{{ request('busca') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Evento</label>
                    <select name="evento" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="created" {{ request('evento') == 'created' ? 'selected' : '' }}>Criação</option>
                        <option value="updated" {{ request('evento') == 'updated' ? 'selected' : '' }}>Atualização</option>
                        <option value="deleted" {{ request('evento') == 'deleted' ? 'selected' : '' }}>Remoção</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Módulo</label>
                    <select name="modelo" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="Bem" {{ request('modelo') == 'Bem' ? 'selected' : '' }}>Bens</option>
                        <option value="Usuario" {{ request('modelo') == 'Usuario' ? 'selected' : '' }}>Usuários</option>
                        <option value="Unidade" {{ request('modelo') == 'Unidade' ? 'selected' : '' }}>Unidades</option>
                        <option value="Sala" {{ request('modelo') == 'Sala' ? 'selected' : '' }}>Salas</option>
                        <option value="CategoriaBem" {{ request('modelo') == 'CategoriaBem' ? 'selected' : '' }}>Categorias</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Operador</label>
                    <select name="usuario_id" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        @foreach($usuarios as $u)
                            <option value="{{ $u->id }}" {{ request('usuario_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label small mb-1">De</label>
                    <input type="date" name="data_inicio" class="form-control form-control-sm" value="{{ request('data_inicio') }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label small mb-1">Até</label>
                    <input type="date" name="data_fim" class="form-control form-control-sm" value="{{ request('data_fim') }}">
                </div>
                <div class="col-auto d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                    <a href="{{ route('auditoria.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabela de logs -->
<div class="card">
    <div class="card-header bg-transparent border-0 pt-3 px-4">
        <h6 class="fw-semibold mb-0">
            <i class="bi bi-shield-check me-2 text-primary"></i>
            {{ $logs->total() }} registro(s) encontrado(s)
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">Data/Hora</th>
                        <th>Operador</th>
                        <th>Evento</th>
                        <th>Módulo</th>
                        <th>Descrição</th>
                        <th>Detalhes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
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
                            $modeloLabel = class_basename($log->subject_type ?? '');
                        @endphp
                        <tr>
                            <td class="px-4">
                                <div class="fw-semibold small">{{ $log->created_at->format('d/m/Y') }}</div>
                                <div class="text-muted" style="font-size:.75rem;">{{ $log->created_at->format('H:i:s') }}</div>
                            </td>
                            <td>
                                @if($log->causer)
                                    <div class="small fw-semibold">{{ $log->causer->name }}</div>
                                    <span class="badge bg-{{ $log->causer->role_color }}" style="font-size:.65rem;">{{ $log->causer->role_label }}</span>
                                @else
                                    <span class="text-muted small">Sistema</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $eventoColor }}">
                                    <i class="bi bi-{{ $log->event == 'created' ? 'plus' : ($log->event == 'updated' ? 'pencil' : 'trash') }}-circle me-1"></i>
                                    {{ $eventoLabel }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border small">{{ $modeloLabel ?: '-' }}</span>
                                @if($log->subject_id)
                                    <div class="text-muted" style="font-size:.72rem;">ID: {{ $log->subject_id }}</div>
                                @endif
                            </td>
                            <td style="max-width:280px;">
                                <span class="small">{{ $log->description }}</span>
                            </td>
                            <td>
                                <a href="{{ route('auditoria.show', $log) }}" class="btn btn-sm btn-outline-secondary" title="Ver detalhes">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="bi bi-shield-check fs-2 d-block mb-2 text-muted"></i>
                                Nenhum registro de auditoria encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
        <div class="card-footer bg-transparent">
            {{ $logs->links() }}
        </div>
    @endif
</div>
@endsection
