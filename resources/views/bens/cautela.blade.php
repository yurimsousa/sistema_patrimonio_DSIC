<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cautela — {{ $bem->nome }}</title>
    <style>
        /* ===== CONFIGURAÇÕES DE IMPRESSÃO ===== */
        @page {
            size: A4 portrait;
            margin: 2.5cm 2.5cm 3cm 2.5cm;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            background: #fff;
            line-height: 1.6;
        }

        /* ===== CABEÇALHO ===== */
        .cabecalho {
            text-align: center;
            margin-bottom: 30pt;
            border-bottom: 2px solid #000;
            padding-bottom: 16pt;
        }

        .brasao {
            width: 80px;
            height: 80px;
            margin: 0 auto 10pt;
            display: block;
        }

        .cabecalho-orgao {
            font-size: 10.5pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.5;
        }

        .cabecalho-dpto {
            font-size: 10pt;
            line-height: 1.4;
        }

        /* ===== TÍTULO ===== */
        .titulo {
            text-align: center;
            margin: 24pt 0 20pt;
        }

        .titulo-cautela {
            font-size: 18pt;
            font-weight: bold;
            letter-spacing: 6px;
            text-transform: uppercase;
        }

        .titulo-subtitulo {
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 4pt;
        }

        /* ===== CORPO DO DOCUMENTO ===== */
        .corpo {
            text-align: justify;
            text-indent: 3cm;
            margin: 20pt 0;
            line-height: 1.8;
        }

        /* ===== DESCRIÇÃO DO BEM ===== */
        .descricao-bem {
            font-weight: bold;
            text-transform: uppercase;
            margin: 16pt 0;
            line-height: 1.8;
        }

        /* ===== DADOS COMPLEMENTARES ===== */
        .dados-complementares {
            margin: 16pt 0;
            border: 1px solid #000;
            padding: 10pt 14pt;
        }

        .dados-complementares table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11pt;
        }

        .dados-complementares td {
            padding: 3pt 6pt;
            vertical-align: top;
        }

        .dados-complementares td:first-child {
            font-weight: bold;
            white-space: nowrap;
            width: 35%;
        }

        /* ===== ASSINATURA ===== */
        .rodape {
            margin-top: 30pt;
        }

        .local-data {
            text-align: right;
            margin-bottom: 40pt;
            font-size: 11pt;
        }

        .assinatura {
            text-align: center;
            border-top: 1px solid #000;
            padding-top: 8pt;
            margin: 0 auto;
            width: 65%;
        }

        .assinatura-nome {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11pt;
            line-height: 1.5;
        }

        .assinatura-info {
            font-size: 10.5pt;
            line-height: 1.4;
        }

        /* ===== NOTA DE RODAPÉ ===== */
        .nota-rodape {
            margin-top: 30pt;
            font-size: 9pt;
            text-align: center;
            color: #444;
            border-top: 1px dotted #999;
            padding-top: 8pt;
        }

        /* ===== CONTROLES DE TELA (ocultados na impressão) ===== */
        .no-print {
            background: #1e3a5f;
            color: #fff;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            box-shadow: 0 -2px 10px rgba(0,0,0,.3);
            font-family: Arial, sans-serif;
        }

        .no-print button,
        .no-print a {
            padding: 8px 20px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            font-family: Arial, sans-serif;
        }

        .btn-imprimir {
            background: #28a745;
            color: #fff;
            border: none;
            font-weight: bold;
        }

        .btn-voltar {
            background: transparent;
            color: #ccc;
            border: 1px solid #ccc;
        }

        .no-print span {
            flex: 1;
            font-size: 13px;
            opacity: .85;
        }

        @media print {
            .no-print { display: none !important; }
            body { padding-bottom: 0; }
        }

        /* Espaço para a barra fixa não cobrir o conteúdo na tela */
        @media screen {
            body { padding-bottom: 70px; }
            .documento { max-width: 21cm; margin: 20px auto; padding: 2cm 2.5cm; background: #fff; box-shadow: 0 0 20px rgba(0,0,0,.15); }
        }
    </style>
</head>
<body>

@php
    // ============================================================
    // CONFIGURAÇÕES DO ÓRGÃO — edite conforme necessidade
    // ============================================================
    $orgaoLinha1 = config('app.cautela_orgao_1', 'PRESIDÊNCIA DA REPÚBLICA');
    $orgaoLinha2 = config('app.cautela_orgao_2', 'Secretaria de Governo');
    $orgaoLinha3 = config('app.cautela_orgao_3', 'Secretaria Especial de Comunicação Social');
    $orgaoLinha4 = config('app.cautela_orgao_4', 'Secretaria de Gestão e Controle');
    $departamento = config('app.cautela_dpto', 'Coordenação-Geral de Logística e TI');
    $cidade       = config('app.cautela_cidade', 'Brasília-DF');
    // ============================================================

    $responsavel  = $bem->usuario;
    $unidade      = $bem->unidade;
    $dataHoje     = now();

    // Meses em português
    $meses = ['janeiro','fevereiro','março','abril','maio','junho',
              'julho','agosto','setembro','outubro','novembro','dezembro'];
    $dataExtenso = $dataHoje->day . ' de ' . $meses[$dataHoje->month - 1] . ' de ' . $dataHoje->year;

    // Monta descrição do bem para a linha do documento
    $descBem = collect([
        $bem->numero_patrimonio ? 'Nº PATRIMÔNIO: ' . strtoupper($bem->numero_patrimonio) : null,
        strtoupper($bem->nome),
        $bem->numero_serie      ? 'SÉRIE: ' . strtoupper($bem->numero_serie)              : null,
        $bem->marca             ? 'MARCA: ' . strtoupper($bem->marca)                     : null,
        $bem->modelo            ? 'MODELO: ' . strtoupper($bem->modelo)                   : null,
    ])->filter()->implode(' — ');
@endphp

<div class="documento">

    {{-- =================== CABEÇALHO =================== --}}
    <div class="cabecalho">
        {{-- Brasão -- substitua o src por uma imagem real se disponível --}}
        <img src="{{ asset('brasao.png') }}" class="brasao" alt="Brasão">

        <div class="cabecalho-orgao">{{ $orgaoLinha1 }}</div>
        <div class="cabecalho-dpto">{{ $orgaoLinha2 }}</div>
        <div class="cabecalho-dpto">{{ $orgaoLinha3 }}</div>
        <div class="cabecalho-dpto">{{ $orgaoLinha4 }}</div>
        <div class="cabecalho-dpto">{{ $departamento }}</div>
    </div>

    {{-- =================== TÍTULO =================== --}}
    <div class="titulo">
        <div class="titulo-cautela">C A U T E L A</div>
        <div class="titulo-subtitulo">E M P R É S T I M O &nbsp; P R O V I S Ó R I O</div>
    </div>

    {{-- =================== CORPO =================== --}}
    <div class="corpo">
        Declaro, para os fins, que recebi da
        <strong>{{ $departamento }}</strong>
        @if($responsavel)
            , portador(a) do SIAPE nº <strong>{{ $responsavel->matricula ?? '___________' }}</strong>,
            da <strong>{{ $unidade->nome ?? 'unidade' }}</strong>
        @endif
        — o bem descrito abaixo em perfeito estado de conservação, assumindo total responsabilidade pela guarda e conservação do mesmo.
    </div>

    {{-- =================== DESCRIÇÃO DO BEM =================== --}}
    <div class="descricao-bem">
        {{ $descBem }}
    </div>

    {{-- =================== DADOS COMPLEMENTARES =================== --}}
    <div class="dados-complementares">
        <table>
            <tr>
                <td>Categoria:</td>
                <td>{{ $bem->categoria->nome ?? '—' }}</td>
                <td><strong>Status:</strong></td>
                <td>{{ $bem->status_label }}</td>
            </tr>
            @if($bem->valor)
            <tr>
                <td>Valor:</td>
                <td>R$ {{ number_format($bem->valor, 2, ',', '.') }}</td>
                <td><strong>Aquisição:</strong></td>
                <td>{{ $bem->data_aquisicao?->format('d/m/Y') ?? '—' }}</td>
            </tr>
            @endif
            @if($bem->sala)
            <tr>
                <td>Localização:</td>
                <td colspan="3">{{ $unidade->nome ?? '' }}{{ $bem->sala ? ' — ' . $bem->sala->nome : '' }}</td>
            </tr>
            @endif
            @if($bem->descricao)
            <tr>
                <td>Descrição:</td>
                <td colspan="3">{{ $bem->descricao }}</td>
            </tr>
            @endif
        </table>
    </div>

    {{-- =================== LOCAL E DATA =================== --}}
    <div class="local-data">
        {{ $cidade }}, {{ $dataExtenso }}.
    </div>

    {{-- =================== ASSINATURA =================== --}}
    <div class="assinatura">
        @if($responsavel)
            <div class="assinatura-nome">{{ $responsavel->nome }}</div>
            <div class="assinatura-info">
                @if($responsavel->matricula) SIAPE: {{ $responsavel->matricula }}<br>@endif
            </div>
        @else
            <div class="assinatura-nome">_______________________________</div>
            <div class="assinatura-info">Nome do Responsável<br>Matrícula/SIAPE</div>
        @endif
    </div>

    {{-- =================== NOTA DE RODAPÉ =================== --}}
    <div class="nota-rodape">
        Documento gerado em {{ now()->format('d/m/Y \à\s H:i') }} pelo Sistema de Gestão de Patrimônio.
    </div>

</div>

{{-- =================== BARRA DE AÇÕES (somente tela) =================== --}}
<div class="no-print">
    <span>Clique em <strong>Imprimir</strong> para gerar o PDF desta cautela.</span>
    <button class="btn-imprimir" onclick="window.print()">🖨️ Imprimir / Salvar PDF</button>
    <a class="btn-voltar" href="{{ route('bens.show', $bem->id) }}">← Voltar</a>
</div>

</body>
</html>
