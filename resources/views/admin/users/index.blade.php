@extends('layouts.app')

@section('content')
<div class="card card-modern p-5">
    <div class="card-header">
        Gestion des utilisateurs
    </div>

    <a href="{{ route('admin.users.create') }}" class="btn btn-primary mb-3 col-3">Nouveau utilisateur</a>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-marked table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Utilisateurs</th>
                <th>Rôle</th>
                <th>Actif</th>
                <th>Restriction IP</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr>
                <td>
                    <div class="fw-bold">{{ $u->UTI_Nom }}</div>
                    <div class="small text-muted">{{ $u->UTI_Email }}</div>
                </td>
                <td>
                    @if($u->UTI_Role === 'admin')
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Administrateur</span>
                    @else
                    <span class="badge bg-light text-muted border">Utilisateur</span>
                    @endif
                </td>
                <td>
                    @if($u->UTI_Actif)
                    <span class="badge bg-success-subtle text-success border border-success-subtle">Oui</span>
                    @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">Non</span>
                    @endif
                </td>
                <td>
                    @if($u->UTI_IP_Restriction)
                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Oui</span>
                    @else
                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Non</span>
                    @endif
                </td>
                <td class="text-center">
                    <div class="d-flex justify-content-center gap-1">
                        <a href="{{ route('admin.users.edit', $u->UTI_ID) }}" class="btn btn-sm btn-outline-success border-0" title="Modifier">
                            <i class="fas fa-pencil-alt fs-5"></i>
                        </a>

                        @if(auth()->id() !== $u->UTI_ID)
                        <form action="{{ route('admin.users.destroy', $u->UTI_ID) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger border-0" title="Supprimer">
                                <i class="fas fa-trash-alt fs-5"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection