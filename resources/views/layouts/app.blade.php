<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Pointeuse - Administration</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/custom.css', 'resources/js/app.js'])
</head>

<body>

    {{--<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
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
    </nav>--}}

    <div class="layout page-wrapper">
        <aside class="sidebar">
            <div>
                <div class="sidebar-logo">
                    <img src="{{asset('/images/logo.png')}}" alt="Pointeuse Logo">
                </div>

                <div class="sidebar-section-title">GÉNÉRAL</div>
                <ul class="sidebar-menu">
                    @if(auth()->user()->UTI_Role === 'admin')
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-line"></i> Tableau de bord
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.sessions.crosstable') }}" class="{{ request()->routeIs('admin.sessions.crosstable') ? 'active' : '' }}">
                            <i class="fas fa-table"></i> Tableau croisé
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.sessions.index') }}" class="{{ request()->routeIs('admin.sessions.index') ? 'active' : '' }}">
                            <i class="fas fa-clock"></i> Sessions
                        </a>
                    </li>
                    @else
                    <li>
                        <a href="{{ route('pointage') }}" class="{{ request()->routeIs('pointage') ? 'active' : '' }}">
                            <i class="fas fa-fingerprint"></i> Ma session
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-history"></i> Historique
                        </a>
                    </li>
                    @endif
                </ul>

                @if(auth()->user()->UTI_Role === 'admin')
                <div class="sidebar-section-title">ADMINISTRATION</div>
                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="fas fa-users"></i> Utilisateurs
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.ips.index') }}" class="{{ request()->routeIs('admin.ips.*') ? 'active' : '' }}">
                            <i class="fas fa-network-wired"></i> Adresses IP
                        </a>
                    </li>

                </ul>
                @endif
            </div>

            <div class="sidebar-footer">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="d-flex align-items-center text-white text-decoration-none p-2 rounded hover-bg-light">
                    <div class="bg-danger rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="fas fa-power-off fa-sm"></i>
                    </div>
                    <div style="font-size: 0.9em;">
                        <div class="fw-bold">Déconnexion</div>
                        <div class="small opacity-50">{{ auth()->user()->UTI_Nom }}</div>
                    </div>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </aside>
        <div class="container pt-5">
            @yield("content")
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>