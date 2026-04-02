@extends('layouts.app')
@section('title', 'Editar Unidade')
@section('page-title', 'Editar Unidade')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-3 px-4">
                <h6 class="fw-semibold mb-0"><i class="bi bi-pencil me-2 text-primary"></i>Editar: {{ $unidade->nome }}</h6>
            </div>
            <div class="card-body px-4">
                <form action="{{ route('unidades.update', $unidade) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome', $unidade->nome) }}" required>
                            @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sigla</label>
                            <input type="text" name="sigla" class="form-control" value="{{ old('sigla', $unidade->sigla) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Endereço</label>
                            <input type="text" name="endereco" class="form-control" value="{{ old('endereco', $unidade->endereco) }}">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="ativo" value="1" id="ativo" {{ old('ativo', $unidade->ativo) ? 'checked' : '' }}>
                                <label class="form-check-label" for="ativo">Unidade ativa</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Salvar Alterações</button>
                        <a href="{{ route('unidades.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
