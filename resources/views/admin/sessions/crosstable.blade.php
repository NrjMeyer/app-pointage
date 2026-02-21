@extends('layouts.app')

@section('content')
	<div class="card card-modern p-5">
		<div class="card-header d-flex justify-content-between align-items-center mb-4 bg-transparent border-0 p-0">
			<h2 class="mb-0">Tableau Croisé des Sessions</h2>
			<form method="GET" action="" class="d-flex align-items-center gap-2">
				<label class="small fw-bold text-muted mb-0">Mois :</label>
				<input type="month" name="month" class="form-control form-control-sm"
					   value="{{ $selectedMonth }}" max="{{\Carbon\Carbon::now()->format('Y-m')}}" required style="width: 160px;">
				<button class="btn btn-sm btn-primary px-3">
					<i class="fas fa-filter me-1"></i>Filtrer
				</button>
			</form>
		</div>

		<div class="table-responsive">
			<table class="table table-marked table-hover align-middle">
				<thead class="table-light">
				<tr>
					<th style="min-width: 120px;">Jour</th>
					@foreach ($utilisateurs as $u)
						@php
							$totalMin = $totauxMois[$u->UTI_ID] ?? 0;
							$hours = floor($totalMin / 60);
							$minutes = $totalMin % 60;
						@endphp
						<th class="text-center p-3">
							<div class="fw-bold">{{ $u->UTI_Nom }}</div>
							<div class="small text-primary fw-normal mt-1">{{ $hours }}h{{ str_pad($minutes, 2, '0', STR_PAD_LEFT) }}</div>
						</th>
					@endforeach
				</tr>
				</thead>

				<tbody>
				@foreach ($jours as $j)
					@php
						$date = \Carbon\Carbon::parse($j)->locale('fr');
						$isWeekend = $date->isWeekend();
					@endphp
					<tr class="{{ $isWeekend ? 'bg-weekend' : '' }}">
						<td class="p-3 {{ $isWeekend ? 'bg-weekend' : '' }}">
							<div class="fw-bold">{{ $date->format('d/m/Y') }}</div>
							<div class="small text-muted">{{ ucfirst($date->translatedFormat('l')) }}</div>
						</td>

						@foreach ($utilisateurs as $u)
							@php
								$min = $pivot[$j][$u->UTI_ID];
								$txt = $min ? sprintf('%dh %02dmin', intdiv($min,60), $min%60) : '';
							@endphp

							<td class="text-center p-3 {{ $isWeekend ? 'bg-weekend' : '' }}">
								@if($min)
									<a href="{{ route('admin.sessions.index') }}?user={{ $u->UTI_ID }}&date={{ $j }}" 
									   class="text-decoration-none fw-bold text-success">
										{{ $txt }}
									</a>
								@else
									<span class="text-muted opacity-25">-</span>
								@endif
							</td>
						@endforeach
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection
