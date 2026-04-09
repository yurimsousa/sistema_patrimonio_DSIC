# Variáveis de Ambiente

Arquivo `.env.example` — copie para `.env` e preencha antes de rodar.

!!! warning "Nunca commitar o `.env`"
    O arquivo `.env` está no `.gitignore`. Apenas o `.env.example` (sem valores sensíveis) deve estar no repositório.

---

## Desenvolvimento Local (SQLite)

```env
# ─────────────────────────────────────────────
#  APLICAÇÃO
# ─────────────────────────────────────────────
APP_NAME="Sistema Patrimônio"
APP_ENV=local
APP_KEY=                          # Gerado com: php artisan key:generate
APP_DEBUG=true                    # true apenas em local — false em produção
APP_TIMEZONE=America/Sao_Paulo
APP_URL=http://localhost:8000

APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR
APP_FAKER_LOCALE=pt_BR

# ─────────────────────────────────────────────
#  BANCO DE DADOS — SQLITE (desenvolvimento)
# ─────────────────────────────────────────────
DB_CONNECTION=sqlite
DB_DATABASE=C:\caminho\absoluto\projeto_patrimonio\database\database.sqlite

# ─────────────────────────────────────────────
#  SESSÃO E CACHE
# ─────────────────────────────────────────────
SESSION_DRIVER=file
SESSION_LIFETIME=60
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false       # false em local (HTTP); true em produção (HTTPS)
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict

CACHE_STORE=file
QUEUE_CONNECTION=sync

# ─────────────────────────────────────────────
#  LOG
# ─────────────────────────────────────────────
LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=warning                 # warning em local; error em produção
```

---

## Produção (Oracle Cloud — Autonomous Database)

```env
# ─────────────────────────────────────────────
#  APLICAÇÃO
# ─────────────────────────────────────────────
APP_NAME="Sistema Patrimônio"
APP_ENV=production
APP_KEY=                          # Gerado com: php artisan key:generate
APP_DEBUG=false                   # NUNCA true em produção
APP_TIMEZONE=America/Sao_Paulo
APP_URL=https://seu-dominio.com.br

# ─────────────────────────────────────────────
#  BANCO DE DADOS — ORACLE CLOUD (Autonomous DB)
# ─────────────────────────────────────────────
# Passo 1: Baixe o Wallet em Oracle Cloud → Autonomous DB → DB Connection → Download Wallet
# Passo 2: Extraia o wallet em /var/www/wallet/ no servidor
# Passo 3: Configure a variável de sistema: TNS_ADMIN=/var/www/wallet
# Passo 4: O DB_TNS é o nome do serviço no arquivo tnsnames.ora do wallet
#
DB_CONNECTION=oracle
DB_TNS=nome_do_servico_high       # ex: patrimonio_high | _medium | _low
DB_PORT=1522                      # Porta SSL do Oracle Cloud (≠ 1521 on-premise)
DB_CHARSET=AL32UTF8
DB_SERVER_VERSION=19c
DB_USERNAME=                      # Preencher no servidor
DB_PASSWORD=                      # Nunca commitar com valor

# ─────────────────────────────────────────────
#  SESSÃO E CACHE
# ─────────────────────────────────────────────
SESSION_DRIVER=database           # database em produção (não file)
SESSION_LIFETIME=480              # 8 horas
SESSION_ENCRYPT=true
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true        # true obrigatório em HTTPS
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict

CACHE_STORE=database
QUEUE_CONNECTION=sync

# ─────────────────────────────────────────────
#  LOG
# ─────────────────────────────────────────────
LOG_CHANNEL=stack
LOG_STACK=single
LOG_LEVEL=error
```

---

## Descrição das Variáveis Críticas

| Variável | Obrigatória | Desenvolvimento | Produção |
|---|---|---|---|
| `APP_KEY` | **Sim** | Gerado com `php artisan key:generate` | Idem |
| `APP_ENV` | Sim | `local` | `production` |
| `APP_DEBUG` | Sim | `true` | **`false`** — impede exposição de stack traces |
| `DB_CONNECTION` | Sim | `sqlite` | `oracle` |
| `DB_TNS` | Em produção | — | Nome do serviço no `tnsnames.ora` do wallet |
| `DB_PORT` | Em produção | — | `1522` para Oracle Cloud (SSL) |
| `DB_PASSWORD` | Sim | — | Nunca versionar com valor real |
| `SESSION_SECURE_COOKIE` | Sim | `false` (HTTP local) | `true` (HTTPS obrigatório) |
| `SESSION_DRIVER` | Recomendado | `file` | `database` |
| `LOG_LEVEL` | Recomendado | `warning` | `error` |

!!! danger "SESSION_SECURE_COOKIE em desenvolvimento"
    Manter `SESSION_SECURE_COOKIE=false` no ambiente local. Se definido como `true` em HTTP (`localhost`),
    o cookie de sessão não será enviado pelo browser e o login não funcionará.

---

## Criação do `.env` a partir do exemplo

```bash
cp .env.example .env
php artisan key:generate
```
