# Guia de Testes

Estratégias e procedimentos para validar o funcionamento do sistema.

---

## Testes Manuais

### Verificação do Ambiente

```bash
# Confirmar que o servidor está rodando
php artisan serve

# Listar todas as rotas registradas
php artisan route:list

# Verificar status das migrations
php artisan migrate:status
```

### Fluxo Básico de Login

1. Acesse `http://localhost:8000`
2. Você deve ser redirecionado para `/login` (HTTP 302 → 200)
3. Faça login com `admin@patrimonio.com` / `admin123`
4. Verifique que o dashboard carrega com os cards de resumo

### Verificação de Permissões por Perfil

| Usuário               | Acesso esperado                                       |
|-----------------------|-------------------------------------------------------|
| admin@patrimonio.com  | Acesso total: todas as seções + auditoria             |
| auditor@patrimonio.com| Apenas leitura + auditoria (sem criar/editar/excluir) |
| operador@patrimonio.com| CRUD nos módulos, sem acesso à auditoria             |

Para testar a restrição de auditoria:
1. Faça login como `operador@patrimonio.com`
2. Acesse diretamente `http://localhost:8000/auditoria`
3. Deve retornar **HTTP 403 Forbidden**

### Teste de CRUD — Bens

1. Navegue para **Bens > Novo Bem**
2. Preencha todos os campos obrigatórios (tombo, nome, categoria, unidade)
3. Salve — verifique o redirecionamento e a mensagem de sucesso
4. Edite o bem criado
5. Verifique se a linha aparece no **Log de Auditoria** com evento `created` e `updated`
6. Exclua o bem — verifique evento `deleted` na auditoria

### Teste do Filtro de Salas por Unidade

1. Vá em **Bens > Novo Bem** ou **Editar Bem**
2. Selecione uma unidade no campo Unidade
3. O campo Sala deve atualizar dinamicamente via AJAX com as salas da unidade selecionada
4. Troque a unidade — as salas devem ser recarregadas

### Teste de Filtros no Dashboard

1. Acesse a rota `GET /bens?unidade_id=1` (substitua pelo ID real)
2. Verifique que apenas bens da unidade aparecem
3. Combine com `sala_id` e `status`

---

## Testes de Integração (Artisan Tinker)

O `tinker` permite testar a camada de modelo diretamente:

```bash
php artisan tinker
```

### Verificar criação de bem e registro de auditoria

```php
// Criar um bem de teste
$bem = \App\Models\Bem::create([
    'tombo'        => 'TEST-001',
    'nome'         => 'Bem de Teste',
    'categoria_id' => 1,
    'unidade_id'   => 1,
    'status'       => 'ativo',
]);

// Verificar se o log foi criado
\Spatie\Activitylog\Models\Activity::latest()->first();
```

### Consultar usuários e perfis

```php
\App\Models\User::all(['id', 'name', 'email', 'role']);
```

### Verificar contagem de bens por status

```php
\App\Models\Bem::selectRaw('status, count(*) as total')
    ->groupBy('status')
    ->get();
```

---

## Testes de Autenticação (curl)

```bash
# Deve retornar 302 → redirecionar para /login
curl -I http://localhost:8000/

# Deve retornar 200
curl -I http://localhost:8000/login
```

---

## Checklist de Validação Pós-Deploy

Antes de qualquer entrega ou deploy em produção, verifique:

- [ ] Login funcionando para os três perfis
- [ ] Dashboard exibe dados corretos
- [ ] CRUD de Bens: criar, editar, visualizar, excluir
- [ ] CRUD de Unidades e Salas: criar e vincular
- [ ] Filtro dinâmico de salas por unidade (AJAX)
- [ ] Filtros na listagem de bens funcionando
- [ ] Auditoria: log registrado para criar/editar/excluir bens
- [ ] Perfil `operador` não acessa `/auditoria` (403)
- [ ] Perfil `auditor` acessa auditoria mas não altera dados
- [ ] Paginação funcionando nas listagens
- [ ] Sem erros no `storage/logs/laravel.log`

---

## Limpeza de Cache

Após alterações de configuração ou código:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

Para ambiente de produção, reconstruir o cache:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
