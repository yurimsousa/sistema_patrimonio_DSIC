# DDL — Definição das Tabelas

Scripts de criação das tabelas no **Oracle**. Para SQLite (desenvolvimento), as migrations Laravel geram o equivalente automaticamente.

!!! tip "Migrations Laravel"
    Em vez de executar o DDL manualmente, utilize `php artisan migrate`.
    Os scripts abaixo são o equivalente Oracle gerado a partir das migrations.

---

## Sequências (Oracle Auto-Increment)

```sql
CREATE SEQUENCE seq_users          START WITH 1 INCREMENT BY 1 NOCACHE NOCYCLE;
CREATE SEQUENCE seq_unidades       START WITH 1 INCREMENT BY 1 NOCACHE NOCYCLE;
CREATE SEQUENCE seq_salas          START WITH 1 INCREMENT BY 1 NOCACHE NOCYCLE;
CREATE SEQUENCE seq_categorias_bem START WITH 1 INCREMENT BY 1 NOCACHE NOCYCLE;
CREATE SEQUENCE seq_usuarios       START WITH 1 INCREMENT BY 1 NOCACHE NOCYCLE;
CREATE SEQUENCE seq_bens           START WITH 1 INCREMENT BY 1 NOCACHE NOCYCLE;
CREATE SEQUENCE seq_activity_log   START WITH 1 INCREMENT BY 1 NOCACHE NOCYCLE;
```

---

## Tabela: USERS (Usuários do Sistema)

```sql
CREATE TABLE users (
    id                 NUMBER(20)     DEFAULT seq_users.NEXTVAL NOT NULL,
    name               VARCHAR2(255)  NOT NULL,
    email              VARCHAR2(255)  NOT NULL,
    email_verified_at  TIMESTAMP      NULL,
    password           VARCHAR2(255)  NOT NULL,
    role               VARCHAR2(10)   DEFAULT 'usuario' NOT NULL
                           CONSTRAINT chk_users_role
                           CHECK (role IN ('admin', 'auditor', 'usuario')),
    remember_token     VARCHAR2(100)  NULL,
    created_at         TIMESTAMP      NULL,
    updated_at         TIMESTAMP      NULL,
    --
    CONSTRAINT pk_users PRIMARY KEY (id),
    CONSTRAINT uq_users_email UNIQUE (email)
);

COMMENT ON TABLE  users              IS 'Usuários do sistema (login e controle de acesso)';
COMMENT ON COLUMN users.role         IS 'Perfil: admin | auditor | usuario';
```

---

## Tabela: UNIDADES

```sql
CREATE TABLE unidades (
    id          NUMBER(20)    DEFAULT seq_unidades.NEXTVAL NOT NULL,
    nome        VARCHAR2(100) NOT NULL,
    sigla       VARCHAR2(20)  NULL,
    endereco    VARCHAR2(255) NULL,
    ativo       NUMBER(1)     DEFAULT 1 NOT NULL
                    CONSTRAINT chk_unidades_ativo CHECK (ativo IN (0, 1)),
    created_at  TIMESTAMP     NULL,
    updated_at  TIMESTAMP     NULL,
    --
    CONSTRAINT pk_unidades PRIMARY KEY (id)
);

COMMENT ON TABLE  unidades      IS 'Unidades ou setores da organização';
COMMENT ON COLUMN unidades.ativo IS '1 = ativa, 0 = inativa';
```

---

## Tabela: SALAS

```sql
CREATE TABLE salas (
    id          NUMBER(20)   DEFAULT seq_salas.NEXTVAL NOT NULL,
    unidade_id  NUMBER(20)   NOT NULL,
    nome        VARCHAR2(100) NOT NULL,
    numero      VARCHAR2(20)  NULL,
    andar       VARCHAR2(20)  NULL,
    ativo       NUMBER(1)     DEFAULT 1 NOT NULL
                    CONSTRAINT chk_salas_ativo CHECK (ativo IN (0, 1)),
    created_at  TIMESTAMP     NULL,
    updated_at  TIMESTAMP     NULL,
    --
    CONSTRAINT pk_salas         PRIMARY KEY (id),
    CONSTRAINT fk_salas_unidade FOREIGN KEY (unidade_id)
        REFERENCES unidades(id) ON DELETE CASCADE
);

CREATE INDEX idx_salas_unidade_id ON salas(unidade_id);

COMMENT ON TABLE salas IS 'Salas físicas vinculadas a uma unidade';
```

---

## Tabela: CATEGORIAS_BEM

```sql
CREATE TABLE categorias_bem (
    id          NUMBER(20)    DEFAULT seq_categorias_bem.NEXTVAL NOT NULL,
    nome        VARCHAR2(80)  NOT NULL,
    descricao   VARCHAR2(255) NULL,
    created_at  TIMESTAMP     NULL,
    updated_at  TIMESTAMP     NULL,
    --
    CONSTRAINT pk_categorias_bem PRIMARY KEY (id)
);

COMMENT ON TABLE categorias_bem IS 'Categorias dos bens (Computador, Celular, TV, etc.)';
```

---

## Tabela: USUARIOS (Responsáveis pelos Bens)

```sql
CREATE TABLE usuarios (
    id          NUMBER(20)    DEFAULT seq_usuarios.NEXTVAL NOT NULL,
    nome        VARCHAR2(150) NOT NULL,
    matricula   VARCHAR2(30)  NULL,
    email       VARCHAR2(150) NOT NULL,
    telefone    VARCHAR2(20)  NULL,
    cargo       VARCHAR2(100) NULL,
    unidade_id  NUMBER(20)    NULL,
    ativo       NUMBER(1)     DEFAULT 1 NOT NULL
                    CONSTRAINT chk_usuarios_ativo CHECK (ativo IN (0, 1)),
    created_at  TIMESTAMP     NULL,
    updated_at  TIMESTAMP     NULL,
    --
    CONSTRAINT pk_usuarios         PRIMARY KEY (id),
    CONSTRAINT uq_usuarios_email   UNIQUE (email),
    CONSTRAINT uq_usuarios_matr    UNIQUE (matricula),
    CONSTRAINT fk_usuarios_unidade FOREIGN KEY (unidade_id)
        REFERENCES unidades(id) ON DELETE SET NULL
);

CREATE INDEX idx_usuarios_unidade_id ON usuarios(unidade_id);

COMMENT ON TABLE  usuarios      IS 'Pessoas responsáveis pelos bens patrimoniais';
COMMENT ON COLUMN usuarios.ativo IS '1 = ativo, 0 = inativo';
```

---

## Tabela: BENS

```sql
CREATE TABLE bens (
    id                 NUMBER(20)     DEFAULT seq_bens.NEXTVAL NOT NULL,
    nome               VARCHAR2(150)  NOT NULL,
    numero_patrimonio  VARCHAR2(50)   NULL,
    numero_serie       VARCHAR2(100)  NULL,
    descricao          CLOB           NULL,
    categoria_id       NUMBER(20)     NOT NULL,
    unidade_id         NUMBER(20)     NULL,
    sala_id            NUMBER(20)     NULL,
    usuario_id         NUMBER(20)     NULL,
    data_aquisicao     DATE           NULL,
    valor              NUMBER(15, 2)  NULL,
    marca              VARCHAR2(100)  NULL,
    modelo             VARCHAR2(100)  NULL,
    status             VARCHAR2(11)   DEFAULT 'ativo' NOT NULL
                           CONSTRAINT chk_bens_status
                           CHECK (status IN ('ativo','inativo','manutencao','descartado')),
    observacoes        CLOB           NULL,
    created_at         TIMESTAMP      NULL,
    updated_at         TIMESTAMP      NULL,
    --
    CONSTRAINT pk_bens              PRIMARY KEY (id),
    CONSTRAINT uq_bens_patrimonio   UNIQUE (numero_patrimonio),
    CONSTRAINT fk_bens_categoria    FOREIGN KEY (categoria_id)
        REFERENCES categorias_bem(id),
    CONSTRAINT fk_bens_unidade      FOREIGN KEY (unidade_id)
        REFERENCES unidades(id) ON DELETE SET NULL,
    CONSTRAINT fk_bens_sala         FOREIGN KEY (sala_id)
        REFERENCES salas(id) ON DELETE SET NULL,
    CONSTRAINT fk_bens_usuario      FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id) ON DELETE SET NULL
);

CREATE INDEX idx_bens_categoria_id ON bens(categoria_id);
CREATE INDEX idx_bens_unidade_id   ON bens(unidade_id);
CREATE INDEX idx_bens_sala_id      ON bens(sala_id);
CREATE INDEX idx_bens_usuario_id   ON bens(usuario_id);
CREATE INDEX idx_bens_status       ON bens(status);

COMMENT ON TABLE  bens                IS 'Bens patrimoniais da organização';
COMMENT ON COLUMN bens.status         IS 'ativo | inativo | manutencao | descartado';
COMMENT ON COLUMN bens.numero_patrimonio IS 'Número de tombamento do bem';
```

---

## Tabela: ACTIVITY_LOG (Auditoria)

Gerada pelo pacote `spatie/laravel-activitylog`:

```sql
CREATE TABLE activity_log (
    id            NUMBER(20)     DEFAULT seq_activity_log.NEXTVAL NOT NULL,
    log_name      VARCHAR2(255)  NULL,
    description   CLOB           NOT NULL,
    subject_type  VARCHAR2(255)  NULL,
    subject_id    VARCHAR2(255)  NULL,
    causer_type   VARCHAR2(255)  NULL,
    causer_id     VARCHAR2(255)  NULL,
    properties    CLOB           NULL,     -- JSON com valores antes/depois
    created_at    TIMESTAMP      NULL,
    updated_at    TIMESTAMP      NULL,
    event         VARCHAR2(255)  NULL,     -- created | updated | deleted
    batch_uuid    VARCHAR2(36)   NULL,
    --
    CONSTRAINT pk_activity_log PRIMARY KEY (id)
);

CREATE INDEX idx_activity_log_subject  ON activity_log(subject_type, subject_id);
CREATE INDEX idx_activity_log_causer   ON activity_log(causer_type, causer_id);
CREATE INDEX idx_activity_log_created  ON activity_log(created_at);
CREATE INDEX idx_activity_log_event    ON activity_log(event);
```
