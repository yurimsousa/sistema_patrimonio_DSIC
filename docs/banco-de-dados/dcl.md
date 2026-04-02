# DCL — Controle de Acesso no Oracle

Scripts de criação do usuário Oracle e concessão de privilégios para o schema do sistema.

---

## Criação do Usuário / Schema

Execute como **DBA** (sys ou system):

```sql
-- Criar o usuário do schema
CREATE USER patrimonio
    IDENTIFIED BY "SuaSenhaForte@2024"
    DEFAULT TABLESPACE users
    TEMPORARY TABLESPACE temp
    QUOTA UNLIMITED ON users;

-- Privilégios de conexão e recursos
GRANT CREATE SESSION   TO patrimonio;
GRANT CREATE TABLE     TO patrimonio;
GRANT CREATE SEQUENCE  TO patrimonio;
GRANT CREATE VIEW      TO patrimonio;
GRANT CREATE PROCEDURE TO patrimonio;
GRANT CREATE TRIGGER   TO patrimonio;

COMMIT;
```

---

## Perfis de Acesso ao Banco (Oracle Roles)

### Papel: Aplicação (leitura e escrita)

Usado pela aplicação Laravel (string de conexão).

```sql
CREATE ROLE role_patrimonio_app;

GRANT SELECT, INSERT, UPDATE, DELETE ON patrimonio.users          TO role_patrimonio_app;
GRANT SELECT, INSERT, UPDATE, DELETE ON patrimonio.unidades       TO role_patrimonio_app;
GRANT SELECT, INSERT, UPDATE, DELETE ON patrimonio.salas          TO role_patrimonio_app;
GRANT SELECT, INSERT, UPDATE, DELETE ON patrimonio.categorias_bem TO role_patrimonio_app;
GRANT SELECT, INSERT, UPDATE, DELETE ON patrimonio.usuarios       TO role_patrimonio_app;
GRANT SELECT, INSERT, UPDATE, DELETE ON patrimonio.bens           TO role_patrimonio_app;
GRANT SELECT, INSERT, UPDATE, DELETE ON patrimonio.activity_log   TO role_patrimonio_app;

-- Sequências
GRANT SELECT ON patrimonio.seq_users          TO role_patrimonio_app;
GRANT SELECT ON patrimonio.seq_unidades       TO role_patrimonio_app;
GRANT SELECT ON patrimonio.seq_salas          TO role_patrimonio_app;
GRANT SELECT ON patrimonio.seq_categorias_bem TO role_patrimonio_app;
GRANT SELECT ON patrimonio.seq_usuarios       TO role_patrimonio_app;
GRANT SELECT ON patrimonio.seq_bens           TO role_patrimonio_app;
GRANT SELECT ON patrimonio.seq_activity_log   TO role_patrimonio_app;
```

### Papel: Auditoria (somente leitura)

Para usuários do DBA ou auditores externos que acessam diretamente pelo banco.

```sql
CREATE ROLE role_patrimonio_audit;

GRANT SELECT ON patrimonio.bens          TO role_patrimonio_audit;
GRANT SELECT ON patrimonio.usuarios      TO role_patrimonio_audit;
GRANT SELECT ON patrimonio.unidades      TO role_patrimonio_audit;
GRANT SELECT ON patrimonio.salas         TO role_patrimonio_audit;
GRANT SELECT ON patrimonio.categorias_bem TO role_patrimonio_audit;
GRANT SELECT ON patrimonio.activity_log  TO role_patrimonio_audit;
GRANT SELECT ON patrimonio.users         TO role_patrimonio_audit;
```

### Papel: Relatórios (somente leitura)

```sql
CREATE ROLE role_patrimonio_relatorio;

GRANT SELECT ON patrimonio.bens          TO role_patrimonio_relatorio;
GRANT SELECT ON patrimonio.unidades      TO role_patrimonio_relatorio;
GRANT SELECT ON patrimonio.salas         TO role_patrimonio_relatorio;
GRANT SELECT ON patrimonio.categorias_bem TO role_patrimonio_relatorio;
GRANT SELECT ON patrimonio.usuarios      TO role_patrimonio_relatorio;
```

---

## Atribuir Roles a Usuários

```sql
-- Usuário da aplicação Laravel
GRANT role_patrimonio_app TO patrimonio;

-- Usuário de auditoria DBA
CREATE USER auditoria_dba IDENTIFIED BY "SenhaAudit@2024";
GRANT CREATE SESSION TO auditoria_dba;
GRANT role_patrimonio_audit TO auditoria_dba;

-- Usuário de relatórios
CREATE USER relatorio_bi IDENTIFIED BY "SenhaBI@2024";
GRANT CREATE SESSION TO relatorio_bi;
GRANT role_patrimonio_relatorio TO relatorio_bi;

COMMIT;
```

---

## Revogar Acesso

```sql
-- Revogar papel de um usuário
REVOKE role_patrimonio_app FROM patrimonio;

-- Revogar um privilégio específico
REVOKE DELETE ON patrimonio.bens FROM role_patrimonio_app;

-- Desabilitar usuário (sem remover)
ALTER USER auditoria_dba ACCOUNT LOCK;

COMMIT;
```

---

## Verificar Privilégios

```sql
-- Verificar grants do usuário patrimonio
SELECT grantee, privilege, table_name
FROM   dba_tab_privs
WHERE  grantee = 'PATRIMONIO'
ORDER BY table_name, privilege;

-- Verificar roles do usuário
SELECT granted_role, admin_option, default_role
FROM   dba_role_privs
WHERE  grantee = 'PATRIMONIO';
```
