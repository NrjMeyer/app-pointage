@extends('layouts.app')

@section('content')
<div class="card card-modern p-5">
    <div class="card-header">
        Gestion des Adresses IP
    </div>

    <div class="d-flex gap-2 mb-3">
        <form action="{{ route('admin.ips.store_current') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-magic me-1"></i> Ajouter mon IP
            </button>
        </form>
        <a href="{{ route('admin.ips.create') }}" class="btn btn-primary">Nouvelle Adresse IP</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-marked table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Adresse IP</th>
                <th>Date de création</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ips as $ip)
            <tr>
                <td>
                    <div class="fw-bold">{{ $ip->AIP_IP }}</div>
                </td>
                <td>
                    {{ $ip->created_at->format('d/m/Y H:i') }}
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('admin.ips.edit', $ip->AIP_ID) }}" class="btn btn-sm btn-outline-success border-0" title="Modifier">
                            <i class="fas fa-pencil-alt fs-5"></i>
                        </a>

                        <form action="{{ route('admin.ips.destroy', $ip->AIP_ID) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression de cette adresse IP ?')">
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