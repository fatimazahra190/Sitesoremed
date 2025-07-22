@extends('layouts.admin')
@section('admin-title', 'Security Checklist')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Security Checklist</h2>
        <a href="{{ route('admin.index') }}" class="btn btn-secondary">Retour au dashboard</a>
    </div>
    
    <div class="row">
        @foreach($checklist as $sectionKey => $section)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ $section['title'] }}</h5>
                    </div>
                    <div class="card-body">
                        @foreach($section['items'] as $itemKey => $item)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $item['label'] }}</span>
                                <span class="badge bg-{{ $item['status'] }}">{{ $item['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Recommandations de sécurité -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Recommandations de sécurité</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Actions recommandées :</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success"></i>
                            Vérifier régulièrement les logs de sécurité
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success"></i>
                            Réviser les permissions des rôles
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success"></i>
                            Surveiller les échecs de connexion
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success"></i>
                            Maintenir les mots de passe forts
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>Bonnes pratiques :</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-shield-alt text-info"></i>
                            Utiliser l'authentification à deux facteurs
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-shield-alt text-info"></i>
                            Limiter les tentatives de connexion
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-shield-alt text-info"></i>
                            Chiffrer les données sensibles
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-shield-alt text-info"></i>
                            Effectuer des audits réguliers
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 