@extends('layouts.app')

@section('content')
    <div class="row g-4 mb-4">
        {{-- Stat Cards --}}
        <div class="col-md-3">
            <div class="card card-modern border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary-subtle p-3 rounded-circle me-3">
                            <i class="fas fa-play text-primary fs-4"></i>
                        </div>
                        <div>
                            <div class="small text-muted fw-bold">EN COURS</div>
                            <h2 class="mb-0">{{ $activeSessions }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-modern border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-success-subtle p-3 rounded-circle me-3">
                            <i class="fas fa-calendar-check text-success fs-4"></i>
                        </div>
                        <div>
                            <div class="small text-muted fw-bold">SESSIONS JOUR</div>
                            <h2 class="mb-0">{{ $sessionsToday }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-modern border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-info-subtle p-3 rounded-circle me-3">
                            <i class="fas fa-clock text-info fs-4"></i>
                        </div>
                        <div>
                            <div class="small text-muted fw-bold">HEURES MOIS</div>
                            <h2 class="mb-0">{{ $totalHoursMonth }}h</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-modern border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning-subtle p-3 rounded-circle me-3">
                            <i class="fas fa-users text-warning fs-4"></i>
                        </div>
                        <div>
                            <div class="small text-muted fw-bold">SALARIÉS</div>
                            <h2 class="mb-0">{{ $totalUsers }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- Graphique --}}
        <div class="col-md-8">
            <div class="card card-modern h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h5 class="card-title fw-bold">Cumul d'heures (6 derniers mois)</h5>
                    <p class="text-muted small">Totalité des salariés confondus</p>
                </div>
                <div class="card-body">
                    <canvas id="hoursChart" style="max-height: 350px;"></canvas>
                </div>
            </div>
        </div>

        {{-- Top Jours --}}
        <div class="col-md-4">
            <div class="card card-modern h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h5 class="card-title fw-bold">Top 3 jours les + travaillés</h5>
                    <p class="text-muted small">Sur le mois en cours</p>
                </div>
                <div class="card-body pt-2">
                    <ul class="list-group list-group-flush">
                        @forelse($topDays as $index => $td)
                            @php
                                $dateObj = \Carbon\Carbon::parse($td->date)->locale('fr');
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-subtle rounded-circle p-2 me-3 text-primary d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                                        <i class="far fa-calendar-alt"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $dateObj->format('d/m/Y') }}</div>
                                        <div class="small text-muted">{{ ucfirst($dateObj->translatedFormat('l')) }}</div>
                                    </div>
                                </div>
                                <span class="badge bg-primary rounded-pill">
                                    {{ round($td->total_min / 60, 1) }}h
                                </span>
                            </li>
                        @empty
                            <p class="text-muted small">Aucune donnée ce mois-ci.</p>
                        @endforelse
                    </ul>
                </div>
                <div class="card-footer bg-transparent border-0 text-center pb-4">
                    <a href="{{ route('admin.sessions.crosstable') }}" class="btn btn-sm btn-outline-primary shadow-sm rounded-pill px-4">
                        Consulter le tableau complet
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js Script --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('hoursChart').getContext('2d');
            
            // Création d'un dégradé pour le graphique
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(75, 111, 232, 0.4)');
            gradient.addColorStop(1, 'rgba(75, 111, 232, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($months),
                    datasets: [{
                        label: 'Heures cumulées',
                        data: @json($hoursData),
                        borderColor: '#4b6fe8',
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#4b6fe8',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#fff',
                            titleColor: '#1c2340',
                            bodyColor: '#1c2340',
                            borderColor: '#e3e8f2',
                            borderWidth: 1,
                            padding: 10,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' heures';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f0f3f9'
                            },
                            ticks: {
                                color: '#7b8194',
                                callback: function(value) {
                                    return value + 'h';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#7b8194'
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
