@extends('layouts.app')
@section('content')
<style>
    body {
        background: linear-gradient(135deg, #e0f7fa 0%, #388e3c 100%) !important;
    }
    .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
    }
    .auth-card {
        border-radius: 20px;
        box-shadow: 0 8px 32px 0 rgba(56, 142, 60, 0.2);
        background: #fff;
        overflow: hidden;
        animation: fadeInUp 0.8s cubic-bezier(0.23, 1, 0.32, 1);
    }
    @keyframes fadeInUp {
        from { transform: translateY(40px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    .auth-header {
        background: linear-gradient(90deg, #388e3c 60%, #66bb6a 100%);
        color: #fff;
        padding: 2rem 1rem 1rem 1rem;
        text-align: center;
        border-bottom-left-radius: 40px 20px;
        border-bottom-right-radius: 40px 20px;
    }
    .auth-header h2 {
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 0.5rem;
    }
    .auth-header p {
        font-size: 1.1rem;
        opacity: 0.9;
    }
    .auth-form {
        padding: 2rem 2.5rem 2rem 2.5rem;
    }
    .auth-form .form-control {
        border-radius: 10px;
        border: 1px solid #b2dfdb;
        transition: border-color 0.2s;
    }
    .auth-form .form-control:focus {
        border-color: #388e3c;
        box-shadow: 0 0 0 2px #c8e6c9;
    }
    .auth-form .btn-success {
        background: linear-gradient(90deg, #388e3c 60%, #66bb6a 100%);
        border: none;
        border-radius: 10px;
        font-weight: 600;
        padding: 0.75rem 2rem;
        transition: background 0.2s;
    }
    .auth-form .btn-success:hover {
        background: linear-gradient(90deg, #2e7d32 60%, #388e3c 100%);
    }
    .auth-form .form-check-label {
        color: #388e3c;
    }
    .auth-form .register-link, .auth-form .login-link {
        display: block;
        margin-top: 1.5rem;
        text-align: center;
        color: #388e3c;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.2s;
    }
    .auth-form .register-link:hover, .auth-form .login-link:hover {
        color: #1b5e20;
        text-decoration: underline;
    }
    .auth-form .forgot-link {
        color: #66bb6a;
        font-size: 0.95rem;
        margin-left: 1rem;
    }
    .auth-form .forgot-link:hover {
        color: #388e3c;
        text-decoration: underline;
    }
    @media (max-width: 600px) {
        .auth-form { padding: 1.5rem 1rem; }
    }
</style>
<div class="auth-container">
    <div class="col-md-7 col-lg-5 mx-auto">
        <div class="auth-card shadow-lg">
            <div class="auth-header">
                <h2>Bienvenue chez SOREMED</h2>
                <p>Connectez-vous à votre espace client</p>
            </div>
            <div class="auth-form">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="mb-3 d-flex align-items-center justify-content-between">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Se souvenir de moi</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="forgot-link" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
                        @endif
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">Connexion</button>
                    </div>
                </form>
                @if (Route::has('register'))
                    <a class="register-link" href="{{ route('register') }}">
                        Nouveau client ? Créez un compte
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
