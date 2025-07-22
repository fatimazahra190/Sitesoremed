@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Assigner des rôles à {{ $user->full_name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Informations utilisateur</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Nom :</strong></td>
                                    <td>{{ $user->full_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email :</strong></td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Statut :</strong></td>
                                    <td>
                                        @if($user->status === 'active')
                                            <span class="badge badge-success">Actif</span>
                                        @else
                                            <span class="badge badge-warning">Inactif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Rôles actuels :</strong></td>
                                    <td>
                                        @if($user->roles->count() > 0)
                                            @foreach($user->roles as $role)
                                                <span class="badge badge-primary">{{ $role->name }}</span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">Aucun rôle assigné</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <form action="{{ route('admin.users.assign-roles', $user) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="roles">Sélectionner les rôles *</label>
                            <select class="form-control @error('roles') is-invalid @enderror" 
                                    id="roles" name="roles[]" multiple required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" 
                                            {{ in_array($role->id, $userRoles) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                        @if($role->permissions->count() > 0)
                                            ({{ $role->permissions->count() }} permissions)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('roles')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs rôles
                            </small>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note :</strong> Les permissions de l'utilisateur seront automatiquement mises à jour en fonction des rôles sélectionnés.
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Assigner les rôles
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialiser les select multiples
    $('#roles').select2({
        placeholder: 'Sélectionnez les rôles',
        allowClear: true
    });
});
</script>
@endpush 