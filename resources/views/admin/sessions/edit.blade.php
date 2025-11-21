@extends('layouts.app')

@section('content')
    <div class="card card-modern p-5">
        <div class="card-header">
            <h2>{{ $session->exists ? 'Modifier' : 'Nouvelle' }} Session</h2>
        </div>


        <form action="{{ $session->exists ? route('admin.sessions.update', $session->WRK_ID) : route('admin.sessions.store') }}" method="POST">
            @csrf
            @if($session->exists)
                @method('PUT')
            @endif

            <div class="mb-3">
                <label>Utilisateur</label>
                <select name="WRK_UTI_ID" class="form-control" required>
                    <option value="">Sélectionner</option>
                    @foreach($users as $u)
                        <option value="{{ $u->UTI_ID }}" {{ $session->WRK_UTI_ID == $u->UTI_ID ? 'selected' : '' }}>
                            {{ $u->UTI_Nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Date / Heure Début</label>
                <input type="datetime-local" name="WRK_Dte_Heure_Deb" class="form-control" value="{{ $session->WRK_Dte_Heure_Deb ? \Carbon\Carbon::parse($session->WRK_Dte_Heure_Deb)->format('Y-m-d\TH:i') : '' }}" required>
            </div>

            <div class="mb-3">
                <label>Date / Heure Fin</label>
                <input type="datetime-local" name="WRK_Dte_Heure_Fin" class="form-control" value="{{ $session->WRK_Dte_Heure_Fin ? \Carbon\Carbon::parse($session->WRK_Dte_Heure_Fin)->format('Y-m-d\TH:i') : '' }}">
            </div>

            <div class="mb-3">
                <label>Note</label>
                <input type="text" name="WRK_Note" class="form-control" value="{{ $session->WRK_Note }}">
            </div>

            <button type="submit" class="btn btn-success">{{ $session->exists ? 'Mettre à jour' : 'Créer' }}</button>
            <a href="{{ route('admin.sessions.index') }}" class="btn btn-secondary">Retour</a>
        </form>
    </div>
@endsection
