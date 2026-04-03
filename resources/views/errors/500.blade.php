<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Erro Interno | Sistema de Patrimônio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="text-center px-4" style="max-width: 480px;">
        <div class="mb-4">
            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 5rem;"></i>
        </div>
        <h1 class="display-4 fw-bold text-warning">500</h1>
        <h2 class="h4 fw-semibold mb-3">Erro Interno do Servidor</h2>
        <p class="text-muted mb-4">Ocorreu um erro inesperado. Nossa equipe foi notificada. Tente novamente em alguns instantes.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">
            <i class="bi bi-house me-2"></i>Voltar ao início
        </a>
    </div>
</body>
</html>
