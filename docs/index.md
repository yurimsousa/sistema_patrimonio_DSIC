# Sistema de Gestão de Patrimônio

Bem-vindo à documentação técnica do **Sistema de Gestão de Patrimônio**.

## Sobre o Projeto

Sistema web desenvolvido em **Laravel 11 + Oracle** para o gerenciamento completo de bens patrimoniais de uma organização. Permite cadastrar, localizar e rastrear equipamentos (computadores, celulares, TVs, etc.) distribuídos entre unidades, salas e usuários responsáveis.

## Funcionalidades Principais

| Módulo | Descrição |
|---|---|
| **Dashboard** | Visão geral com totais, gráficos por categoria e unidade |
| **Bens** | CRUD completo com filtro dinâmico por unidade e sala |
| **Usuários** | Cadastro de responsáveis com atribuição de bens |
| **Unidades** | Gestão de unidades/setores da organização |
| **Salas** | Salas vinculadas às unidades |
| **Categorias** | Tipificação dos bens (Computador, Celular, TV, etc.) |
| **Auditoria** | Log completo de todas as operações do sistema |
| **Autenticação** | Login com perfis de acesso (Admin, Auditor, Usuário) |

## Navegação da Documentação

=== "Início Rápido"
    Consulte [Início Rápido (5 min)](visao-geral/readme.md) para colocar a aplicação no ar rapidamente.

=== "Banco de Dados"
    Consulte [DDL](banco-de-dados/ddl.md), [Modelo Lógico](banco-de-dados/modelo-logico.md) e [DER](banco-de-dados/der.md) para entender a estrutura de dados.

=== "Manutenção"
    Consulte o [Setup de Desenvolvimento](manutencao/setup-dev.md) para configurar o ambiente local.

=== "Evolução"
    Consulte [Deploy e Dockerização](evolucao/producao.md) para levar o sistema a produção.

## Perfis de Acesso

```
┌─────────────────────────────────────────────┐
│  admin@patrimonio.com   →  Administrador    │
│  auditor@patrimonio.com →  Auditor          │
│  operador@patrimonio.com →  Usuário         │
└─────────────────────────────────────────────┘
```

!!! info "Versão"
    Documentação gerada para a versão **1.0.0** do sistema.
