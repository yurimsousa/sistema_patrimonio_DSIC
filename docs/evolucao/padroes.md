# Padrões de Design e Arquitetura

Padrões utilizados no projeto e diretrizes para manter a consistência ao evoluir o código.

---

## Arquitetura Geral

O projeto segue a arquitetura **MVC (Model-View-Controller)** padrão do Laravel:

```
Request HTTP
    │
    ▼
routes/web.php          ← Define URL → Controller → Method
    │
    ▼
Middleware (auth, role) ← Autorização antes de chegar no controller
    │
    ▼
Controller              ← Orquestra: valida, chama Model, retorna View
    │
    ├─► Model (Eloquent) ← Acesso ao banco, regras de negócio básicas
    │
    └─► View (Blade)     ← Renderização HTML
```

---

## Padrões Utilizados

### 1. Resource Controller (RESTful)

Todos os módulos CRUD usam `Route::resource()` e `php artisan make:controller --resource`, seguindo as 7 ações convencionais:

| Método HTTP | URI                  | Action   | Route Name        |
|-------------|----------------------|----------|-------------------|
| GET         | /bens                | index    | bens.index        |
| GET         | /bens/create         | create   | bens.create       |
| POST        | /bens                | store    | bens.store        |
| GET         | /bens/{bem}          | show     | bens.show         |
| GET         | /bens/{bem}/edit     | edit     | bens.edit         |
| PUT/PATCH   | /bens/{bem}          | update   | bens.update       |
| DELETE      | /bens/{bem}          | destroy  | bens.destroy      |

**Regra:** Não criar rotas customizadas para operações que cabem no CRUD padrão.

---

### 2. Form Request Validation

Validações são centralizadas em Form Requests (quando o volume justificar), mantendo os controllers enxutos. Para controllers simples, a validação inline com `$request->validate()` é aceitável.

```php
// Inline (aceitável para regras simples)
$validated = $request->validate([
    'tombo' => 'required|unique:bens,tombo,' . $bem->id,
    'nome'  => 'required|max:200',
]);

// Form Request (preferível para muitas regras ou reutilização)
// php artisan make:request StoreBemRequest
```

---

### 3. Eloquent Relationships

Os modelos definem os relacionamentos explicitamente. Siga o padrão:

```php
// Em Bem.php
public function unidade(): BelongsTo    { return $this->belongsTo(Unidade::class); }
public function sala(): BelongsTo       { return $this->belongsTo(Sala::class); }
public function categoria(): BelongsTo  { return $this->belongsTo(CategoriaBem::class); }
public function responsavel(): BelongsTo{ return $this->belongsTo(Usuario::class, 'usuario_id'); }
```

**Regra:** Nunca fazer query manual com `DB::` quando um relacionamento Eloquent resolve.

---

### 4. Observer / Trait para Auditoria

A auditoria usa a trait `LogsActivity` do pacote `spatie/laravel-activitylog`. Cada modelo auditável declara:

```php
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Bem extends Model {
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(fn(string $event) => match($event) {
                'created' => "Bem '{$this->nome}' cadastrado",
                'updated' => "Bem '{$this->nome}' atualizado",
                'deleted' => "Bem '{$this->nome}' removido",
            });
    }
}
```

**Regra:** Nunca registrar logs manualmente em controllers — deixar a trait fazer isso.

---

### 5. Middleware para Controle de Acesso

O middleware `CheckRole` é registrado como alias `'role'` em `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias(['role' => \App\Http\Middleware\CheckRole::class]);
})
```

Uso nas rotas:

```php
Route::middleware('role:admin,auditor')->group(function () {
    // Rotas restritas
});
```

**Regra:** Toda restrição de acesso deve ser feita na camada de rota ou middleware — nunca verificar `auth()->user()->role` dentro de controllers para redirecionar.

---

### 6. Computed Properties nos Models

Atributos calculados são definidos como accessors no model com `Attribute::make()` (Laravel 9+):

```php
// Em User.php
protected function roleLabel(): Attribute {
    return Attribute::make(get: fn() => match($this->role) {
        'admin'   => 'Administrador',
        'auditor' => 'Auditor',
        default   => 'Usuário',
    });
}
```

**Regra:** Lógica de apresentação (label, cor, formato) fica no model como accessor — não nas views.

---

### 7. Layouts e Componentes Blade

Todas as views internas herdam de `layouts.app`:

```blade
@extends('layouts.app')
@section('title', 'Título da Página')
@section('page-title', 'Título no Topbar')
@section('content')
    {{-- conteúdo --}}
@endsection
```

A view de login (`auth.login`) é standalone (sem layout) por ser pré-autenticação.

---

## Convenções de Código

| Item                  | Convenção                                              |
|-----------------------|--------------------------------------------------------|
| Nomes de classes      | PascalCase (`BemController`, `CategoriaBem`)           |
| Nomes de métodos      | camelCase (`getActivitylogOptions`, `roleLabel`)       |
| Nomes de tabelas BD   | snake_case plural (`categorias_bem`, `activity_log`)  |
| Nomes de colunas BD   | snake_case (`usuario_id`, `data_aquisicao`)            |
| Rotas nomeadas        | `recurso.ação` (`bens.index`, `auditoria.show`)        |
| Views                 | `recurso/acao.blade.php` (`bens/edit.blade.php`)       |
| Variáveis de view     | snake_case singular/plural (`$bem`, `$bens`, `$logs`)  |

---

## Como Adicionar um Novo Módulo

1. Criar o model: `php artisan make:model NomeModelo -m`
2. Editar a migration com os campos necessários
3. Criar o controller: `php artisan make:controller NomeModeloController --resource`
4. Registrar a rota em `routes/web.php`: `Route::resource('nome-modulo', NomeModeloController::class)`
5. Criar as views em `resources/views/nome-modulo/` (index, create, edit, show)
6. Adicionar a trait `LogsActivity` e `getActivitylogOptions()` no model
7. Adicionar o link no sidebar em `resources/views/layouts/app.blade.php`
8. Executar `php artisan migrate`
