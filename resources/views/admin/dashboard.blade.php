@extends('layouts.app')

@section('content')
    <div class="row">

        <div class="col-md-4">
            <div class="card card-modern mb-4">
                <div class="card-header">Sessions ouverts aujourd'hui</div>
                <div class="card-body text-center">
                    <h2>{{ $activeSessions }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-modern mb-4">
                <div class="card-header">Sessions du jour</div>
                <div class="card-body text-center">
                    <h2>{{ $sessionsToday }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-modern mb-4">
                <div class="card-header">Utilisateurs</div>
                <div class="card-body text-center">
                    <h2>{{ $totalUsers }}</h2>
                </div>
            </div>
        </div>
    </div>
@endsection
