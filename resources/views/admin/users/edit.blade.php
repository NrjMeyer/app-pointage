@extends('layouts.app')

@section('content')
<div class="card card-modern p-5">
    <div class="card-header">
        <h2>{{ $user->exists ? 'Modifier' : 'Nouvel utilisateur' }}</h2>
    </div>


    <form action="{{ $user->exists ? route('admin.users.update', $user->UTI_ID) : route('admin.users.store') }}" method="POST">
        @csrf
        @if($user->exists)
        @method('PUT')
        @endif

        <div class="row g-4">
            {{-- Informations personnelles --}}
            <div class="col-md-6">
                <label class="form-label fw-bold"><i class="fas fa-user text-muted me-2"></i>Nom Complet</label>
                <input type="text" name="UTI_Nom" class="form-control" value="{{ $user->UTI_Nom }}" required placeholder="Ex: Jean Dupont">
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold"><i class="fas fa-envelope text-muted me-2"></i>Email professionnel</label>
                <input type="email" name="UTI_Email" class="form-control" value="{{ $user->UTI_Email }}" required placeholder="jean@entreprise.com">
            </div>

            {{-- Sécurité --}}
            <div class="col-md-12">
                <label class="form-label fw-bold">
                    <i class="fas fa-key text-muted me-2"></i>Mot de passe
                    @if($user->exists)
                    <small class="text-muted fw-normal fs-7">(Laissez vide pour conserver l'actuel)</small>
                    @endif
                </label>
                <input type="password" name="UTI_Password" class="form-control" placeholder="••••••••">
            </div>

            {{-- Paramètres --}}
            <div class="col-md-6">
                <label class="form-label fw-bold"><i class="fas fa-user-tag text-muted me-2"></i>Rôle Système</label>
                <select name="UTI_Role" class="form-select" required>
                    <option value="employe" {{ $user->UTI_Role=='employe' ? 'selected' : '' }}>Employé</option>
                    <option value="admin" {{ $user->UTI_Role=='admin' ? 'selected' : '' }}>Administrateur</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold"><i class="fas fa-toggle-on text-muted me-2"></i>Statut du compte</label>
                <select name="UTI_Actif" class="form-select" required>
                    <option value="1" {{ ($user->exists && $user->UTI_Actif) ? 'selected' : '' }}>Actif (Autorisé)</option>
                    <option value="0" {{ ($user->exists && !$user->UTI_Actif) ? 'selected' : '' }}>Inactif (Bloqué)</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold"><i class="fas fa-network-wired text-muted me-2"></i>Restriction IP</label>
                <select name="UTI_IP_Restriction" class="form-select" required>
                    <option value="0" {{ (!$user->exists || !$user->UTI_IP_Restriction) ? 'selected' : '' }}>Non (Pas de restriction)</option>
                    <option value="1" {{ ($user->exists && $user->UTI_IP_Restriction) ? 'selected' : '' }}>Oui (Accès bureau uniquement)</option>
                </select>
            </div>
        </div>

        <div class="mt-5 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-2"></i>{{ $user->exists ? 'Enregistrer les modifications' : 'Créer l\'utilisateur' }}
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection