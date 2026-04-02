@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Cards de resumo -->
<div class="row g-3 mb-4">
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                    <i class="bi bi-laptop text-primary fs-4"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4">{{ $totalBens }}</div>
                    <div class="text-muted small">Total de Bens</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                    <i class="bi bi-check-circle text-success fs-4"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4">{{ $bensAtivos }}</div>
                    <div class="text-muted small">Ativos</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                    <i class="bi bi-tools text-warning fs-4"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4">{{ $bensManutencao }}</div>
                    <div class="text-muted small">Em Manutenção</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                    <i class="bi bi-trash text-danger fs-4"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4">{{ $bensDescartados }}</div>
                    <div class="text-muted small">Descartados</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-info bg-opacity-10 p-3">
                    <i class="bi bi-people text-info fs-4"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4">{{ $totalUsuarios }}</div>
                    <div class="text-muted small">Usuários</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6">
        <div class="card stat-card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle bg-secondary bg-opacity-10 p-3">
                    <i class="bi bi-building text-secondary fs-4"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4">{{ $totalUnidades }}</div>
                    <div class="text-muted small">Unidades</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Bens por categoria -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0 pb-0 pt-3 px-4">
                <h6 class="fw-semibold mb-0"><i class="bi bi-tags me-2 text-primary"></i>Bens por Categoria</h6>
            </div>
            <div class="card-body px-4">
                @forelse($bensPorCategoria as $cat)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small">{{ $cat->nome }}</span>
                            <span class="small fw-semibold">{{ $cat->bens_count }}</span>
                        </div>
                        <div class="progress" style="height:6px;">
                            <div class="progress-bar bg-primary" style="width:{{ $totalBens > 0 ? ($cat->bens_count / $totalBens * 100) : 0 }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small">Nenhuma categoria cadastrada.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Bens por unidade -->
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0 pb-0 pt-3 px-4">
                <h6 class="fw-semibold mb-0"><i class="bi bi-building me-2 text-success"></i>Top Unidades por Bens</h6>
            </div>
            <div class="card-body px-4">
                @forelse($bensPorUnidade as $unidade)
                    <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                        <div>
                            <div class="fw-semibold small">{{ $unidade->nome }}</div>
                            @if($unidade->sigla)
                                <span class="badge bg-secondary">{{ $unidade->sigla }}</span>
                            @endif
                        </div>
                        <span class="badge bg-primary rounded-pill">{{ $unidade->bens_count }} bens</span>
                    </div>
                @empty
                    <p class="text-muted small">Nenhuma unidade com bens cadastrados.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Últimos bens cadastrados -->
<div class="card">
    <div class="card-header bg-transparent border-0 pt-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0"><i class="bi bi-clock-history me-2 text-info"></i>Últimos Bens Cadastrados</h6>
        <a href="{{ route('bens.index') }}" class="btn btn-sm btn-outline-primary">Ver todos</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">Bem</th>
                        <th>Categoria</th>
                        <th>Unidade / Sala</th>
                        <th>Usuário</th>
                        <th>Status</th>
                        <th>Cadastro</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ultimosBens as $bem)
                        <tr>
                            <td class="px-4">
                                <a href="{{ route('bens.show', $bem) }}" class="text-decoration-none fw-semibold">{{ $bem->nome }}</a>
                                @if($bem->numero_patrimonio)
                                    <br><small class="text-muted">{{ $bem->numero_patrimonio }}</small>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark">{{ $bem->categoria->nome ?? '-' }}</span></td>
                            <td>
                                {{ $bem->unidade->nome ?? '-' }}
                                @if($bem->sala)
                                    <br><small class="text-muted">{{ $bem->sala->nome }}</small>
                                @endif
                            </td>
                            <td>{{ $bem->usuario->nome ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $bem->status_color }}">{{ $bem->status_label }}</span>
                            </td>
                            <td><small class="text-muted">{{ $bem->created_at->format('d/m/Y') }}</small></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Nenhum bem cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
