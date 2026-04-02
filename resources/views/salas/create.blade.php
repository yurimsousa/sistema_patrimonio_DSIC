@extends('layouts.app')
@section('title', 'Nova Sala')
@section('page-title', 'Cadastrar Sala')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-3 px-4">
                <h6 class="fw-semibold mb-0"><i class="bi bi-door-open me-2 text-primary"></i>Nova Sala</h6>
            </div>
            <div class="card-body px-4">
                <form action="{{ route('salas.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Unidade <span class="text-danger">*</span></label>
                            <select name="unidade_id" class="form-select @error('unidade_id') is-invalid @enderror" required>
                                <option value="">Selecione a unidade...</option>
                                @foreach($unidades as $u)
                                    <option value="{{ $u->id }}" {{ old('unidade_id') == $u->id ? 'selected' : '' }}>{{ $u->nome }}</option>
                                @endforeach
                            </select>
                            @error('unidade_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome') }}" placeholder="Ex: Sala de Reuniões" required>
                            @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Número</label>
                            <input type="text" name="numero" class="form-control" value="{{ old('numero') }}" placeholder="Ex: 101">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Andar</label>
                            <input type="text" name="andar" class="form-control" value="{{ old('andar') }}" placeholder="Ex: 1°">
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="ativo" value="1" id="ativo" {{ old('ativo', '1') ? 'checked' : '' }}>
                                <label class="form-check-label" for="ativo">Sala ativa</label>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Salvar</button>
                        <a href="{{ route('salas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
