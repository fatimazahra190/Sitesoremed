@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-success text-white text-center py-4 rounded-top-4">
                    <h3 class="mb-0 fw-bold">
                        <i class="fas fa-key me-2"></i>
                        Réinitialiser le Mot de Passe
                    </h3>
                    <p class="mb-0 mt-2 opacity-75">Entrez votre email pour recevoir un lien de réinitialisation</p>
                </div>

                <div class="card-body p-5">
                    @if (session('status'))
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold text-success">
                                <i class="fas fa-envelope me-2"></i>Adresse Email
                            </label>
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                   placeholder="votre@email.com">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid gap-3 mb-4">
                            <button type="submit" class="btn btn-success btn-lg fw-bold">
                                <i class="fas fa-paper-plane me-2"></i>
                                Envoyer le Lien de Réinitialisation
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-3 text-muted">Vous vous souvenez de votre mot de passe ?</p>
                        <a href="{{ route('login') }}" class="btn btn-outline-success btn-lg fw-bold">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            Se Connecter
                        </a>
                    </div>
                </div>
            </div>

            <!-- Informations supplémentaires -->
            <div class="text-center mt-4">
                <p class="text-muted mb-0">
                    <i class="fas fa-shield-alt me-1"></i>
                    Le lien sera envoyé par email de manière sécurisée
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
    
    .form-control {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #2e7d32;
        box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
    }
    
    .btn {
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-2px);
    }
    
    .alert {
        border-radius: 10px;
        border: none;
    }
</style>
@endsection
