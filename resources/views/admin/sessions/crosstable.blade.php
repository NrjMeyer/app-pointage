@extends('layouts.app')

@section('content')
    <div class="card card-modern p-5">
        <div class="card-header">
            <h2>Tableau croisé - Sessions ({{ count($dates) }} derniers jours)</h2>
        </div>

        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th>Employé</th>
                @foreach($dates as $date)
                    <th>{{ \Carbon\Carbon::parse($date)->format('d/m') }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->UTI_Nom }}</td>
                    @foreach($dates as $date)
                        @php
                            $daySessions = $grid[$user->UTI_ID][$date];
                            $totalMinutes = $daySessions->sum('WRK_Duree_Minutes');
                            $hasAuto = $daySessions->contains('WRK_Type_Cloture', 'auto');
                        @endphp
                        <td>
                            <a href="{{ route('admin.sessions.index') }}?user={{ $user->UTI_ID }}&date={{ $date }}">
                                {{ $totalMinutes }} min
                                @if($hasAuto)
                                    <span class="badge bg-warning">Auto</span>
                                @endif
                            </a>
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
