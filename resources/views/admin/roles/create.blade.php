@extends('layouts.admin')
@section('admin-title', 'Add Role')
@section('content')
<div class="container mt-4">
    <h2>Créer un rôle</h2>
    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Nom du rôle</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" name="description" id="description" class="form-control">
        </div>
        <button type="submit" class="btn btn-success">Créer</button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection 