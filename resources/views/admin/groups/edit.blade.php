@extends('layouts.admin')
@section('admin-title', 'Edit Group')
@section('content')
<div class="container mt-4">
    <h2>Gérer le groupe : {{ $group->name }}</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form action="{{ route('admin.groups.update', $group) }}" method="POST" class="mb-4">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nom du groupe</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $group->name }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" name="description" id="description" class="form-control" value="{{ $group->description }}">
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.groups.index') }}" class="btn btn-secondary">Retour</a>
    </form>
    <hr>
    <h4>Membres du groupe</h4>
    <form action="{{ route('admin.groups.updateMembers', $group) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="members" class="form-label">Sélectionner les membres</label>
            <select name="members[]" id="members" class="form-select" multiple size="8">
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @if($group->users->contains($user)) selected @endif>{{ $user->name }} ({{ $user->email }})</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Mettre à jour les membres</button>
    </form>
    <hr>
    <h4>Gestion avancée des rôles (matrice)</h4>
    <form action="{{ route('admin.groups.updateRoles', $group) }}" method="POST">
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Membre</th>
                    @foreach($roles as $role)
                        <th>{{ $role->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($group->users as $user)
                    <tr>
                        <td>{{ $user->name }} ({{ $user->email }})</td>
                        @foreach($roles as $role)
                            <td class="text-center">
                                <input type="checkbox" name="roles[{{ $user->id }}][]" value="{{ $role->id }}" @if(isset($userRoles[$user->id]) && in_array($role->id, $userRoles[$user->id])) checked @endif>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Enregistrer la matrice de rôles</button>
    </form>
</div>
@endsection 