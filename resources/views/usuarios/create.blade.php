@extends('layouts.app')
@section('title', 'Novo Usuário')
@section('page-title', 'Cadastrar Usuário')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-3 px-4">
                <h6 class="fw-semibold mb-0"><i class="bi bi-person-plus me-2 text-primary"></i>Novo Usuário</h6>
            </div>
            <div class="card-body px-4">
                <form action="{{ route('usuarios.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nome Completo <span class="text-danger">*</span></label>
                            <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome') }}" required>
                            @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Matrícula</label>
                            <input type="text" name="matricula" class="form-control @error('matricula') is-invalid @enderror" value="{{ old('matricula') }}">
                            @error('matricula')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone" class="form-control" value="{{ old('telefone') }}" placeholder="(00) 00000-0000">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cargo</label>
                            <input type="text" name="cargo" class="form-control" value="{{ old('cargo') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Unidade</label>
                            <select name="unidade_id" class="form-select">
                                <option value="">Nenhuma</option>
                                @foreach($unidades as $u)
                                    <option value="{{ $u->id }}" {{ old('unidade_id') == $u->id ? 'selected' : '' }}>{{ $u->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="ativo" value="1" id="ativo" {{ old('ativo', '1') ? 'checked' : '' }}>
                                <label class="form-check-label" for="ativo">Ativo</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Salvar</button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
