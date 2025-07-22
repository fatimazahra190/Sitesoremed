@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger shadow-lg rounded-4">
                <div class="card-header bg-danger text-white text-center rounded-top-4">
                    <h3 class="mb-0">Suppression de compte</h3>
                </div>
                <div class="card-body p-5">
                    <p class="fs-5 mb-4 text-center">
                        Êtes-vous sûr de vouloir <strong>supprimer définitivement</strong> votre compte ?<br>
                        Cette action est <span class="text-danger fw-bold">irréversible</span> et toutes vos données seront perdues.
                    </p>
                    <form method="POST" action="{{ route('user.account.delete') }}">
                        @csrf
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-lg">Annuler</a>
                            <button type="submit" class="btn btn-danger btn-lg fw-bold" onclick="return confirm('Êtes-vous sûr ? Cette action est irréversible.')">
                                Supprimer mon compte
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 