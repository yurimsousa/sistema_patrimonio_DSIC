# Modelo Lógico

Descrição das entidades, atributos e relacionamentos do banco de dados.

---

## Entidades e Atributos

### users
Usuários de autenticação do sistema (tabela gerenciada pelo Laravel).

| Coluna       | Tipo          | Restrição           | Descrição                        |
|--------------|---------------|---------------------|----------------------------------|
| id           | NUMBER        | PK, NOT NULL        | Identificador único              |
| name         | VARCHAR2(255)  | NOT NULL            | Nome completo                    |
| email        | VARCHAR2(255)  | NOT NULL, UNIQUE    | E-mail de login                  |
| password     | VARCHAR2(255)  | NOT NULL            | Hash bcrypt da senha             |
| role         | VARCHAR2(20)   | NOT NULL, DEFAULT 'usuario' | Perfil: admin, auditor, usuario |
| remember_token | VARCHAR2(100) | NULL              | Token de sessão persistente      |
| created_at   | TIMESTAMP     | NULL                | Data de criação                  |
| updated_at   | TIMESTAMP     | NULL                | Data de atualização              |

---

### unidades
Unidades organizacionais (prédios, campi, filiais).

| Coluna     | Tipo          | Restrição    | Descrição                  |
|------------|---------------|--------------|----------------------------|
| id         | NUMBER        | PK, NOT NULL | Identificador único        |
| nome       | VARCHAR2(150)  | NOT NULL     | Nome da unidade            |
| sigla      | VARCHAR2(20)   | NULL         | Sigla da unidade           |
| endereco   | VARCHAR2(255)  | NULL         | Endereço físico            |
| created_at | TIMESTAMP     | NULL         | Data de criação            |
| updated_at | TIMESTAMP     | NULL         | Data de atualização        |

---

### salas
Salas ou ambientes dentro de uma unidade.

| Coluna      | Tipo          | Restrição           | Descrição                    |
|-------------|---------------|---------------------|------------------------------|
| id          | NUMBER        | PK, NOT NULL        | Identificador único          |
| unidade_id  | NUMBER        | FK → unidades.id    | Unidade à qual pertence      |
| nome        | VARCHAR2(150)  | NOT NULL            | Nome/número da sala          |
| descricao   | VARCHAR2(255)  | NULL                | Descrição ou finalidade      |
| created_at  | TIMESTAMP     | NULL                | Data de criação              |
| updated_at  | TIMESTAMP     | NULL                | Data de atualização          |

---

### categorias_bem
Classificação dos bens patrimoniais.

| Coluna     | Tipo          | Restrição    | Descrição                    |
|------------|---------------|--------------|------------------------------|
| id         | NUMBER        | PK, NOT NULL | Identificador único          |
| nome       | VARCHAR2(100)  | NOT NULL     | Nome da categoria            |
| descricao  | CLOB          | NULL         | Descrição detalhada          |
| created_at | TIMESTAMP     | NULL         | Data de criação              |
| updated_at | TIMESTAMP     | NULL         | Data de atualização          |

---

### usuarios
Responsáveis e servidores que recebem bens patrimoniais.

| Coluna     | Tipo          | Restrição    | Descrição                           |
|------------|---------------|--------------|-------------------------------------|
| id         | NUMBER        | PK, NOT NULL | Identificador único                 |
| nome       | VARCHAR2(150)  | NOT NULL     | Nome completo                       |
| matricula  | VARCHAR2(30)   | UNIQUE, NULL | Matrícula funcional                 |
| email      | VARCHAR2(150)  | UNIQUE, NULL | E-mail corporativo                  |
| setor      | VARCHAR2(100)  | NULL         | Setor de atuação                    |
| telefone   | VARCHAR2(20)   | NULL         | Telefone de contato                 |
| created_at | TIMESTAMP     | NULL         | Data de criação                     |
| updated_at | TIMESTAMP     | NULL         | Data de atualização                 |

---

### bens
Bens patrimoniais registrados no sistema.

