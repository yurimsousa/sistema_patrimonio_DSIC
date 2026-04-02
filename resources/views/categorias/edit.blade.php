@extends('layouts.app')
@section('title', 'Editar Categoria')
@section('page-title', 'Editar Categoria')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-5">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-3 px-4">
                <h6 class="fw-semibold mb-0"><i class="bi bi-pencil me-2 text-primary"></i>Editar: {{ $categoria->nome }}</h6>
            </div>
            <div class="card-body px-4">
                <form action="{{ route('categorias.update', $categoria) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nome <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome', $categoria->nome) }}" required>
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição</label>
                        <input type="text" name="descricao" class="form-control" value="{{ old('descricao', $categoria->descricao) }}">
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Salvar Alterações</button>
                        <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
