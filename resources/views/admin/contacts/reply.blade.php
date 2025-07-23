@extends('layouts.admin')
@section('admin-title', 'Répondre au contact')
@section('content')
<div class="container mt-4">
    <h4>Répondre à {{ $contact->name }} ({{ $contact->email }})</h4>
    <form method="POST" action="{{ route('admin.contacts.reply.send', $contact) }}">
        @csrf
        <div class="mb-3">
            <label for="reply" class="form-label">Votre réponse</label>
            <textarea name="reply" id="reply" rows="6" class="form-control" required>{{ old('reply') }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Envoyer la réponse</button>
        <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
