@extends('layouts.app')

@section('content')
    <div class="card card-modern p-5">
        <div class="card-header">
            <h2>Gestion des Sessions</h2>
        </div>

        <a href="{{ route('admin.sessions.create') }}" class="btn btn-primary mb-3 col-md-3">Nouvelle session</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Utilisateur</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Durée (min)</th>
                <th>Type clôture</th>
                <th>Note</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sessions as $s)
                <tr>
                    <td>{{ $s->WRK_ID }}</td>
                    <td>{{ $s->utilisateur->UTI_Nom }}</td>
                    <td>{{ $s->WRK_Dte_Heure_Deb }}</td>
                    <td>{{ $s->WRK_Dte_Heure_Fin ?? '-' }}</td>
                    <td>{{ $s->WRK_Duree_Minutes ?? '-' }}</td>
                    <td>{{ $s->WRK_Type_Cloture ?? '-' }}</td>
                    <td>{{ $s->WRK_Note }}</td>
                    <td>
                        <a href="{{ route('admin.sessions.edit', $s->WRK_ID) }}" class="btn btn-sm btn-warning">Modifier</a>

                        @if(!$s->WRK_Dte_Heure_Fin)
                            <form action="{{ route('admin.sessions.close', $s->WRK_ID) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-info">Clôturer</button>
                            </form>
                        @endif

                        <form action="{{ route('admin.sessions.destroy', $s->WRK_ID) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette session ?')">
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
