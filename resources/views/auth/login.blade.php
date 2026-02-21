<!DOCTYPE html>
<html lang="fr text-dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | Gestion des sessions</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">

    <!-- Icons & CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @vite(['resources/css/custom.css', 'resources/js/app.js'])
</head>

<body>

    <div class="login-wrapper">
        <!-- Section Illustration -->
        <div class="col-lg-7 login-illustration">
            <img src="{{asset('/images/fond.png')}}" alt="Gestion des sessions">
        </div>

        <!-- Section Formulaire -->
        <div class="col-lg-5 col-12 login-container shadow-lg">
            <div class="login-card">
                <div class="login-header">
                    <h2>Ravi de vous revoir</h2>
                    <p>Veuillez vous connecter pour accéder à votre espace.</p>
                </div>

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label">Email Professionnel</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" placeholder="nom@entreprise.fr" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Mot de passe</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>

                    @if ($errors->any())
                    <div class="alert alert-danger border-0 small mb-4 shadow-sm" style="background-color: #fff5f5; color: #e53e3e;">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ $errors->first() }}
                    </div>
                    @endif

                    <button type="submit" class="btn btn-primary btn-login w-100 shadow-sm">
                        Se connecter <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </form>

                <div class="login-footer">
                    <p class="mb-0">&copy; {{ date('Y') }} – Gestion des sessions</p>
                    <p class="small opacity-50">Gestion simplifiée du temps de travail</p>
                </div>
            </div>
        </div>
    </div>

</body>

</html>