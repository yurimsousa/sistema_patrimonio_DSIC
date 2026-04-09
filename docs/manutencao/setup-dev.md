# Guia de Setup — Ambiente de Desenvolvimento

Passo a passo para configurar o ambiente local do projeto do zero.

---

## Pré-requisitos

| Ferramenta    | Versão mínima | Download                                      |
|---------------|---------------|-----------------------------------------------|
| PHP           | **8.2+**      | https://windows.php.net/download/ (Windows)  |
| Composer      | 2.x           | https://getcomposer.org/                     |
| Node.js / npm | 18+           | https://nodejs.org/                          |
| Git           | 2.x           | https://git-scm.com/                         |

> **Oracle (produção):** Requer Oracle Instant Client 21+ e extensão `oci8` no PHP.
> **Desenvolvimento local:** SQLite (incluso no PHP — sem instalação extra).

---

## 1. Clonar o Repositório

```bash
git clone <url-do-repositorio> projeto_patrimonio
cd projeto_patrimonio
```

---

## 2. Instalar Dependências PHP

```bash
composer install
```

Se houver extensões ausentes na máquina (ex: `fileinfo`), use:

```bash
composer install --ignore-platform-reqs
```

---

## 3. Configurar o `.env`

```bash
cp .env.example .env
php artisan key:generate
```

Edite o `.env` para desenvolvimento local com SQLite:

```dotenv
APP_NAME="Sistema de Patrimônio"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=C:\caminho\absoluto\projeto_patrimonio\database\database.sqlite

SESSION_DRIVER=file
SESSION_SECURE_COOKIE=false       # IMPORTANTE: manter false em local (HTTP)
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

> **Windows:** Use caminho absoluto com barras invertidas duplas ou barras normais no `DB_DATABASE`.

!!! warning "SESSION_SECURE_COOKIE deve ser false em local"
    Se `SESSION_SECURE_COOKIE=true` com `http://localhost`, o browser não envia o cookie de sessão e o login não funciona. Só ative em produção com HTTPS.

---

## 4. Habilitar Extensões PHP (Windows)

Abra `C:\php83\php.ini` e descomente as linhas:

```ini
extension=pdo_sqlite
extension=fileinfo
extension=mbstring
extension=openssl
```

Reinicie o servidor PHP após as alterações.

---

## 5. Criar o Banco SQLite

```bash
# Windows
type nul > database\database.sqlite

# Linux/macOS
touch database/database.sqlite
```

---

## 6. Executar Migrations e Seeders

```bash
php artisan migrate --seed
```

Para recriar do zero (apaga e recria todas as tabelas):

```bash
php artisan migrate:fresh --seed
```

---

## 7. Iniciar o Servidor

```bash
php artisan serve
```

Acesse: [http://localhost:8000](http://localhost:8000)

---

## 8. Usuários Padrão (Seed)

| E-mail                     | Senha        | Perfil  |
|----------------------------|--------------|---------|
| admin@patrimonio.com       | admin123     | admin   |
| auditor@patrimonio.com     | auditor123   | auditor |
| operador@patrimonio.com    | operador123  | usuario |

---

## Verificar Instalação

```bash
# Checar versão do PHP e extensões
php -v
php -m | grep -E "pdo_sqlite|fileinfo|mbstring"

# Checar rotas registradas
php artisan route:list

# Checar status do banco
php artisan migrate:status
```

---

## Problemas Comuns

### `pdo_sqlite` not found
Habilite a extensão no `php.ini` conforme passo 4.

### `UniqueConstraintViolationException` ao rodar o seeder
Execute `php artisan migrate:fresh --seed` para recriar o banco.

### Porta 8000 em uso
```bash
php artisan serve --port=8080
```

### `Call to undefined method middleware()`
O Laravel 11 removeu o método `middleware()` do construtor do `Controller`. Remova qualquer `__construct()` com `$this->middleware(...)` dos controllers.

---

## Configuração para Oracle (Produção)

### Oracle on-premise (servidor próprio)

```dotenv
DB_CONNECTION=oracle
DB_HOST=oracle-server.dominio.com
DB_PORT=1521
DB_DATABASE=ORCL
DB_USERNAME=patrimonio
DB_PASSWORD=SuaSenhaForte@2024
DB_CHARSET=AL32UTF8
DB_SERVER_VERSION=19c
```

### Oracle Cloud (Autonomous Database)

```dotenv
DB_CONNECTION=oracle
DB_TNS=patrimonio_high            # nome do serviço no tnsnames.ora do wallet
DB_PORT=1522                      # porta SSL do Oracle Cloud (≠ 1521)
DB_USERNAME=admin
DB_PASSWORD=SuaSenhaForte@2024
DB_CHARSET=AL32UTF8
DB_SERVER_VERSION=19c
```

Também é necessário:
1. Baixar o **Wallet** na Oracle Cloud Console e extrair no servidor
2. Definir `TNS_ADMIN=/caminho/do/wallet` nas variáveis de sistema

Certifique-se de que:

- Oracle Instant Client 21+ está instalado
- A extensão `oci8` está habilitada no `php.ini`
- O pacote `yajra/laravel-oci8` está no `composer.json` ✅ (já incluído)
- O arquivo `config/database.php` tem o driver `oracle` configurado ✅ (já configurado)

Consulte a documentação de [conexão DBeaver](../banco-de-dados/dcl.md) para configurar um cliente de banco de dados.
