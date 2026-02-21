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

        <table class="table table-marked table-hover align-middle">
            <thead class="table-light">
            <tr>
                <th>Utilisateur</th>
                <th>Période & Statut</th>
                <th>Durée</th>
                <th>Note</th>
                <th class="text-center">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($sessions as $s)
                @php
                    $isWeekend = \Carbon\Carbon::parse($s->WRK_Dte_Heure_Deb)->isWeekend();
                @endphp
                <tr class="{{ $isWeekend ? 'bg-weekend' : '' }}">
                    <td class="{{ $isWeekend ? 'bg-weekend' : '' }}">
                        <div class="fw-bold">{{ $s->utilisateur->UTI_Nom }}</div>
                        <div class="small text-muted">{{ $s->utilisateur->UTI_Email }}</div>
                    </td>
                    <td class="{{ $isWeekend ? 'bg-weekend' : '' }}">
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
                        
                        @if($s->WRK_IP_Debut || $s->WRK_IP_Fin)
                            @php
                                $ipColor = 'text-muted';
                                if ($s->WRK_IP_Fin) {
                                    $ipColor = ($s->WRK_IP_Debut === $s->WRK_IP_Fin) ? 'text-success' : 'text-danger';
                                }
                            @endphp
                            <i class="fas fa-info-circle {{ $ipColor }} ms-2" title="IP Début: {{ $s->WRK_IP_Debut ?? '-' }} &#10;IP Fin: {{ $s->WRK_IP_Fin ?? '-' }}"></i>
                        @endif
                    </td>
					<td class="{{ $isWeekend ? 'bg-weekend' : '' }}">
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
                    <td class="{{ $isWeekend ? 'bg-weekend' : '' }}">
                        <div class="text-truncate" style="max-width: 150px;" title="{{ $s->WRK_Note }}">
                            {{ $s->WRK_Note ?: '-' }}
                        </div>
                    </td>
                    <td class="text-center {{ $isWeekend ? 'bg-weekend' : '' }}">
                        <div class="d-flex justify-content-center gap-1">
                            @if(!$s->WRK_Dte_Heure_Fin)
                                <form action="{{ route('admin.sessions.close', $s->WRK_ID) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-info border-0" title="Clôturer la session">
                                        <i class="fas fa-lock fs-5"></i>
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ route('admin.sessions.edit', $s->WRK_ID) }}" class="btn btn-sm btn-outline-success border-0" title="Modifier">
                                <i class="fas fa-pencil-alt fs-5"></i>
                            </a>

                            <form action="{{ route('admin.sessions.destroy', $s->WRK_ID) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette session ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger border-0" title="Supprimer">
                                    <i class="fas fa-trash-alt fs-5"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
