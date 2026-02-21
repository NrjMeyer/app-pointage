@extends('layouts.app')

@section('content')
    <div class="card card-modern p-5">

        {{--@if(session('success'))
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
        @endif--}}


        <h4 class="mb-3">Vos sessions des 7 derniers jours</h4>

        @if($sessions7Jours->isEmpty())
            <p>Aucune session récente.</p>
        @else
        <table class="table table-marked table-hover align-middle">
            <thead class="table-light">
            <tr>
                <th>Période & Statut</th>
                <th>Durée</th>
                <th>Note</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sessions7Jours as $s)
                <tr @if($s->WRK_Est_Cloture_Auto) class="table-warning-subtle" @endif>
                    <td>
                        <div class="mb-1">
                            <span class="fw-bold">{{ \Carbon\Carbon::parse($s->WRK_Dte_Heure_Deb)->format('d/m/Y') }}</span>
                            <span class="text-muted mx-1">|</span>
                            <span>{{ \Carbon\Carbon::parse($s->WRK_Dte_Heure_Deb)->format('H:i') }}</span>
                            <i class="fas fa-arrow-right mx-1 small text-muted"></i>
                            <span>{{ $s->WRK_Dte_Heure_Fin ? \Carbon\Carbon::parse($s->WRK_Dte_Heure_Fin)->format('H:i') : '...' }}</span>
                        </div>
                        @if(!$s->WRK_Dte_Heure_Fin)
                            <span class="badge bg-success-subtle text-success border border-success-subtle">En cours</span>
                        @else
                            <span class="badge bg-light text-muted border">Terminée</span>
                        @endif
                        
                        @if($s->WRK_Est_Cloture_Auto)
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle ms-1" title="Clôturée automatiquement">Auto</span>
                        @endif
                    </td>
					<td>
                        @if($s->WRK_Duree_Minutes)
                            @php
                                $minutes = $s->WRK_Duree_Minutes;
                                $heures = floor($minutes / 60);
                                $mins = $minutes % 60;
                            @endphp
                            <div class="fw-bold">{{ sprintf('%02dh %02dmin', $heures, $mins) }}</div>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="text-truncate" style="max-width: 250px;" title="{{ $s->WRK_Note }}">
                            {{ $s->WRK_Note ?: '-' }}
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @endif

    </div>
@endsection
