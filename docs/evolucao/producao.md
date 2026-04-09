# Deploy em Produção e Docker

Guia para publicar o sistema em ambiente de produção, com e sem Docker.

---

## Deploy Tradicional (Servidor Linux)

### Pré-requisitos do Servidor

- Ubuntu 22.04 LTS (ou RHEL 8+)
- PHP 8.3 com extensões: `pdo_oci`, `oci8`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`
- Oracle Instant Client 21+
- Composer 2.x
- Nginx ou Apache
- Git

### Passo a Passo

```bash
# 1. Clonar o repositório
cd /var/www
git clone <url-do-repositorio> patrimonio
cd patrimonio

# 2. Instalar dependências (sem devDependencies)
composer install --optimize-autoloader --no-dev

# 3. Configurar variáveis de ambiente
cp .env.example .env
nano .env  # Editar com valores de produção

# 4. Gerar chave da aplicação
php artisan key:generate

# 5. Executar migrations
php artisan migrate --force

# 6. Compilar cache de configuração e rotas
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Ajustar permissões
chown -R www-data:www-data /var/www/patrimonio
chmod -R 755 /var/www/patrimonio/storage
chmod -R 755 /var/www/patrimonio/bootstrap/cache
```

### Configuração Nginx

```nginx
server {
    listen 80;
    server_name patrimonio.seudominio.com;
    root /var/www/patrimonio/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

### `.env` de Produção

```dotenv
APP_NAME="Sistema de Patrimônio"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://patrimonio.seudominio.com

# Oracle Cloud (Autonomous Database)
# Configure TNS_ADMIN=/var/www/wallet no sistema antes de subir
DB_CONNECTION=oracle
DB_TNS=patrimonio_high            # nome do serviço no tnsnames.ora do wallet
DB_PORT=1522                      # porta SSL do Oracle Cloud
DB_USERNAME=
DB_PASSWORD=
DB_CHARSET=AL32UTF8
DB_SERVER_VERSION=19c

SESSION_DRIVER=database
SESSION_LIFETIME=480
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict

CACHE_STORE=database
QUEUE_CONNECTION=sync

LOG_CHANNEL=stack
LOG_LEVEL=error
```

!!! warning "Oracle Cloud — configuração do Wallet"
    O Oracle Autonomous Database exige autenticação via **Wallet** (arquivo de credenciais):

    1. Baixe o Wallet ZIP em: **Oracle Cloud → Autonomous DB → DB Connection → Download Wallet**
    2. Extraia em `/var/www/wallet/` (ou outro diretório seguro fora do webroot)
    3. Defina a variável de sistema **antes** de iniciar o PHP:
       ```bash
       export TNS_ADMIN=/var/www/wallet
       ```
    4. O `DB_TNS` é o nome do serviço listado no `tnsnames.ora` dentro do wallet
       (ex: `patrimonio_high`, `patrimonio_medium`, `patrimonio_low`)
    5. A porta do Oracle Cloud é **1522** (SSL) — diferente da 1521 usada em instalações on-premise

---

## Deploy com Docker

### Estrutura de Arquivos Docker

```
projeto_patrimonio/
├── Dockerfile
├── docker-compose.yml
└── docker/
    └── nginx/
        └── default.conf
```

### Dockerfile

```dockerfile
FROM php:8.3-fpm

# Instalar extensões do sistema
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip curl git \
    && docker-php-ext-install pdo mbstring zip bcmath opcache

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar arquivos do projeto
COPY . .

# Instalar dependências PHP
RUN composer install --optimize-autoloader --no-dev

# Ajustar permissões
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
```

> **Nota:** Para Oracle, adicionar a instalação do Oracle Instant Client e a extensão `oci8` no Dockerfile. Consulte a documentação da `yajra/laravel-oci8`.

### docker-compose.yml (com SQLite para testes)

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: patrimonio_app
    volumes:
      - .:/var/www/html
      - ./storage:/var/www/html/storage
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
    depends_on:
      - nginx

  nginx:
    image: nginx:alpine
    container_name: patrimonio_nginx
    ports:
      - "80:80"
    volumes:
      - .:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
```

### docker/nginx/default.conf

```nginx
server {
    listen 80;
    root /var/www/html/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### Subir o Ambiente Docker

```bash
# Construir e iniciar
docker-compose up -d --build

# Executar migrations dentro do container
docker-compose exec app php artisan migrate --force --seed

# Gerar chave (se necessário)
docker-compose exec app php artisan key:generate

# Ver logs da aplicação
docker-compose exec app tail -f storage/logs/laravel.log
```

---

## Atualizações em Produção

```bash
# 1. Ativar modo de manutenção
php artisan down --message="Atualização em andamento" --retry=60

# 2. Atualizar o código
git pull origin main

# 3. Instalar novas dependências
composer install --optimize-autoloader --no-dev

# 4. Executar novas migrations
php artisan migrate --force

# 5. Limpar e recriar caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Desativar modo de manutenção
php artisan up
```

---

## Checklist Pré-Go-Live

- [ ] `APP_DEBUG=false` no `.env` de produção
- [ ] `APP_ENV=production` configurado (ativa redirecionamento HTTPS automático)
- [ ] `APP_KEY` gerado e configurado
- [ ] `SESSION_SECURE_COOKIE=true` (obrigatório com HTTPS)
- [ ] Oracle Cloud Wallet extraído e `TNS_ADMIN` configurado no servidor
- [ ] Banco Oracle acessível e migrations executadas (`php artisan migrate --force`)
- [ ] Usuário admin criado com senha forte (não usar `admin123` do seeder)
- [ ] Permissões de `storage/` e `bootstrap/cache/` corretas (`chmod -R 775`)
- [ ] HTTPS configurado (certificado SSL/TLS ativo)
- [ ] Todos os testes passando: `php artisan test`
- [ ] Logs funcionando em `storage/logs/laravel.log`
- [ ] Backup automático do banco de dados configurado
- [ ] Firewall: apenas portas 80/443 abertas para o servidor web
- [ ] Acesso direto ao Oracle bloqueado externamente (apenas via aplicação)

---

## Monitoramento

```bash
# Monitorar logs em tempo real
tail -f storage/logs/laravel.log

# Verificar erros recentes
grep "ERROR\|CRITICAL" storage/logs/laravel.log | tail -50

# Status da aplicação
php artisan about
```
