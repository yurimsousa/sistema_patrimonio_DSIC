@extends('layouts.app')
@section('title', 'Bens')
@section('page-title', 'Bens Patrimoniais')

@section('content')
<div class="card mb-3">
    <div class="card-body py-3 px-4">
        <form method="GET" action="{{ route('bens.index') }}">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small mb-1">Buscar</label>
                    <input type="text" name="busca" class="form-control form-control-sm" placeholder="Nome, patrimônio, série..." value="{{ request('busca') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Unidade</label>
                    <select name="unidade_id" id="filtro-unidade" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($unidades as $u)
                            <option value="{{ $u->id }}" {{ request('unidade_id') == $u->id ? 'selected' : '' }}>{{ $u->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Sala</label>
                    <select name="sala_id" id="filtro-sala" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($salas as $s)
                            <option value="{{ $s->id }}" {{ request('sala_id') == $s->id ? 'selected' : '' }}>{{ $s->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Categoria</label>
                    <select name="categoria_id" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        @foreach($categorias as $c)
                            <option value="{{ $c->id }}" {{ request('categoria_id') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="ativo" {{ request('status') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                        <option value="inativo" {{ request('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                        <option value="manutencao" {{ request('status') == 'manutencao' ? 'selected' : '' }}>Em Manutenção</option>
                        <option value="descartado" {{ request('status') == 'descartado' ? 'selected' : '' }}>Descartado</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i></button>
                    <a href="{{ route('bens.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x"></i></a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-transparent border-0 pt-3 px-4 d-flex justify-content-between align-items-center">
        <h6 class="fw-semibold mb-0">
            <i class="bi bi-laptop me-2 text-primary"></i>
            {{ $bens->total() }} bem(ns) encontrado(s)
        </h6>
        <a href="{{ route('bens.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg me-1"></i>Novo Bem
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4">Bem</th>
                        <th>Categoria</th>
                        <th>Unidade</th>
                        <th>Sala</th>
                        <th>Usuário</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bens as $bem)
                        <tr>
                            <td class="px-4">
                                <div class="fw-semibold">{{ $bem->nome }}</div>
                                @if($bem->numero_patrimonio)
                                    <small class="text-muted">Pat: {{ $bem->numero_patrimonio }}</small>
                                @endif
                                @if($bem->marca || $bem->modelo)
                                    <br><small class="text-muted">{{ trim($bem->marca . ' ' . $bem->modelo) }}</small>
                                @endif
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $bem->categoria->nome ?? '-' }}</span></td>
                            <td>{{ $bem->unidade->nome ?? '-' }}</td>
                            <td>{{ $bem->sala->nome ?? '-' }}</td>
                            <td>{{ $bem->usuario->nome ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $bem->status_color }}">{{ $bem->status_label }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('bens.show', $bem) }}" class="btn btn-sm btn-outline-secondary" title="Visualizar">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('bens.edit', $bem) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('bens.destroy', $bem) }}" method="POST" onsubmit="return confirm('Deseja remover este bem?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Remover">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Nenhum bem encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($bens->hasPages())
        <div class="card-footer bg-transparent">
            {{ $bens->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.getElementById('filtro-unidade').addEventListener('change', function () {
    const unidadeId = this.value;
    const salaSelect = document.getElementById('filtro-sala');
    salaSelect.innerHTML = '<option value="">Todas</option>';

    if (!unidadeId) return;

    fetch(`/api/salas-por-unidade/${unidadeId}`)
        .then(r => r.json())
        .then(salas => {
            salas.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.nome + (s.numero ? ` (${s.numero})` : '');
                salaSelect.appendChild(opt);
            });
        });
});
</script>
@endpush
