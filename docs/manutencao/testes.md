# Guia de Testes

Estratégias e procedimentos para validar o funcionamento do sistema.

---

## Testes Automatizados (PHPUnit)

O projeto possui uma suite completa de testes automatizados usando **PHPUnit** com banco **SQLite in-memory** (sem dependência de Oracle para rodar).

### Rodar todos os testes

```bash
php artisan test
```

Saída esperada:

```
Tests:    68 passed (110 assertions)
Duration: ~5s
```

### Rodar por suite

```bash
php artisan test --testsuite=Unit     # Apenas testes unitários
php artisan test --testsuite=Feature  # Apenas testes de feature
```

### Rodar um arquivo específico

```bash
php artisan test tests/Feature/BemControllerTest.php
php artisan test tests/Feature/AuthorizationTest.php
```

---

## Estrutura dos Testes

```
tests/
├── Unit/
│   ├── BemTest.php           # Accessors de status, casts, fillable
│   └── UserTest.php          # isAdmin(), isAuditor(), labels, $hidden
└── Feature/
    ├── BemControllerTest.php  # CRUD completo + filtros + validações
    ├── AuthorizationTest.php  # Controle de acesso por role
    ├── ApiSalasTest.php       # API AJAX de salas
    └── ExampleTest.php        # Smoke tests básicos
```

### Testes Unitários (`tests/Unit/`)

Testam lógica isolada dos models, **sem acesso ao banco de dados**.

| Arquivo | O que testa |
|---------|-------------|
| `BemTest.php` | `status_label`, `status_color`, `$fillable`, cast de `valor` e `data_aquisicao` |
| `UserTest.php` | `isAdmin()`, `isAuditor()`, `role_label`, `role_color`, `$hidden` de password |

### Testes de Feature (`tests/Feature/`)

Testam o fluxo HTTP completo: requisição → controller → banco → resposta.

#### `BemControllerTest.php` — 23 testes

| Grupo | Cenários testados |
|-------|-------------------|
| Listagem | Exibe bens, filtra por status, busca por nome, filtra por unidade |
| Show | Exibe detalhes, retorna 404 para ID inexistente |
| Create/Store | Formulário carrega dados, cadastro válido, persiste todos os campos |
| Validações | Falha sem nome, sem categoria, status inválido, patrimônio duplicado, valor negativo, categoria inexistente |
| Edit/Update | Formulário carrega bem, atualização válida, permite mesmo patrimônio do próprio bem, rejeita patrimônio de outro bem |
| Destroy | Remove do banco, 404 para inexistente |
| Cautela | Página carrega com dados do bem |

#### `AuthorizationTest.php` — 15 testes

| Perfil | Cenários testados |
|--------|-------------------|
| Visitante | Redirecionado para `/login` em todas as rotas |
| Usuário comum | Pode listar; não pode criar, editar, excluir, exportar, acessar auditoria |
| Auditor | Pode exportar e acessar auditoria; não pode cadastrar ou excluir |
| Admin | Pode acessar criação, auditoria e exportação |

#### `ApiSalasTest.php` — 6 testes

| Cenário |
|---------|
| Retorna salas ativas da unidade correta |
| Não retorna salas inativas |
| Não retorna salas de outras unidades |
| Retorna array vazio para unidade sem salas |
| Rejeita parâmetro não numérico (404) |
| Exige autenticação (redireciona para /login) |

---

## Factories Disponíveis

As factories permitem criar dados de teste facilmente:

```php
// Usuários por perfil
User::factory()->admin()->create();
User::factory()->auditor()->create();
User::factory()->create(); // role=usuario

// Bens por status
Bem::factory()->create();             // ativo
Bem::factory()->inativo()->create();
Bem::factory()->emManutencao()->create();
Bem::factory()->descartado()->create();

// Entidades relacionadas
CategoriaBem::factory()->create();
Unidade::factory()->create();
Unidade::factory()->inativa()->create();
Sala::factory()->create();
Usuario::factory()->create();
Usuario::factory()->inativo()->create();
```

---

## Configuração do Ambiente de Testes

O arquivo `phpunit.xml` configura automaticamente:

- Banco: **SQLite in-memory** (sem instalar Oracle)
- Sessão: `array` (sem persistência entre requisições)
- Cache: `array`
- Email: `array` (não envia e-mails reais)
- `APP_ENV=testing`

Cada teste usa `RefreshDatabase` — o banco é recriado do zero a cada teste, garantindo isolamento total.

---

## Testes Manuais

### Verificação do Ambiente

```bash
php artisan serve
php artisan route:list
php artisan migrate:status
```

### Fluxo Básico de Login

1. Acesse `http://localhost:8000`
2. Você deve ser redirecionado para `/login` (HTTP 302 → 200)
3. Faça login com `admin@patrimonio.com` / `admin123`
4. Verifique que o dashboard carrega com os cards de resumo

### Verificação de Permissões por Perfil

| Usuário | Acesso esperado |
|---------|-----------------|
| admin@patrimonio.com | Acesso total: todas as seções + auditoria + exportação |
| auditor@patrimonio.com | Leitura + auditoria + exportação (sem criar/editar/excluir) |
| operador@patrimonio.com | Listagem e visualização, sem acesso à auditoria |

### Teste do Filtro de Salas por Unidade

1. Vá em **Bens > Novo Bem** ou **Editar Bem**
2. Selecione uma unidade no campo Unidade
3. O campo Sala deve atualizar dinamicamente via AJAX
4. Troque a unidade — as salas devem ser recarregadas

---

## Checklist de Validação Pós-Deploy

- [ ] Todos os 68 testes passando: `php artisan test`
- [ ] Login funcionando para os três perfis
- [ ] Dashboard exibe dados corretos
- [ ] CRUD de Bens: criar, editar, visualizar, excluir
- [ ] Exportação XLSX funciona (admin e auditor)
- [ ] Importação de planilha funciona (admin)
- [ ] Cautela de bem gera documento corretamente
- [ ] Filtro dinâmico de salas por unidade (AJAX)
- [ ] Filtros na listagem de bens funcionando
- [ ] Auditoria: log registrado para criar/editar/excluir bens
- [ ] Perfil `operador` não acessa `/auditoria` (403)
- [ ] Perfil `auditor` acessa auditoria mas não altera dados
- [ ] Sem erros no `storage/logs/laravel.log`

---

## Limpeza de Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

Para produção, reconstruir o cache:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
