@extends('layouts.app')

@section('content')
    <div class="card card-modern p-5">
        <div class="card-header">
            Gestion des utilisateurs
        </div>

        <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3">Nouvel utilisateur</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Actif</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $u)
                <tr>
                    <td>{{ $u->UTI_ID }}</td>
                    <td>{{ $u->UTI_Nom }}</td>
                    <td>{{ $u->UTI_Email }}</td>
                    <td>{{ ucfirst($u->UTI_Role) }}</td>
                    <td>{{ $u->UTI_Actif ? 'Oui' : 'Non' }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $u->UTI_ID) }}" class="btn btn-sm btn-warning">Modifier</a>

                        <form action="{{ route('admin.users.destroy', $u->UTI_ID) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
