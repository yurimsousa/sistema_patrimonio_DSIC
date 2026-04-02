<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Patrimônio') - Sistema de Patrimônio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1a3a5c 0%, #0d2137 100%);
            width: 250px;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            box-shadow: 2px 0 10px rgba(0,0,0,0.3);
        }
        .sidebar .brand {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.75);
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.12);
            border-left: 3px solid #4fa3e3;
        }
        .sidebar .nav-section {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.4);
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 1rem 1.5rem 0.3rem;
        }
        .main-content { margin-left: 250px; }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #e0e0e0;
            padding: 0.75rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .page-content { padding: 1.5rem; }
        .card { border: none; box-shadow: 0 1px 6px rgba(0,0,0,0.08); border-radius: 10px; }
        .stat-card { border-radius: 10px; transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-2px); }
        .table th { font-size: 0.78rem; text-transform: uppercase; color: #6c757d; font-weight: 600; letter-spacing: 0.5px; }
        .badge { font-size: 0.73rem; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="sidebar">
        <div class="brand">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-building-gear text-info fs-4"></i>
                <div>
                    <div class="text-white fw-bold" style="font-size:0.95rem;">Patrimônio</div>
                    <div class="text-white-50" style="font-size:0.72rem;">Gestão de Bens</div>
                </div>
            </div>
        </div>

        <ul class="nav flex-column mt-2 pb-4">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>

            <div class="nav-section">Patrimônio</div>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('bens.*') ? 'active' : '' }}" href="{{ route('bens.index') }}">
                    <i class="bi bi-laptop me-2"></i> Bens
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('categorias.*') ? 'active' : '' }}" href="{{ route('categorias.index') }}">
                    <i class="bi bi-tags me-2"></i> Categorias
                </a>
            </li>

            <div class="nav-section">Localização</div>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('unidades.*') ? 'active' : '' }}" href="{{ route('unidades.index') }}">
                    <i class="bi bi-building me-2"></i> Unidades
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('salas.*') ? 'active' : '' }}" href="{{ route('salas.index') }}">
                    <i class="bi bi-door-open me-2"></i> Salas
                </a>
            </li>

            <div class="nav-section">Pessoas</div>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                    <i class="bi bi-people me-2"></i> Usuários
                </a>
            </li>

            @auth
                @if(auth()->user()->isAuditor())
                    <div class="nav-section">Controle</div>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('auditoria.*') ? 'active' : '' }}" href="{{ route('auditoria.index') }}">
                            <i class="bi bi-shield-check me-2"></i> Auditoria
                        </a>
                    </li>
                @endif
            @endauth
        </ul>

        @auth
        <div class="mt-auto px-3 pb-3" style="position:absolute;bottom:0;width:100%;">
            <div class="border-top border-white border-opacity-10 pt-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-circle bg-white bg-opacity-10 d-flex align-items-center justify-content-center" style="width:34px;height:34px;flex-shrink:0;">
                        <i class="bi bi-person text-white"></i>
                    </div>
                    <div style="overflow:hidden;">
                        <div class="text-white fw-semibold text-truncate" style="font-size:0.82rem;">{{ auth()->user()->name }}</div>
                        <span class="badge bg-{{ auth()->user()->role_color }}" style="font-size:0.65rem;">{{ auth()->user()->role_label }}</span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-sm w-100 text-white-50 border-0 text-start p-1" style="background:rgba(255,255,255,0.05);font-size:0.8rem;">
                        <i class="bi bi-box-arrow-left me-2"></i>Sair
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </nav>

    <div class="main-content">
        <div class="topbar d-flex align-items-center justify-content-between">
            <h6 class="mb-0 fw-semibold text-dark">@yield('page-title', 'Dashboard')</h6>
            <div class="d-flex align-items-center gap-3">
                <small class="text-muted">
                    <i class="bi bi-calendar3 me-1"></i>{{ now()->format('d/m/Y') }}
                </small>
                @auth
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-circle text-secondary"></i>
                    <small class="text-dark fw-semibold">{{ auth()->user()->name }}</small>
                    <span class="badge bg-{{ auth()->user()->role_color }}">{{ auth()->user()->role_label }}</span>
                </div>
                @endauth
            </div>
        </div>

        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Corrija os erros abaixo:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
