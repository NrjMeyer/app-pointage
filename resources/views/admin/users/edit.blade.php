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

            <div class="mb-3">
                <label>Nom</label>
                <input type="text" name="UTI_Nom" class="form-control" value="{{ $user->UTI_Nom }}" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="UTI_Email" class="form-control" value="{{ $user->UTI_Email }}" required>
            </div>

            <div class="mb-3">
                <label>Mot de passe @if($user->exists)(laisser vide pour ne pas changer)@endif</label>
                <input type="password" name="UTI_Password" class="form-control">
            </div>

            <div class="mb-3">
                <label>Rôle</label>
                <select name="UTI_Role" class="form-control" required>
                    <option value="employe" {{ $user->UTI_Role=='employe' ? 'selected' : '' }}>Employé</option>
                    <option value="admin" {{ $user->UTI_Role=='admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Actif</label>
                <select name="UTI_Actif" class="form-control" required>
                    <option value="1" {{ $user->UTI_Actif ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ !$user->UTI_Actif ? 'selected' : '' }}>Non</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">{{ $user->exists ? 'Mettre à jour' : 'Créer' }}</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Retour</a>
        </form>
    </div>
@endsection
