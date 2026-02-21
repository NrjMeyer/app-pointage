@extends('layouts.app')

@section('content')
<div class="card card-modern p-5">
    <div class="card-header">
        <h2>{{ $ip->exists ? 'Modifier l\'adresse IP' : 'Nouvelle adresse IP' }}</h2>
    </div>

    <form action="{{ $ip->exists ? route('admin.ips.update', $ip->AIP_ID) : route('admin.ips.store') }}" method="POST">
        @csrf
        @if($ip->exists)
        @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-md-12">
                <label class="form-label fw-bold"><i class="fas fa-network-wired text-muted me-2"></i>Adresse IP</label>
                <input type="text" name="AIP_IP" class="form-control" value="{{ old('AIP_IP', $ip->AIP_IP) }}" required placeholder="Ex: 192.168.1.1">
                @error('AIP_IP')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mt-5 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-2"></i>{{ $ip->exists ? 'Enregistrer' : 'Créer' }}
            </button>
            <a href="{{ route('admin.ips.index') }}" class="btn btn-outline-secondary px-4">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection