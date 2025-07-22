@extends('layouts.admin')
@section('admin-title', 'Assigner des rôles au groupe')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Assigner des rôles au groupe : {{ $group->name }}</h2>
        <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary">Retour à la liste</a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.groups.assignRoles', $group) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Sélectionner les rôles pour ce groupe :</label>
                    <div class="row">
                        @foreach($roles as $role)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="roles[]" value="{{ $role->id }}" 
                                           id="role_{{ $role->id }}"
                                           @if(in_array($role->id, $groupRoles)) checked @endif>
                                    <label class="form-check-label" for="role_{{ $role->id }}">
                                        <strong>{{ $role->name }}</strong>
                                        @if($role->description)
                                            <br><small class="text-muted">{{ $role->description }}</small>
                                        @endif
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note :</strong> Les utilisateurs de ce groupe hériteront automatiquement de ces rôles.
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Assigner les rôles
                    </button>
                    <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 