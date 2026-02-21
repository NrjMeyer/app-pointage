@extends('layouts.app')

@section('content')
<div class="card card-modern p-5">
    <div class="card-header bg-transparent border-0 p-0 mb-5">
        <h2 class="mb-0 fw-bold text-dark">{{ $session->exists ? 'Modifier la Session' : 'Nouvelle Session de Travail' }}</h2>
        <p class="text-muted small">Ajustez les horaires et les détails</p>
    </div>

    <form action="{{ $session->exists ? route('admin.sessions.update', $session->WRK_ID) : route('admin.sessions.store') }}" method="POST">
        @csrf
        @if($session->exists)
        @method('PUT')
        @endif

        <div class="row g-4">
            {{-- Utilisateur --}}
            <div class="col-md-12">
                <label class="form-label fw-bold"><i class="fas fa-user text-muted me-2"></i>Collaborateur</label>
                <select name="WRK_UTI_ID" class="form-select" required>
                    <option value="">Sélectionner un utilisateur...</option>
                    @foreach($users as $u)
                    <option value="{{ $u->UTI_ID }}" {{ $session->WRK_UTI_ID == $u->UTI_ID ? 'selected' : '' }}>
                        {{ $u->UTI_Nom }} ({{ $u->UTI_Email }})
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Horaires --}}
            <div class="col-md-6">
                <label class="form-label fw-bold"><i class="fas fa-clock text-success me-2"></i>Début de service</label>
                <input type="datetime-local" name="WRK_Dte_Heure_Deb" class="form-control"
                    value="{{ $session->WRK_Dte_Heure_Deb ? \Carbon\Carbon::parse($session->WRK_Dte_Heure_Deb)->format('Y-m-d\TH:i') : '' }}" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold"><i class="fas fa-history text-danger me-2"></i>Fin de service</label>
                <input type="datetime-local" name="WRK_Dte_Heure_Fin" class="form-control"
                    value="{{ $session->WRK_Dte_Heure_Fin ? \Carbon\Carbon::parse($session->WRK_Dte_Heure_Fin)->format('Y-m-d\TH:i') : '' }}"
                    @if($session->WRK_Dte_Heure_Deb) max="{{\Carbon\Carbon::parse($session->WRK_Dte_Heure_Deb)->format('Y-m-d')}}T22:00" @endif>
                <div class="form-text small">Laissez vide si la session est toujours en cours.</div>
            </div>

            {{-- Commentaires --}}
            <div class="col-md-12">
                <label class="form-label fw-bold"><i class="fas fa-sticky-note text-muted me-2"></i>Observations / Notes</label>
                <textarea name="WRK_Note" class="form-control" rows="3" placeholder="Détails de la mission, motif de modification... ">{{ $session->WRK_Note }}</textarea>
            </div>
        </div>

        <div class="mt-5 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-2"></i>{{ $session->exists ? 'Enregistrer les modifications' : 'Créer la session' }}
            </button>
            <a href="{{ route('admin.sessions.index') }}" class="btn btn-outline-secondary px-4">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection