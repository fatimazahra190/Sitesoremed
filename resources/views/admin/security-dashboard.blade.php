@extends('layouts.admin')
@section('admin-title', 'Dashboard de sécurité')
@section('content')
@can('super admin')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Dashboard de sécurité</h2>
        <div>
            <a href="{{ route('admin.security-logs') }}" class="btn btn-info me-2">
                <i class="fas fa-list"></i> Voir tous les logs
            </a>
            <a href="{{ route('admin.security-checklist') }}" class="btn btn-warning">
                <i class="fas fa-clipboard-check"></i> Checklist sécurité
            </a>
        </div>
    </div>
    
    <!-- Statistiques de sécurité -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Utilisateurs actifs</h5>
                    <h2 class="mb-0">{{ $stats['active_users'] }}</h2>
                    <small>Sur {{ $stats['total_users'] }} total</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">MFA activé</h5>
                    <h2 class="mb-0">{{ $stats['mfa_enabled_users'] }}</h2>
                    <small>{{ $stats['mfa_enabled_users'] > 0 ? round(($stats['mfa_enabled_users'] / $stats['total_users']) * 100, 1) : 0 }}% des utilisateurs</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Connexions (7j)</h5>
                    <h2 class="mb-0">{{ $stats['recent_logins'] }}</h2>
                    <small>{{ $stats['failed_logins'] }} échecs</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Événements sécurité</h5>
                    <h2 class="mb-0">{{ $stats['security_events'] }}</h2>
                    <small>7 derniers jours</small>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Événements récents -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Événements de sécurité récents</h5>
                </div>
                <div class="card-body">
                    @if($recentSecurityEvents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Utilisateur</th>
                                        <th>Action</th>
                                        <th>Détails</th>
                                        <th>IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSecurityEvents as $event)
                                        <tr>
                                            <td>{{ $event->created_at->format('d/m H:i') }}</td>
                                            <td>
                                                @if($event->user)
                                                    {{ $event->user->name }}
                                                @else
                                                    <span class="text-muted">Utilisateur supprimé</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $event->action === 'login' ? 'success' : ($event->action === 'login_failed' ? 'danger' : 'info') }}">
                                                    {{ ucfirst($event->action) }}
                                                </span>
                                            </td>
                                            <td>{{ Str::limit($event->details, 50) }}</td>
                                            <td><code>{{ $event->ip_address }}</code></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Aucun événement de sécurité récent</p>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Alertes de sécurité -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Alertes de sécurité</h5>
                </div>
                <div class="card-body">
                    @if($usersWithOldPasswords->count() > 0)
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-exclamation-triangle"></i> Mots de passe anciens</h6>
                            <p class="mb-2">{{ $usersWithOldPasswords->count() }} utilisateur(s) n'ont pas changé leur mot de passe depuis plus de 3 mois.</p>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-warning">Voir la liste</a>
                        </div>
                    @endif
                    
                    @if($stats['mfa_enabled_users'] < $stats['total_users'] * 0.5)
                        <div class="alert alert-info">
                            <h6><i class="fas fa-shield-alt"></i> MFA recommandé</h6>
                            <p class="mb-2">Seulement {{ round(($stats['mfa_enabled_users'] / $stats['total_users']) * 100, 1) }}% des utilisateurs ont MFA activé.</p>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-info">Activer MFA</a>
                        </div>
                    @endif
                    
                    @if($stats['failed_logins'] > 10)
                        <div class="alert alert-danger">
                            <h6><i class="fas fa-exclamation-circle"></i> Tentatives d'intrusion</h6>
                            <p class="mb-2">{{ $stats['failed_logins'] }} tentatives de connexion échouées cette semaine.</p>
                            <a href="{{ route('admin.security-logs') }}" class="btn btn-sm btn-danger">Voir les logs</a>
                        </div>
                    @endif
                    
                    @if($stats['suspended_users'] > 0)
                        <div class="alert alert-secondary">
                            <h6><i class="fas fa-user-slash"></i> Utilisateurs suspendus</h6>
                            <p class="mb-2">{{ $stats['suspended_users'] }} utilisateur(s) suspendu(s).</p>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">Gérer</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recommandations de sécurité -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Recommandations de sécurité</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-shield-alt text-success"></i> Actions recommandées</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success"></i> Activer MFA pour tous les utilisateurs</li>
                                <li><i class="fas fa-check text-success"></i> Forcer le changement de mots de passe anciens</li>
                                <li><i class="fas fa-check text-success"></i> Examiner les tentatives de connexion échouées</li>
                                <li><i class="fas fa-check text-success"></i> Mettre à jour la politique de mots de passe</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-exclamation-triangle text-warning"></i> Points d'attention</h6>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-exclamation text-warning"></i> Surveiller les connexions depuis des IP suspectes</li>
                                <li><i class="fas fa-exclamation text-warning"></i> Vérifier les permissions des rôles</li>
                                <li><i class="fas fa-exclamation text-warning"></i> Maintenir les logs de sécurité</li>
                                <li><i class="fas fa-exclamation text-warning"></i> Former les utilisateurs à la sécurité</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan
@endsection 