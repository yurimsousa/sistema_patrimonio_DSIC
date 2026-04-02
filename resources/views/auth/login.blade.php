<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Patrimônio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #1a3a5c 0%, #0d2137 60%, #0a1a2e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        }
        .login-header {
            background: linear-gradient(135deg, #1a3a5c, #2563a8);
            border-radius: 16px 16px 0 0;
            padding: 2rem;
            text-align: center;
        }
        .login-body { padding: 2rem; }
        .form-control:focus { border-color: #2563a8; box-shadow: 0 0 0 .2rem rgba(37,99,168,.25); }
        .btn-login {
            background: linear-gradient(135deg, #1a3a5c, #2563a8);
            border: none;
            padding: .75rem;
            font-size: 1rem;
            letter-spacing: .5px;
        }
        .btn-login:hover { background: linear-gradient(135deg, #2563a8, #1a3a5c); }
        .input-group-text { background: #f8f9fa; }
    </style>
</head>
<body>
    <div class="login-card card">
        <div class="login-header">
            <div class="mb-3">
                <i class="bi bi-building-gear text-white" style="font-size: 3rem;"></i>
            </div>
            <h4 class="text-white fw-bold mb-1">Sistema de Patrimônio</h4>
            <p class="text-white-50 mb-0 small">Gestão de Bens Patrimoniais</p>
        </div>
        <div class="login-body">
            @if(session('status'))
                <div class="alert alert-success small">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold small">E-mail</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="seu@email.com"
                            required autofocus autocomplete="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Senha</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock text-muted"></i></span>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="••••••••" required autocomplete="current-password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label small" for="remember">Lembrar de mim</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-login btn-primary w-100 text-white">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                </button>
            </form>
        </div>
        <div class="card-footer text-center bg-transparent border-0 pb-3">
            <small class="text-muted">Sistema de Gestão de Patrimônio &copy; {{ date('Y') }}</small>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
