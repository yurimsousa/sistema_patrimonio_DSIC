# Variáveis de Ambiente

Arquivo `.env.example` — copie para `.env` e preencha antes de rodar.

```env
# ─────────────────────────────────────────────
#  APLICAÇÃO
# ─────────────────────────────────────────────
APP_NAME="Sistema Patrimônio"
APP_ENV=local                     # local | production
APP_KEY=                          # Gerado com: php artisan key:generate
APP_DEBUG=true                    # false em produção
APP_TIMEZONE=America/Sao_Paulo
APP_URL=http://localhost:8000

APP_LOCALE=pt_BR
APP_FALLBACK_LOCALE=pt_BR
APP_FAKER_LOCALE=pt_BR

# ─────────────────────────────────────────────
#  BANCO DE DADOS — ORACLE (produção)
# ─────────────────────────────────────────────
# DB_CONNECTION=oracle
# DB_HOST=127.0.0.1               # IP ou hostname do servidor Oracle
# DB_PORT=1521                    # Porta padrão Oracle
# DB_DATABASE=ORCL                # SID ou Service Name
# DB_USERNAME=patrimonio          # Usuário do schema
# DB_PASSWORD=                    # Senha (nunca commitar com valor)
# DB_CHARSET=AL32UTF8
# DB_SERVER_VERSION=12c           # Versão: 11g | 12c | 19c | 21c

# ─────────────────────────────────────────────
#  BANCO DE DADOS — SQLITE (desenvolvimento local)
# ─────────────────────────────────────────────
DB_CONNECTION=sqlite
DB_DATABASE=/caminho/absoluto/para/database/database.sqlite

# ─────────────────────────────────────────────
#  SESSÃO E CACHE
# ─────────────────────────────────────────────
SESSION_DRIVER=file               # file | database | redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

CACHE_STORE=file                  # file | database | redis

# ─────────────────────────────────────────────
#  FILAS
# ─────────────────────────────────────────────
QUEUE_CONNECTION=sync             # sync | database | redis

# ─────────────────────────────────────────────
#  LOG
# ─────────────────────────────────────────────
LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug                   # debug | info | warning | error

# ─────────────────────────────────────────────
#  E-MAIL (opcional)
# ─────────────────────────────────────────────
MAIL_MAILER=log                   # log | smtp | mailgun
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="noreply@patrimonio.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## Descrição das Variáveis Críticas

| Variável | Obrigatória | Descrição |
|---|---|---|
| `APP_KEY` | **Sim** | Chave de criptografia. Gerar com `php artisan key:generate` |
| `APP_ENV` | Sim | Define o comportamento do framework. Usar `production` em prod |
| `APP_DEBUG` | Sim | `false` em produção — impede exposição de stack traces |
| `DB_CONNECTION` | Sim | `oracle` em produção, `sqlite` em desenvolvimento |
| `DB_PASSWORD` | Sim | Nunca versionar com valor real |
| `SESSION_DRIVER` | Recomendado | `redis` em produção para performance e escalabilidade |
| `CACHE_STORE` | Recomendado | `redis` em produção |

---

## Criação do `.env.example`

O arquivo `.env.example` já está incluído no repositório com todos os campos acima (sem valores sensíveis).

```bash
# Para criar seu .env a partir do exemplo:
cp .env.example .env
php artisan key:generate
```
