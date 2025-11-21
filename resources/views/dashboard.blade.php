@extends('layouts.app')

@section('content')
    <div class="card card-modern p-5">
        <div class="card-header">
            Tableau de bord
        </div>
        <div class="d-flex justify-content-end mb-3">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-secondary">
                    Se déconnecter
                </button>
            </form>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @php
            $now = \Carbon\Carbon::now('Europe/Paris')->format('H:i');
            $ouverture = env('POINTEUSE_HEURE_OUVERTURE', '05:00');
            $cloture   = env('POINTEUSE_HEURE_CLOTURE', '22:00');

            $horsHoraires = !($now >= $ouverture && $now < $cloture);
        @endphp

        @if($horsHoraires)
            <div class="alert alert-warning">
                L’ouverture d’une session n’est pas permise entre
                <strong>{{ $cloture }}</strong> et <strong>{{ $ouverture }}</strong>.
            </div>
        @endif

        @if($sessionActive)
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    Session en cours
                </div>
                <div class="card-body">

                    <p><strong>Début :</strong> {{ $sessionActive->WRK_Dte_Heure_Deb }}</p>

                    @if($sessionActive->WRK_Est_Cloture_Auto)
                        <p class="text-danger">
                            ⚠️ Cette session a été clôturée automatiquement.
                        </p>
                    @endif

                    <form method="POST" action="{{ route('session.close') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="note" class="form-label">Ajouter une note (optionnel)</label>
                            <textarea name="WRK_Note" id="note" class="form-control" rows="2"></textarea>
                        </div>

                        <button class="btn btn-danger w-100">
                            Clôturer la session
                        </button>
                    </form>
                </div>
            </div>


        @else
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    Aucune session active
                </div>
                <div class="card-body text-center">

                    @if(!$horsHoraires)
                        <form method="POST" action="{{ route('session.start') }}">
                            @csrf
                            <button class="btn btn-primary btn-lg w-100">
                                Démarrer une nouvelle session
                            </button>
                        </form>
                    @else
                        <div class="alert alert-info mt-3">
                            Vous ne pouvez pas démarrer une session maintenant.
                        </div>
                    @endif
                </div>
            </div>
        @endif


        <h4 class="mb-3">Vos sessions des 7 derniers jours</h4>

        @if($sessions7Jours->isEmpty())
            <p>Aucune session récente.</p>
        @else
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Début</th>
                    <th>Fin</th>
                    <th>Durée (min)</th>
                    <th>Clôture</th>
                    <th>Note</th>
                </tr>
                </thead>
                <tbody>

                @foreach($sessions7Jours as $s)
                    <tr @if($s->WRK_Est_Cloture_Auto) class="table-warning" @endif>
                        <td>{{ \Carbon\Carbon::parse($s->WRK_Dte_Heure_Deb)->format('d/m/Y') }}</td>
                        <td>{{ $s->WRK_Dte_Heure_Deb }}</td>
                        <td>{{ $s->WRK_Dte_Heure_Fin ?? '—' }}</td>
                        <td>{{ $s->WRK_Duree_Minutes ?? '—' }}</td>
                        <td>
                            @if($s->WRK_Est_Cloture_Auto)
                                Automatique
                            @else
                                Manuel
                            @endif
                        </td>
                        <td>{{ $s->WRK_Note }}</td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        @endif

    </div>
@endsection
