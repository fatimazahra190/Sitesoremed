@extends('layouts.admin')
@section('admin-title', 'Éditer le rôle')
@section('content')
<div class="container mt-4">
    <h2>Éditer le rôle</h2>
    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nom du rôle</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $role->name }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" name="description" id="description" class="form-control" value="{{ $role->description }}">
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Retour</a>
    </form>
</div>
@endsection 