@extends('layouts.app')

@section('content')
<div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 80vh;">

	{{-- Alerts --}}
	@if(session('success'))
	<div class="alert alert-success border-0 shadow-sm mb-4 rounded-pill px-5">
		<i class="fas fa-check-circle me-2"></i> {{ session('success') }}
	</div>
	@endif

	@if(session('error'))
	<div class="alert alert-danger border-0 shadow-sm mb-4 rounded-pill px-5">
		<i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
	</div>
	@endif

	@php
	$now = \Carbon\Carbon::now('Europe/Paris')->format('H:i');
	$ouverture = env('POINTEUSE_HEURE_OUVERTURE', '05:00');
	$cloture = env('POINTEUSE_HEURE_CLOTURE', '22:00');
	$horsHoraires = !($now >= $ouverture && $now < $cloture);
		@endphp

		<div class="card card-modern border-0 shadow-lg" style="width: 100%; max-width: 500px; border-radius: 20px; overflow: hidden;">

		{{-- Header Status --}}
		<div class="card-header border-0 text-center py-4 {{ $sessionActive ? 'bg-primary text-white' : 'bg-white' }}">
			@if($sessionActive)
			<h4 class="mb-0 fw-bold text-white"><i class="fas fa-clock fa-spin me-2"></i> Session en cours</h4>
			<div class="mt-2 opacity-75 small">Commencé à {{ \Carbon\Carbon::parse($sessionActive->WRK_Dte_Heure_Deb)->format('H:i') }}</div>
			@else
			<h4 class="mb-0 fw-bold text-dark">Bonjour, {{ Auth::user()->UTI_Nom }}</h4>
			@endif
		</div>

		<div class="card-body p-5 text-center bg-white">

			@if($horsHoraires)
			<div class="py-4">
				<div class="mb-3 text-warning">
					<i class="fas fa-moon fa-4x"></i>
				</div>
				<h5 class="fw-bold text-muted">Hors horaires</h5>
				<p class="small text-muted mb-0">
					Fermé entre <strong>{{ $cloture }}</strong> et <strong>{{ $ouverture }}</strong>.
				</p>
			</div>
			@else
			@if($sessionActive)
			{{-- Timer Display --}}
			@php
			$duree = intval(\Carbon\Carbon::parse($sessionActive->WRK_Dte_Heure_Deb, 'Europe/Paris')->diffInMinutes(\Carbon\Carbon::now('Europe/Paris')));
			$heures = floor($duree / 60);
			$mins = $duree % 60;
			@endphp

			<div class="mb-5">
				<div class="display-3 fw-bold text-primary">{{ sprintf('%02d:%02d', $heures, $mins) }}</div>
				<div class="text-muted small text-uppercase spacing-2">Durée Actuelle</div>
			</div>

			{{-- Formulaire de clôture --}}
			<form method="POST" action="{{ route('session.close') }}">
				@csrf

				@if($sessionActive->WRK_Est_Cloture_Auto)
				<div class="alert alert-warning small mb-3">
					<i class="fas fa-exclamation-triangle me-1"></i> Session clôturée automatiquement hier.
				</div>
				@endif

				<div class="mb-4 text-start">
					<label for="note" class="form-label small fw-bold text-muted text-uppercase">Note (Optionnel)</label>
					<textarea name="WRK_Note" id="note" class="form-control bg-light border-0" rows="2" placeholder="..."></textarea>
				</div>

				<button class="btn btn-danger btn-lg w-100 rounded-pill py-3 fw-bold shadow-sm hover-scale">
					<i class="fas fa-stop-circle me-2"></i> TERMINER LA SESSION
				</button>
			</form>

			@else
			{{-- Bouton Start --}}
			<div class="py-4">
				<form method="POST" action="{{ route('session.start') }}">
					@csrf
					<button class="btn btn-primary rounded-circle shadow-lg hover-scale d-flex align-items-center justify-content-center mx-auto mb-4"
						style="width: 120px; height: 120px; border: 8px solid #eff2fc;">
						<i class="fas fa-play fa-3x ms-1"></i>
					</button>
					<div class="text-muted fw-bold">CLIQUER POUR DÉMARRER</div>
				</form>
			</div>
			@endif
			@endif

		</div>

		<div class="card-footer bg-light border-0 text-center py-3">
			<a href="{{ route('dashboard') }}" class="text-decoration-none text-muted small fw-bold">
				<i class="fas fa-arrow-left me-1"></i> Retour au Tableau de Bord
			</a>
		</div>
</div>
</div>

@endsection