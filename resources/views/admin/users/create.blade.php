@extends('layouts.admin')
@section('admin-title', 'Add User')
@section('content')
@can('super admin')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Créer un nouvel utilisateur</h2>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Prénom *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   maxlength="255"
                                   placeholder="Prénom de l'utilisateur">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="surname" class="form-label">Nom *</label>
                            <input type="text" 
                                   class="form-control @error('surname') is-invalid @enderror" 
                                   id="surname" 
                                   name="surname" 
                                   value="{{ old('surname') }}" 
                                   required 
                                   maxlength="255"
                                   placeholder="Nom de famille">
                            @error('surname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email *</label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required
                           placeholder="email@exemple.com">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">L'utilisateur recevra un email d'invitation avec son mot de passe temporaire</small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="roles" class="form-label">Rôles *</label>
                            <select class="form-select @error('roles') is-invalid @enderror" 
                                    id="roles" 
                                    name="roles[]" 
                                    multiple 
                                    required 
                                    size="6">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" 
                                            {{ in_array($role->id, old('roles', [])) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                        @if($role->description)
                                            - {{ $role->description }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('roles')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs rôles</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="groups" class="form-label">Groupes (optionnel)</label>
                            <select class="form-select @error('groups') is-invalid @enderror" 
                                    id="groups" 
                                    name="groups[]" 
                                    multiple 
                                    size="6">
                                @foreach($groups ?? [] as $group)
                                    <option value="{{ $group->id }}" 
                                            {{ in_array($group->id, old('groups', [])) ? 'selected' : '' }}>
                                        {{ $group->name }}
                                        @if($group->description)
                                            - {{ $group->description }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('groups')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Maintenez Ctrl pour sélectionner plusieurs groupes</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Statut *</label>
                    <select class="form-select @error('status') is-invalid @enderror" 
                            id="status" 
                            name="status" 
                            required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note :</strong> 
                    <ul class="mb-0 mt-2">
                        <li>Un mot de passe temporaire sera généré automatiquement</li>
                        <li>Un email d'invitation sera envoyé à l'utilisateur</li>
                        <li>L'utilisateur devra changer son mot de passe lors de sa première connexion</li>
                    </ul>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Créer l'utilisateur
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialiser les select multiples
    $('#roles').select2({
        placeholder: 'Sélectionnez les rôles',
        allowClear: true
    });
    $('#groups').select2({
        placeholder: 'Sélectionnez les groupes',
        allowClear: true
    });
});
</script>
@endpush 