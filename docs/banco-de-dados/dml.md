# DML — Manipulação de Dados

Scripts de inserção, atualização e remoção. Representam os dados iniciais (seed) e operações comuns do sistema.

---

## Inserção — Dados Iniciais (Seed)

### Usuários do Sistema (login)

```sql
INSERT INTO users (name, email, password, role, created_at, updated_at)
VALUES ('Administrador',  'admin@patrimonio.com',
        '$2y$12$hash_bcrypt_admin',    'admin',   CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO users (name, email, password, role, created_at, updated_at)
VALUES ('Auditor Geral',  'auditor@patrimonio.com',
        '$2y$12$hash_bcrypt_auditor',  'auditor', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

INSERT INTO users (name, email, password, role, created_at, updated_at)
VALUES ('Operador',       'operador@patrimonio.com',
        '$2y$12$hash_bcrypt_operador', 'usuario', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
```

!!! warning "Senhas"
    As senhas são armazenadas com hash Bcrypt (12 rounds).
    Para gerar via Laravel: `Hash::make('senha')`.

---

### Categorias de Bens

```sql
INSERT INTO categorias_bem (nome, descricao) VALUES ('Computador',        'Desktops e computadores fixos');
INSERT INTO categorias_bem (nome, descricao) VALUES ('Notebook',          'Laptops e computadores portáteis');
INSERT INTO categorias_bem (nome, descricao) VALUES ('Celular/Smartphone','Telefones celulares e smartphones');
INSERT INTO categorias_bem (nome, descricao) VALUES ('Tablet',            'Tablets e iPads');
INSERT INTO categorias_bem (nome, descricao) VALUES ('Televisão/Monitor', 'TVs e monitores');
INSERT INTO categorias_bem (nome, descricao) VALUES ('Impressora',        'Impressoras e multifuncionais');
INSERT INTO categorias_bem (nome, descricao) VALUES ('Projetor',          'Projetores e datashow');
INSERT INTO categorias_bem (nome, descricao) VALUES ('Mobiliário',        'Mesas, cadeiras e armários');
INSERT INTO categorias_bem (nome, descricao) VALUES ('Equipamento de Rede','Switches, roteadores, access points');
INSERT INTO categorias_bem (nome, descricao) VALUES ('Outros',            'Outros equipamentos');
COMMIT;
```

---

### Unidades

```sql
INSERT INTO unidades (nome, sigla, endereco, ativo)
VALUES ('Sede Administrativa', 'SEDE', 'Rua Principal, 100 - Centro', 1);

INSERT INTO unidades (nome, sigla, endereco, ativo)
VALUES ('Unidade de TI', 'UTI', 'Av. Tecnologia, 200 - Bloco B', 1);

INSERT INTO unidades (nome, sigla, endereco, ativo)
VALUES ('Filial Norte', 'FN', 'Rua Norte, 50 - Bairro Norte', 1);
COMMIT;
```

---

### Salas (exemplo para a Sede)

```sql
-- Supondo que a Sede tem id = 1
INSERT INTO salas (unidade_id, nome, numero, andar, ativo) VALUES (1, 'Diretoria',        '101', '1°',    1);
INSERT INTO salas (unidade_id, nome, numero, andar, ativo) VALUES (1, 'Sala de Reuniões', '102', '1°',    1);
INSERT INTO salas (unidade_id, nome, numero, andar, ativo) VALUES (1, 'RH',               '201', '2°',    1);
INSERT INTO salas (unidade_id, nome, numero, andar, ativo) VALUES (2, 'Suporte',          '001', 'Térreo',1);
INSERT INTO salas (unidade_id, nome, numero, andar, ativo) VALUES (2, 'Desenvolvimento',  '002', 'Térreo',1);
INSERT INTO salas (unidade_id, nome, numero, andar, ativo) VALUES (3, 'Atendimento',      '01',  'Térreo',1);
COMMIT;
```

---

### Usuários (responsáveis)

```sql
INSERT INTO usuarios (nome, matricula, email, cargo, telefone, unidade_id, ativo)
VALUES ('Carlos Mendes',   '001', 'carlos@empresa.com',   'Diretor',        '(11) 99001-1111', 1, 1);

INSERT INTO usuarios (nome, matricula, email, cargo, telefone, unidade_id, ativo)
VALUES ('Ana Paula Silva', '002', 'ana@empresa.com',      'Analista de RH', '(11) 99002-2222', 1, 1);

INSERT INTO usuarios (nome, matricula, email, cargo, telefone, unidade_id, ativo)
VALUES ('Roberto Costa',   '003', 'roberto@empresa.com',  'Analista de TI', '(11) 99003-3333', 2, 1);

INSERT INTO usuarios (nome, matricula, email, cargo, telefone, unidade_id, ativo)
VALUES ('Fernanda Lima',   '004', 'fernanda@empresa.com', 'Desenvolvedora', '(11) 99004-4444', 2, 1);
COMMIT;
```

---

## Atualização — Exemplos Comuns

### Alterar status de um bem para manutenção

```sql
UPDATE bens
SET    status     = 'manutencao',
       updated_at = CURRENT_TIMESTAMP
WHERE  id = :id;
COMMIT;
```

### Transferir bem para outra unidade/sala

```sql
UPDATE bens
SET    unidade_id = :nova_unidade_id,
       sala_id    = :nova_sala_id,
       updated_at = CURRENT_TIMESTAMP
WHERE  id = :id;
COMMIT;
```

### Atribuir bem a um usuário responsável

```sql
UPDATE bens
SET    usuario_id = :usuario_id,
       updated_at = CURRENT_TIMESTAMP
WHERE  id = :id;
COMMIT;
```

### Desativar uma unidade

```sql
UPDATE unidades
SET    ativo      = 0,
       updated_at = CURRENT_TIMESTAMP
WHERE  id = :id;
COMMIT;
```

---

## Remoção — Exemplos

### Remover bem (soft delete não implementado — exclusão física)

```sql
DELETE FROM bens WHERE id = :id;
COMMIT;
```

!!! warning "Auditoria"
    A exclusão de qualquer registro dispara automaticamente um log na tabela `activity_log` via `spatie/laravel-activitylog`.

### Limpar logs de auditoria com mais de 1 ano

```sql
DELETE FROM activity_log
WHERE  created_at < ADD_MONTHS(CURRENT_TIMESTAMP, -12);
COMMIT;
```
