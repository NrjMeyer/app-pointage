<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Pointeuse - Administration</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .navbar-brand {
            font-weight: 600;
        }

        .nav-link.active {
            font-weight: 600;
            color: #fff !important;
        }

        .page-wrapper {
            padding-top: 20px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        }
        .card-modern {
            border-radius: 14px;
            border: none;
            background: #ffffff;
            padding: 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            transition: all 0.25s ease;
        }

        .card-modern:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.09);
        }

        .card-modern .card-header {
            background: #f8f9fc;
            border-bottom: none;
            padding: 18px 22px;
            border-radius: 14px 14px 0 0;
            font-weight: 600;
            font-size: 1.1rem;
            color: #2d3748;
        }

        .card-modern .card-body {
            padding: 22px;
        }

        .card-modern .card-footer {
            background: #fafbff;
            border-top: none;
            padding: 16px 22px;
            border-radius: 0 0 14px 14px;
        }

        .btn-modern {
            border-radius: 10px;
            padding: 8px 18px;
            font-weight: 500;
            transition: all 0.25s ease;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand" href="{{ route('dashboard') }}">Pointeuse</a>

        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div id="mainNavbar" class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">

                @auth

                    <li class="nav-item">
                        @if(auth()->user()->UTI_Role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                Tableau de bord
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}"
                               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                Tableau de bord
                            </a>
                        @endif
                    </li>

                    @if(auth()->user()->UTI_Role === 'admin')

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button"
                               data-bs-toggle="dropdown">
                                Administration
                            </a>
                            <ul class="dropdown-menu">

                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                        Utilisateurs
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.sessions.index') }}">
                                        Sessions
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.sessions.crosstable') }}">
                                        Tableau croisé
                                    </a>
                                </li>

                            </ul>
                        </li>

                    @endif
                @endauth

            </ul>

            <ul class="navbar-nav ms-auto">
                @auth
                    <li class="nav-item">
                        <span class="navbar-text me-3">
                            👤 {{ auth()->user()->UTI_Nom }}
                        </span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-danger" href="#"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Déconnexion
                        </a>
                    </li>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<div class="container page-wrapper">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
