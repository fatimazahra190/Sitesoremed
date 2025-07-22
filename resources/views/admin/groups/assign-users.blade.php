@extends('layouts.admin')
@section('admin-title', 'Assigner des utilisateurs au groupe')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Assigner des utilisateurs au groupe : {{ $group->name }}</h2>
        <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary">Retour à la liste</a>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.groups.assignUsers', $group) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Sélectionner les utilisateurs pour ce groupe :</label>
                    <div class="row">
                        @foreach($users as $user)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="users[]" value="{{ $user->id }}" 
                                           id="user_{{ $user->id }}"
                                           @if(in_array($user->id, $groupUsers)) checked @endif>
                                    <label class="form-check-label" for="user_{{ $user->id }}">
                                        <strong>{{ $user->name }}</strong>
                                        <br><small class="text-muted">{{ $user->email }}</small>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    <strong>Note :</strong> Seuls les utilisateurs actifs sont affichés. Les utilisateurs assignés hériteront des rôles du groupe.
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Assigner les utilisateurs
                    </button>
                    <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 