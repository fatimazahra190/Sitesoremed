@extends('layouts.admin')
@section('admin-title', 'Edit User')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Modifier l'utilisateur : {{ $user->name }} {{ $user->surname }}</h2>
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
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Prénom *</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required 
                                   maxlength="255">
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
                                   value="{{ old('surname', $user->surname) }}" 
                                   required 
                                   maxlength="255">
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
                           value="{{ old('email', $user->email) }}" 
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                                            {{ in_array($role->id, old('roles', $userRoles)) ? 'selected' : '' }}>
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
                            <small class="form-text text-muted">Maintenez Ctrl pour sélectionner plusieurs rôles</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="groups" class="form-label">Groupes</label>
                            <select class="form-select @error('groups') is-invalid @enderror" 
                                    id="groups" 
                                    name="groups[]" 
                                    multiple 
                                    size="6">
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}" 
                                            {{ in_array($group->id, old('groups', $userGroups)) ? 'selected' : '' }}>
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
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactif</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note :</strong> Les modifications seront enregistrées immédiatement. Les changements de rôles et groupes prendront effet immédiatement.
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Mettre à jour
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 