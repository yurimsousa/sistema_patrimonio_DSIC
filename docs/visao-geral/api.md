# Endpoints da API

O sistema é uma aplicação web tradicional (server-side rendering com Blade). Os endpoints abaixo seguem o padrão RESTful do Laravel Resource Controller.

!!! info "Autenticação"
    Todos os endpoints (exceto `/login`) exigem sessão autenticada via cookie.
    Acesso sem autenticação resulta em **redirect 302 → /login**.

---

## Autenticação

| Método | URI | Descrição |
|---|---|---|
| `GET` | `/login` | Exibe o formulário de login |
| `POST` | `/login` | Processa o login |
| `POST` | `/logout` | Encerra a sessão |

---

## Dashboard

| Método | URI | Nome da Rota | Acesso |
|---|---|---|---|
| `GET` | `/` | `dashboard` | Todos autenticados |

---

## Bens

| Método | URI | Nome da Rota | Acesso | Ação |
|---|---|---|---|---|
| `GET` | `/bens` | `bens.index` | Todos autenticados | Listar bens (com filtros) |
| `GET` | `/bens/create` | `bens.create` | admin | Formulário de cadastro |
| `POST` | `/bens` | `bens.store` | admin | Salvar novo bem |
| `GET` | `/bens/{id}` | `bens.show` | Todos autenticados | Ver detalhes |
| `GET` | `/bens/{id}/edit` | `bens.edit` | admin | Formulário de edição |
| `PUT` | `/bens/{id}` | `bens.update` | admin | Atualizar bem |
| `DELETE` | `/bens/{id}` | `bens.destroy` | admin | Remover bem |
| `GET` | `/bens/{id}/cautela` | `bens.cautela` | Todos autenticados | Gerar documento de cautela |
| `GET` | `/bens-exportar` | `bens.exportar` | admin, auditor | Exportar listagem em `.xlsx` |
| `POST` | `/bens-importar` | `bens.importar` | admin | Importar bens via planilha `.xlsx` |

### Parâmetros de filtro — GET `/bens`

| Parâmetro | Tipo | Descrição |
|---|---|---|
| `busca` | string | Busca em nome, patrimônio, série, marca, modelo |
| `unidade_id` | integer | Filtra por unidade |
| `sala_id` | integer | Filtra por sala |
| `categoria_id` | integer | Filtra por categoria |
| `status` | string | `ativo` \| `inativo` \| `manutencao` \| `descartado` |

---

## Usuários

| Método | URI | Nome da Rota | Ação |
|---|---|---|---|
| `GET` | `/usuarios` | `usuarios.index` | Listar usuários |
| `GET` | `/usuarios/create` | `usuarios.create` | Formulário de cadastro |
| `POST` | `/usuarios` | `usuarios.store` | Salvar usuário |
| `GET` | `/usuarios/{id}` | `usuarios.show` | Ver perfil e bens |
| `GET` | `/usuarios/{id}/edit` | `usuarios.edit` | Formulário de edição |
| `PUT` | `/usuarios/{id}` | `usuarios.update` | Atualizar usuário |
| `DELETE` | `/usuarios/{id}` | `usuarios.destroy` | Remover usuário |

---

## Unidades

| Método | URI | Nome da Rota | Ação |
|---|---|---|---|
| `GET` | `/unidades` | `unidades.index` | Listar unidades |
| `GET` | `/unidades/create` | `unidades.create` | Formulário |
| `POST` | `/unidades` | `unidades.store` | Salvar |
| `GET` | `/unidades/{id}` | `unidades.show` | Detalhes + salas |
| `GET` | `/unidades/{id}/edit` | `unidades.edit` | Editar |
| `PUT` | `/unidades/{id}` | `unidades.update` | Atualizar |
| `DELETE` | `/unidades/{id}` | `unidades.destroy` | Remover |

---

## Salas

| Método | URI | Nome da Rota | Ação |
|---|---|---|---|
| `GET` | `/salas` | `salas.index` | Listar salas |
| `GET` | `/salas/create` | `salas.create` | Formulário |
| `POST` | `/salas` | `salas.store` | Salvar |
| `GET` | `/salas/{id}` | `salas.show` | Detalhes + bens |
| `GET` | `/salas/{id}/edit` | `salas.edit` | Editar |
| `PUT` | `/salas/{id}` | `salas.update` | Atualizar |
| `DELETE` | `/salas/{id}` | `salas.destroy` | Remover |

---

## Categorias

| Método | URI | Nome da Rota | Ação |
|---|---|---|---|
| `GET` | `/categorias` | `categorias.index` | Listar |
| `GET` | `/categorias/create` | `categorias.create` | Formulário |
| `POST` | `/categorias` | `categorias.store` | Salvar |
| `GET` | `/categorias/{id}` | `categorias.show` | Detalhes |
| `GET` | `/categorias/{id}/edit` | `categorias.edit` | Editar |
| `PUT` | `/categorias/{id}` | `categorias.update` | Atualizar |
| `DELETE` | `/categorias/{id}` | `categorias.destroy` | Remover |

---

## Auditoria

!!! warning "Acesso restrito"
    Apenas perfis `admin` e `auditor` têm acesso.

| Método | URI | Nome da Rota | Ação |
|---|---|---|---|
| `GET` | `/auditoria` | `auditoria.index` | Listar logs com filtros |
| `GET` | `/auditoria/{id}` | `auditoria.show` | Ver detalhes do log |

### Parâmetros de filtro — GET `/auditoria`

| Parâmetro | Tipo | Descrição |
|---|---|---|
| `busca` | string | Busca na descrição do log |
| `evento` | string | `created` \| `updated` \| `deleted` |
| `modelo` | string | `Bem` \| `Usuario` \| `Unidade` \| `Sala` \| `CategoriaBem` |
| `usuario_id` | integer | ID do usuário do sistema (operador) |
| `data_inicio` | date | Formato `YYYY-MM-DD` |
| `data_fim` | date | Formato `YYYY-MM-DD` |

---

## API Interna (AJAX)

| Método | URI | Nome da Rota | Acesso | Descrição |
|---|---|---|---|---|
| `GET` | `/api/salas-por-unidade/{unidade_id}` | `api.salas` | Todos autenticados | Retorna salas ativas de uma unidade em JSON |

**Rate limit:** 60 requisições por minuto (`throttle:60,1`).
**Validação:** o parâmetro `{unidade_id}` aceita apenas valores numéricos — parâmetros inválidos retornam **404**.

### Resposta — GET `/api/salas-por-unidade/{id}`

```json
[
  { "id": 1, "nome": "Diretoria", "numero": "101" },
  { "id": 2, "nome": "Sala de Reuniões", "numero": "102" }
]
```

Retorna apenas salas com `ativo = true`. Usado no front-end para popular dinamicamente o select de salas ao selecionar uma unidade no formulário de bens.
