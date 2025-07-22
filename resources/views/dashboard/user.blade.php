@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="mb-4">Mon espace</h1>
    <h4>Mes services</h4>
    <ul class="list-group mb-4">
        @forelse($myServices as $service)
            <li class="list-group-item">{{ $service->name }}</li>
        @empty
            <li class="list-group-item text-muted">Aucun service associé.</li>
        @endforelse
    </ul>
    <form action="{{ route('user.delete') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mt-3">Supprimer mon compte</button>
    </form>
</div>
@endsection 