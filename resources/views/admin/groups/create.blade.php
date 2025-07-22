@extends('layouts.admin')
@section('admin-title', 'Créer un groupe')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Créer un nouveau groupe</h2>
        <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary">Retour à la liste</a>
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
            <form action="{{ route('admin.groups.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="name" class="form-label">Nom du groupe *</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           maxlength="255"
                           placeholder="Ex: Équipe Marketing">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Nom unique pour identifier le groupe</small>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              name="description" 
                              rows="3" 
                              maxlength="255"
                              placeholder="Description du groupe et de ses responsabilités">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Description optionnelle du groupe</small>
                </div>
                
                <div class="mb-3">
                    <label for="users" class="form-label">Membres initiaux (optionnel)</label>
                    <select class="form-select @error('users') is-invalid @enderror" 
                            id="users" 
                            name="users[]" 
                            multiple 
                            size="6">
                        @foreach($users ?? [] as $user)
                            <option value="{{ $user->id }}" 
                                    {{ in_array($user->id, old('users', [])) ? 'selected' : '' }}>
                                {{ $user->name }} {{ $user->surname }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('users')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Maintenez Ctrl (ou Cmd sur Mac) pour sélectionner plusieurs utilisateurs</small>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note :</strong> Vous pourrez assigner des rôles et ajouter d'autres membres après la création du groupe.
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Créer le groupe
                    </button>
                    <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 