| Coluna          | Tipo          | Restrição              | Descrição                          |
|-----------------|---------------|------------------------|------------------------------------|
| id              | NUMBER        | PK, NOT NULL           | Identificador único                |
| tombo           | VARCHAR2(50)   | UNIQUE, NOT NULL       | Número de tombamento               |
| nome            | VARCHAR2(200)  | NOT NULL               | Nome/descrição do bem              |
| categoria_id    | NUMBER        | FK → categorias_bem.id | Categoria do bem                   |
| unidade_id      | NUMBER        | FK → unidades.id       | Localização (unidade)              |
| sala_id         | NUMBER        | FK → salas.id, NULL    | Localização (sala)                 |
| usuario_id      | NUMBER        | FK → usuarios.id, NULL | Responsável atual                  |
| status          | VARCHAR2(20)  | NOT NULL, DEFAULT 'ativo' | ativo, manutencao, descartado    |
| numero_serie    | VARCHAR2(100)  | NULL                   | Número de série do fabricante      |
| marca           | VARCHAR2(100)  | NULL                   | Marca/fabricante                   |
| modelo          | VARCHAR2(100)  | NULL                   | Modelo específico                  |
| valor_aquisicao | NUMBER(15,2)  | NULL                   | Valor de compra                    |
| data_aquisicao  | DATE          | NULL                   | Data de aquisição                  |
| observacoes     | CLOB          | NULL                   | Observações livres                 |
| created_at      | TIMESTAMP     | NULL                   | Data de criação                    |
| updated_at      | TIMESTAMP     | NULL                   | Data de atualização                |

---

### activity_log
Tabela de auditoria gerenciada pelo pacote `spatie/laravel-activitylog`.

| Coluna       | Tipo          | Restrição    | Descrição                             |
|--------------|---------------|--------------|---------------------------------------|
| id           | NUMBER        | PK, NOT NULL | Identificador único                   |
| log_name     | VARCHAR2(255)  | NULL         | Nome do log (default)                 |
| description  | CLOB          | NOT NULL     | Descrição da ação realizada           |
| subject_type | VARCHAR2(255)  | NULL         | Classe do modelo afetado (ex: Bem)    |
| subject_id   | NUMBER        | NULL         | ID do registro afetado               |
| causer_type  | VARCHAR2(255)  | NULL         | Classe do ator (App\Models\User)      |
| causer_id    | NUMBER        | NULL         | ID do usuário que realizou a ação    |
| properties   | CLOB          | NULL         | JSON com old/attributes do registro  |
| event        | VARCHAR2(255)  | NULL         | created, updated, deleted            |
| batch_uuid   | VARCHAR2(36)   | NULL         | UUID de lote para múltiplas operações|
| created_at   | TIMESTAMP     | NULL         | Data/hora da ocorrência              |
| updated_at   | TIMESTAMP     | NULL         | Data de atualização                  |

---

## Relacionamentos

| Relacionamento                  | Cardinalidade | Descrição                                      |
|---------------------------------|---------------|------------------------------------------------|
| unidades → salas                | 1:N           | Uma unidade possui várias salas                |
| unidades → bens                 | 1:N           | Uma unidade abriga vários bens                 |
| salas → bens                    | 1:N           | Uma sala contém vários bens                    |
| categorias_bem → bens           | 1:N           | Uma categoria agrupa vários bens               |
| usuarios → bens                 | 1:N           | Um usuário pode ser responsável por vários bens|
| users → activity_log (causer)   | 1:N           | Um usuário gera vários registros de auditoria  |
| bens → activity_log (subject)   | 1:N           | Um bem possui vários eventos de auditoria      |

---

## Regras de Negócio

- O campo `tombo` é único e obrigatório — identifica o bem no patrimônio.
- Um bem pode existir sem `sala_id` (armazenado na unidade mas sem sala específica).
- Um bem pode existir sem `usuario_id` (não atribuído a nenhum responsável).
- O `status` controla o ciclo de vida: `ativo` → `manutencao` → `descartado`.
- A tabela `usuarios` é separada de `users`: `usuarios` são os servidores/responsáveis, `users` são os operadores do sistema.
- Exclusão de `unidade` é impedida se houver `salas` ou `bens` vinculados (integridade referencial).
- Todos os modelos auditáveis (`Bem`, `Usuario`, `Unidade`, `Sala`, `CategoriaBem`) registram automaticamente na `activity_log` via trait `LogsActivity`.
