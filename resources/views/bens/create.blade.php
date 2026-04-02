@extends('layouts.app')
@section('title', 'Novo Bem')
@section('page-title', 'Cadastrar Novo Bem')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-3 px-4">
                <h6 class="fw-semibold mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>Novo Bem Patrimonial</h6>
            </div>
            <div class="card-body px-4">
                <form action="{{ route('bens.store') }}" method="POST">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Nome do Bem <span class="text-danger">*</span></label>
                            <input type="text" name="nome" class="form-control @error('nome') is-invalid @enderror"
                                value="{{ old('nome') }}" placeholder="Ex: Notebook Dell Inspiron" required>
                            @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">N° Patrimônio</label>
                            <input type="text" name="numero_patrimonio" class="form-control @error('numero_patrimonio') is-invalid @enderror"
                                value="{{ old('numero_patrimonio') }}" placeholder="Ex: 2024-001">
                            @error('numero_patrimonio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Categoria <span class="text-danger">*</span></label>
                            <select name="categoria_id" class="form-select @error('categoria_id') is-invalid @enderror" required>
                                <option value="">Selecione...</option>
                                @foreach($categorias as $c)
                                    <option value="{{ $c->id }}" {{ old('categoria_id') == $c->id ? 'selected' : '' }}>{{ $c->nome }}</option>
                                @endforeach
                            </select>
                            @error('categoria_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Marca</label>
                            <input type="text" name="marca" class="form-control" value="{{ old('marca') }}" placeholder="Ex: Dell, Apple, Samsung">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Modelo</label>
                            <input type="text" name="modelo" class="form-control" value="{{ old('modelo') }}" placeholder="Ex: Inspiron 15">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Número de Série</label>
                            <input type="text" name="numero_serie" class="form-control" value="{{ old('numero_serie') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Data de Aquisição</label>
                            <input type="date" name="data_aquisicao" class="form-control" value="{{ old('data_aquisicao') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Valor (R$)</label>
                            <input type="number" name="valor" class="form-control" step="0.01" min="0" value="{{ old('valor') }}" placeholder="0,00">
                        </div>

                        <div class="col-12"><hr class="my-1"><p class="text-muted small mb-0">Localização</p></div>

                        <div class="col-md-4">
                            <label class="form-label">Unidade</label>
                            <select name="unidade_id" id="unidade_id" class="form-select">
                                <option value="">Nenhuma</option>
                                @foreach($unidades as $u)
                                    <option value="{{ $u->id }}" {{ old('unidade_id') == $u->id ? 'selected' : '' }}>{{ $u->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Sala</label>
                            <select name="sala_id" id="sala_id" class="form-select">
                                <option value="">Nenhuma</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Usuário Responsável</label>
                            <select name="usuario_id" class="form-select">
                                <option value="">Nenhum</option>
                                @foreach($usuarios as $u)
                                    <option value="{{ $u->id }}" {{ old('usuario_id') == $u->id ? 'selected' : '' }}>{{ $u->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="ativo" {{ old('status', 'ativo') == 'ativo' ? 'selected' : '' }}>Ativo</option>
                                <option value="inativo" {{ old('status') == 'inativo' ? 'selected' : '' }}>Inativo</option>
                                <option value="manutencao" {{ old('status') == 'manutencao' ? 'selected' : '' }}>Em Manutenção</option>
                                <option value="descartado" {{ old('status') == 'descartado' ? 'selected' : '' }}>Descartado</option>
                            </select>
                            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-8">
                            <label class="form-label">Descrição</label>
                            <textarea name="descricao" class="form-control" rows="2" placeholder="Descreva o bem...">{{ old('descricao') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Observações</label>
                            <textarea name="observacoes" class="form-control" rows="2">{{ old('observacoes') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Salvar</button>
                        <a href="{{ route('bens.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('unidade_id').addEventListener('change', function () {
    const id = this.value;
    const sel = document.getElementById('sala_id');
    sel.innerHTML = '<option value="">Nenhuma</option>';
    if (!id) return;
    fetch(`/api/salas-por-unidade/${id}`)
        .then(r => r.json())
        .then(salas => salas.forEach(s => {
            const o = document.createElement('option');
            o.value = s.id;
            o.textContent = s.nome + (s.numero ? ` (${s.numero})` : '');
            sel.appendChild(o);
        }));
});
</script>
@endpush
