# Débito Técnico

Limitações conhecidas, decisões de design e melhorias planejadas para versões futuras.

---

## Débitos Ativos

### 1. Sem testes automatizados (PHPUnit / Pest)

**Impacto:** Alto
**Descrição:** O projeto não possui nenhuma suite de testes automatizados. Toda validação é manual, o que aumenta o risco de regressão a cada alteração.
**Solução recomendada:** Adicionar testes de feature com `php artisan make:test` usando banco SQLite in-memory para rodar em CI/CD.

---

### 2. Controle de acesso por ENUM na tabela `users`

**Impacto:** Médio
**Descrição:** Os perfis (`admin`, `auditor`, `usuario`) são gerenciados como um campo `role` na tabela `users`. Funciona para o escopo atual, mas não suporta permissões granulares por recurso.
**Solução recomendada:** Adotar `spatie/laravel-permission` para roles e permissions com tabelas dedicadas, permitindo controle fino (ex: "pode exportar", "pode ver valores").

---

### 3. Sem upload de imagens ou anexos nos bens

**Impacto:** Baixo-Médio
**Descrição:** Não é possível anexar fotos ou documentos (NF, termo de recebimento) ao bem patrimonial.
**Solução recomendada:** Adicionar tabela `bem_anexos` com referência ao `Storage` do Laravel. Usar `Storage::disk('local')` ou S3 para armazenamento.

---

### 4. Sem histórico de movimentação de bens

**Impacto:** Médio
**Descrição:** A auditoria registra que o bem mudou de unidade/sala, mas não há uma visão cronológica de "onde este bem esteve". Só é possível ver isso consultando o `activity_log`.
**Solução recomendada:** Criar tabela `bem_movimentacoes` com origem, destino, data, responsável e motivo — com interface dedicada.

---

### 5. Sem exportação de relatórios (PDF/Excel)

**Impacto:** Médio
**Descrição:** Os dados só são acessíveis via interface web. Não há como exportar listagem de bens para PDF ou planilha.
**Solução recomendada:** Integrar `barryvdh/laravel-dompdf` para PDF e `maatwebsite/excel` para XLSX.

---

### 6. Dependência de CDN para Bootstrap e Bootstrap Icons

**Impacto:** Baixo
**Descrição:** Os assets de frontend são carregados de CDN externo (`cdn.jsdelivr.net`). Em ambientes sem acesso à internet (intranet corporativa), a interface quebraria.
**Solução recomendada:** Instalar Bootstrap via npm/Vite e compilar localmente com `npm run build`.

---

### 7. Sem validação de duplicidade no número de série

**Impacto:** Baixo
**Descrição:** O campo `numero_serie` não possui constraint UNIQUE no banco nem validação de unicidade no formulário. É possível cadastrar dois bens com o mesmo número de série.
**Solução recomendada:** Adicionar `Rule::unique('bens', 'numero_serie')->ignore($bem->id)` na validação do controller e índice UNIQUE no banco.

---

### 8. Sem paginação configurável pelo usuário

**Impacto:** Baixo
**Descrição:** Todas as listagens usam `paginate(15)` fixo. O usuário não pode escolher quantos itens ver por página.
**Solução recomendada:** Implementar parâmetro `?por_pagina=` com valor padrão salvo em sessão.

---

### 9. Filtros de busca sem persistência de estado

**Impacto:** Baixo
**Descrição:** Ao navegar para outra página e voltar, os filtros aplicados nas listagens são perdidos.
**Solução recomendada:** Persistir os parâmetros de busca na sessão ou usar URL como fonte de verdade (já parcialmente implementado via `request()`).

---

## Decisões de Design Conscientes

Estas não são débitos — foram escolhas deliberadas para o escopo atual:

| Decisão                            | Motivo                                                        |
|------------------------------------|---------------------------------------------------------------|
| SQLite em desenvolvimento          | Elimina dependência do Oracle para rodar localmente           |
| `spatie/laravel-activitylog`       | Auditoria sem alterar controllers; zero boilerplate           |
| Sem SPA/Vue/React                  | Blade + Bootstrap é suficiente para o escopo; menos complexidade |
| Sem filas (queues)                 | Volume de operações não justifica processamento assíncrono    |
| `role` como ENUM em `users`        | Simples e funcional para 3 perfis; adequado ao escopo         |
| Sem soft deletes em bens           | Registros deletados ficam na auditoria; suficiente para rastreio |
