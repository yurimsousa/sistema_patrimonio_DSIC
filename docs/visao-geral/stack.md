# Stack Tecnológica

## Backend

| Tecnologia | Versão | Papel |
|---|---|---|
| **PHP** | 8.3 | Linguagem principal |
| **Laravel** | 11.x | Framework MVC |
| **yajra/laravel-oci8** | ^11.6 | Driver Oracle para Laravel |
| **spatie/laravel-activitylog** | ^4.12 | Registro de auditoria |
| **laravel/ui** | ^4.6 | Scaffolding de autenticação |

## Frontend

| Tecnologia | Versão | Papel |
|---|---|---|
| **Bootstrap** | 5.3.3 | Framework CSS (CDN) |
| **Bootstrap Icons** | 1.11.3 | Ícones (CDN) |
| **Blade** | — | Template engine do Laravel |
| **JavaScript Vanilla** | ES2020+ | AJAX dinâmico (salas por unidade) |

## Banco de Dados

| Ambiente | Banco | Driver |
|---|---|---|
| **Produção** | Oracle 12c+ | `yajra/laravel-oci8` + `pdo_oci` |
| **Local/Testes** | SQLite | `pdo_sqlite` (nativo PHP) |

## Ferramentas de Desenvolvimento

| Ferramenta | Uso |
|---|---|
| **Composer** | Gerenciador de dependências PHP |
| **Artisan** | CLI do Laravel (migrations, seeds, etc.) |
| **Node.js / npm** | Build de assets (quando necessário) |
| **DBeaver** | Cliente SQL para inspeção do banco |

## Diagrama da Stack

```
┌─────────────────────────────────────────────────────┐
│                    BROWSER                          │
│         Bootstrap 5 + Bootstrap Icons + JS          │
└──────────────────────┬──────────────────────────────┘
                       │ HTTP
┌──────────────────────▼──────────────────────────────┐
│                  LARAVEL 11                         │
│  ┌──────────┐  ┌────────────┐  ┌─────────────────┐  │
│  │  Routes  │→ │Controllers │→ │  Blade Views    │  │
│  └──────────┘  └─────┬──────┘  └─────────────────┘  │
│                      │                               │
│               ┌──────▼──────┐                        │
│               │   Models    │                        │
│               │  (Eloquent) │                        │
│               └──────┬──────┘                        │
│                      │                               │
│  ┌───────────────────▼───────────────────────────┐  │
│  │         spatie/activitylog                    │  │
│  │      (log automático de mudanças)             │  │
│  └───────────────────────────────────────────────┘  │
└──────────────────────┬──────────────────────────────┘
                       │ PDO / OCI8
┌──────────────────────▼──────────────────────────────┐
│            ORACLE DATABASE 12c+                     │
│   users · bens · usuarios · unidades · salas        │
│   categorias_bem · activity_log                     │
└─────────────────────────────────────────────────────┘
```

## Decisões de Arquitetura

### Por que Laravel + Oracle?
- Laravel oferece o ORM Eloquent com suporte a Oracle via `yajra/laravel-oci8`
- Oracle é o banco de dados corporativo já utilizado pela organização
- Reduz necessidade de nova infraestrutura

### Por que Bootstrap via CDN?
- Simplicidade: sem necessidade de pipeline de build (webpack/vite) no ambiente atual
- O PHP 8.3 disponível não tem `ext-fileinfo` habilitada por padrão, o que impediria o Vite de funcionar

### Por que spatie/activitylog?
- Integração nativa com Eloquent (trait `LogsActivity`)
- Registra automaticamente `created`, `updated`, `deleted` com diff campo a campo
- Nenhuma chamada manual necessária nos controllers
