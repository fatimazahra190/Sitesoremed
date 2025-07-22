@extends('layouts.app')
@section('content')
    <h1>User Dashboard</h1>
    <form action="{{ route('user.delete') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger mt-3">Supprimer mon compte</button>
    </form>
@endsection