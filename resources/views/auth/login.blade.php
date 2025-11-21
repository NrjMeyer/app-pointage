<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2b5876, #4e4376);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0,0,0,0.12);
            overflow: hidden;
        }

        .login-header {
            background: #2b5876;
            color: white;
            padding: 25px;
            text-align: center;
        }

        .login-header h3 {
            margin: 0;
            font-weight: 600;
        }

        .form-control {
            height: 48px;
            border-radius: 8px;
        }

        .btn-login {
            background: #2b5876;
            border: none;
            height: 48px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
        }

        .btn-login:hover {
            background: #3c6d91;
        }

        .login-footer {
            text-align: center;
            margin-top: 15px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-card bg-white p-4">
    <div class="login-header">
        <h3>Connexion</h3>
        <p class="mb-0">Accès à la pointeuse</p>
    </div>
    <form method="POST" action="{{ route('login.post') }}">
        @csrf

        <div class="mb-3">
            <label>Email</label>
            <input type="text" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label>Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <button class="btn btn-primary w-100 btn-login">Se connecter</button>
    </form>
    <div class="login-footer">
        &copy; {{ date('Y') }} Pointeuse – Tous droits réservés
    </div>
</div>

</body>
</html>
