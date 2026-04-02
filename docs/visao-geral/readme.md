# Início Rápido — 5 Minutos

Este guia coloca a aplicação no ar em um ambiente local do zero.

## Pré-requisitos

| Ferramenta | Versão mínima | Verificar |
|---|---|---|
| PHP | 8.2+ | `php --version` |
| Composer | 2.x | `composer --version` |
| Node.js | 18+ | `node --version` |
| SQLite (local) | qualquer | `php -m \| grep pdo_sqlite` |
| Oracle (produção) | 12c+ | — |

---

## Passo a Passo

### 1. Clonar o repositório

```bash
git clone <url-do-repositorio> patrimonio
cd patrimonio
```

### 2. Instalar dependências PHP

```bash
composer install --ignore-platform-reqs
```

### 3. Criar o arquivo de ambiente

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar o banco de dados

=== "SQLite (local/testes)"

    Edite o `.env`:
    ```env
    DB_CONNECTION=sqlite
    DB_DATABASE=/caminho/absoluto/database/database.sqlite
    ```

    Crie o arquivo e rode as migrations:
    ```bash
    touch database/database.sqlite
    php artisan migrate --seed
    ```

=== "Oracle (produção)"

    Edite o `.env`:
    ```env
    DB_CONNECTION=oracle
    DB_HOST=seu-servidor
    DB_PORT=1521
    DB_DATABASE=ORCL
    DB_USERNAME=patrimonio
    DB_PASSWORD=sua_senha
    ```

    Execute:
    ```bash
    php artisan migrate --seed
    ```

    !!! warning "Driver Oracle"
        Ative `extension=oci8_12c` e `extension=pdo_oci` no `php.ini` antes de migrar.

### 5. Iniciar o servidor

```bash
php artisan serve
```

Acesse **[http://localhost:8000](http://localhost:8000)**

---

## Credenciais Padrão (Seed)

| E-mail | Senha | Perfil |
|---|---|---|
| `admin@patrimonio.com` | `admin123` | Administrador |
| `auditor@patrimonio.com` | `auditor123` | Auditor |
| `operador@patrimonio.com` | `operador123` | Usuário |

!!! danger "Importante"
    Altere todas as senhas antes de ir para produção.

---

## Estrutura de Diretórios Relevante

```
projeto_patrimonio/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Lógica de cada módulo
│   │   └── Middleware/         # CheckRole (controle de acesso)
│   └── Models/                 # Eloquent ORM
├── database/
│   ├── migrations/             # Estrutura do banco
│   └── seeders/                # Dados iniciais
├── resources/views/            # Templates Blade
│   ├── layouts/app.blade.php   # Layout principal
│   ├── bens/
│   ├── usuarios/
│   ├── unidades/
│   ├── salas/
│   ├── categorias/
│   └── auditoria/
├── routes/web.php              # Definição das rotas
├── .env                        # Variáveis de ambiente (não versionar)
└── .env.example                # Template de variáveis
```
