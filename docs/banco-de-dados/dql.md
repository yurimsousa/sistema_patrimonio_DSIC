# DQL — Consultas Úteis

Consultas SQL para relatórios, verificações operacionais e análise de dados.

---

## Dashboard — Totalizadores

```sql
-- Totais gerais
SELECT
    COUNT(*)                                        AS total_bens,
    SUM(CASE WHEN status = 'ativo'       THEN 1 ELSE 0 END) AS bens_ativos,
    SUM(CASE WHEN status = 'manutencao'  THEN 1 ELSE 0 END) AS bens_manutencao,
    SUM(CASE WHEN status = 'descartado'  THEN 1 ELSE 0 END) AS bens_descartados,
    SUM(CASE WHEN status = 'inativo'     THEN 1 ELSE 0 END) AS bens_inativos
FROM bens;
```

```sql
-- Bens por categoria (para gráfico)
SELECT
    c.nome        AS categoria,
    COUNT(b.id)   AS quantidade
FROM categorias_bem c
LEFT JOIN bens b ON b.categoria_id = c.id
GROUP BY c.id, c.nome
ORDER BY quantidade DESC;
```

```sql
-- Top 5 unidades por quantidade de bens
SELECT
    u.nome      AS unidade,
    u.sigla,
    COUNT(b.id) AS total_bens
FROM unidades u
LEFT JOIN bens b ON b.unidade_id = u.id
GROUP BY u.id, u.nome, u.sigla
ORDER BY total_bens DESC
FETCH FIRST 5 ROWS ONLY;
```

---

## Bens

### Filtro completo (unidade + sala + categoria + status)

```sql
SELECT
    b.id,
    b.nome,
    b.numero_patrimonio,
    b.marca,
    b.modelo,
    b.status,
    c.nome   AS categoria,
    un.nome  AS unidade,
    s.nome   AS sala,
    us.nome  AS responsavel
FROM bens b
JOIN  categorias_bem c  ON c.id  = b.categoria_id
LEFT JOIN unidades   un ON un.id = b.unidade_id
LEFT JOIN salas      s  ON s.id  = b.sala_id
LEFT JOIN usuarios   us ON us.id = b.usuario_id
WHERE (:unidade_id  IS NULL OR b.unidade_id  = :unidade_id)
  AND (:sala_id     IS NULL OR b.sala_id     = :sala_id)
  AND (:categoria_id IS NULL OR b.categoria_id = :categoria_id)
  AND (:status      IS NULL OR b.status      = :status)
  AND (:busca       IS NULL OR LOWER(b.nome) LIKE '%' || LOWER(:busca) || '%'
                            OR b.numero_patrimonio LIKE '%' || :busca || '%')
ORDER BY b.created_at DESC;
```

### Bens sem localização definida

```sql
SELECT b.id, b.nome, b.numero_patrimonio, b.status
FROM bens b
WHERE b.unidade_id IS NULL
  AND b.sala_id    IS NULL
ORDER BY b.nome;
```

### Bens sem responsável

```sql
SELECT b.id, b.nome, b.numero_patrimonio, b.status
FROM bens b
WHERE b.usuario_id IS NULL
  AND b.status     = 'ativo'
ORDER BY b.nome;
```

### Valor total do patrimônio por unidade

```sql
SELECT
    u.nome               AS unidade,
    COUNT(b.id)          AS quantidade,
    SUM(b.valor)         AS valor_total,
    AVG(b.valor)         AS valor_medio
FROM unidades u
LEFT JOIN bens b ON b.unidade_id = u.id AND b.status != 'descartado'
GROUP BY u.id, u.nome
ORDER BY valor_total DESC NULLS LAST;
```

---

## Auditoria

### Todas as ações de um usuário nos últimos 30 dias

```sql
SELECT
    al.created_at,
    al.event,
    al.description,
    al.subject_type,
    al.subject_id
FROM activity_log al
WHERE al.causer_type = 'App\Models\User'
  AND al.causer_id   = :user_id
  AND al.created_at >= CURRENT_TIMESTAMP - INTERVAL '30' DAY
ORDER BY al.created_at DESC;
```

### Histórico completo de alterações de um bem

```sql
SELECT
    al.created_at,
    al.event,
    al.description,
    u.name    AS operador,
    al.properties
FROM activity_log al
LEFT JOIN users u ON u.id = al.causer_id
WHERE al.subject_type = 'App\Models\Bem'
  AND al.subject_id   = :bem_id
ORDER BY al.created_at DESC;
```

### Resumo de ações por módulo

```sql
SELECT
    al.subject_type            AS modulo,
    al.event,
    COUNT(*)                   AS total
FROM activity_log al
WHERE al.created_at >= TRUNC(SYSDATE, 'MM')   -- mês corrente
GROUP BY al.subject_type, al.event
ORDER BY total DESC;
```

### Bens alterados nas últimas 24 horas

```sql
SELECT
    al.created_at,
    al.description,
    u.name   AS operador,
    u.email
FROM activity_log al
LEFT JOIN users u ON u.id = al.causer_id
WHERE al.subject_type = 'App\Models\Bem'
  AND al.event        IN ('created', 'updated', 'deleted')
  AND al.created_at   >= CURRENT_TIMESTAMP - INTERVAL '1' DAY
ORDER BY al.created_at DESC;
```

---

## Relatórios Operacionais

### Inventário completo por sala

```sql
SELECT
    un.nome      AS unidade,
    s.nome       AS sala,
    s.numero     AS numero_sala,
    s.andar,
    c.nome       AS categoria,
    b.nome       AS bem,
    b.numero_patrimonio,
    b.marca,
    b.modelo,
    b.status,
    us.nome      AS responsavel
FROM salas s
JOIN  unidades     un ON un.id = s.unidade_id
LEFT JOIN bens     b  ON b.sala_id = s.id
LEFT JOIN categorias_bem c  ON c.id = b.categoria_id
LEFT JOIN usuarios us ON us.id = b.usuario_id
WHERE s.ativo = 1
ORDER BY un.nome, s.nome, c.nome, b.nome;
```

### Bens em manutenção com mais de 30 dias

```sql
SELECT
    b.id,
    b.nome,
    b.numero_patrimonio,
    b.updated_at            AS entrada_manutencao,
    SYSDATE - b.updated_at  AS dias_em_manutencao,
    un.nome                 AS unidade
FROM bens b
LEFT JOIN unidades un ON un.id = b.unidade_id
WHERE b.status    = 'manutencao'
  AND b.updated_at < SYSDATE - 30
ORDER BY b.updated_at ASC;
```
