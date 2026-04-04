@extends('layouts.app')
@section('title', $usuario->nome)
@section('page-title', 'Detalhes do Usuário')

@section('content')
<div class="row">
    <div class="col-lg-4 mb-3">
        <div class="card">
            <div class="card-body px-4 text-center py-4">
                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;">
                    <i class="bi bi-person text-primary fs-2"></i>
                </div>
                <h5 class="fw-bold mb-1">{{ $usuario->nome }}</h5>
                <p class="text-muted mb-1">{{ $usuario->cargo ?: 'Sem cargo' }}</p>
                <span class="badge bg-{{ $usuario->ativo ? 'success' : 'secondary' }}">{{ $usuario->ativo ? 'Ativo' : 'Inativo' }}</span>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-body px-4">
                <h6 class="fw-semibold mb-3">Informações</h6>
                <div class="mb-2">
                    <div class="text-muted small">Matrícula</div>
                    <div>{{ $usuario->matricula ?: '-' }}</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted small">E-mail</div>
                    <div>{{ $usuario->email }}</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted small">Telefone</div>
                    <div>{{ $usuario->telefone ?: '-' }}</div>
                </div>
                <div class="mb-2">
                    <div class="text-muted small">Unidade</div>
                    <div>{{ $usuario->unidade->nome ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-3 px-4">
                <h6 class="fw-semibold mb-0">
                    <i class="bi bi-laptop me-2 text-primary"></i>
                    Bens Atribuídos ({{ $usuario->bens->count() }})
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Bem</th>
                                <th>Categoria</th>
                                <th>Sala</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usuario->bens as $bem)
                                <tr>
                                    <td class="px-4">
                                        <a href="{{ route('bens.show', $bem->id) }}" class="fw-semibold text-decoration-none">{{ $bem->nome }}</a>
                                        @if($bem->numero_patrimonio)<br><small class="text-muted">{{ $bem->numero_patrimonio }}</small>@endif
                                    </td>
                                    <td>{{ $bem->categoria->nome ?? '-' }}</td>
                                    <td>{{ $bem->sala->nome ?? '-' }}</td>
                                    <td><span class="badge bg-{{ $bem->status_color }}">{{ $bem->status_label }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">Nenhum bem atribuído.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex gap-2 mt-2">
    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Editar</a>
    <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Voltar</a>
</div>
@endsection
