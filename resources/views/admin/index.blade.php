@extends('layouts.admin')
@section('admin-title', 'Dashboard')
@section('content')
<div class="mb-3">
    <span class="badge bg-info">Type d'utilisateur : {{ Auth::user()->utype ?? 'Non défini' }}</span>
</div>
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">News</h5>
                <h2 class="mb-0">{{ $stats['news_count'] }}</h2>
                <a href="{{ route('admin.news.index') }}" class="text-white">Manage News →</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Services</h5>
                <h2 class="mb-0">{{ $stats['services_count'] }}</h2>
                <a href="{{ route('admin.services.index') }}" class="text-white">Manage Services →</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Messages</h5>
                <h2 class="mb-0">{{ $stats['contacts_count'] }}</h2>
                <a href="{{ route('admin.contacts.index') }}" class="text-white">View Messages →</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Messages</h5>
            </div>
            <div class="card-body">
                @if($stats['recent_contacts']->count() > 0)
                    <div class="list-group">
                        @foreach($stats['recent_contacts'] as $contact)
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $contact->name }}</h6>
                                        <p class="mb-1">{{ $contact->subject }}</p>
                                        <small class="text-muted">{{ $contact->email }} • {{ $contact->created_at->format('M d, Y H:i') }}</small>
                                    </div>
                                    <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-outline-success">View</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No recent messages.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.news.create') }}" class="btn btn-success">Add News</a>
                    <a href="{{ route('admin.services.create') }}" class="btn btn-success">Add Service</a>
                    @can('create users')
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Créer un utilisateur</a>
                    @endcan
                    @can('create groups')
                    <a href="{{ route('admin.groups.create') }}" class="btn btn-primary">Créer un groupe</a>
                    @endcan
                    @can('view groups')
                    <a href="{{ route('admin.groups.index') }}" class="btn btn-primary">Gérer les groupes</a>
                    @endcan
                    @can('view security dashboard')
                    <a href="{{ route('admin.security-dashboard') }}" class="btn btn-warning">Dashboard Sécurité</a>
                    @endcan
                    @can('view roles')
                    <a href="{{ route('admin.role-matrix') }}" class="btn btn-info">Matrice des rôles</a>
                    @endcan
                    @can('view security logs')
                    <a href="{{ route('admin.security-logs') }}" class="btn btn-warning">Logs de sécurité</a>
                    @endcan
                    @can('view security checklist')
                    <a href="{{ route('admin.security-checklist') }}" class="btn btn-dark">Security Checklist</a>
                    @endcan
                    <a href="{{ route('home.index') }}" class="btn btn-outline-success">View Site</a>
                </div>
            </div>
        </div>
    </div>
</div>

                <!-- Advanced Security Features -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h4><i class="fas fa-shield-alt"></i> Fonctionnalités de sécurité avancées</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-upload fa-2x text-warning mb-2"></i>
                                <h5>Import/Export</h5>
                                <p class="text-muted">Gérer les utilisateurs en masse</p>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-warning btn-sm">
                                    Gérer
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-bell fa-2x text-danger mb-2"></i>
                                <h5>Notifications</h5>
                                <p class="text-muted">Envoyer des alertes de sécurité</p>
                                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#notificationModal">
                                    Envoyer
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x text-info mb-2"></i>
                                <h5>Sessions</h5>
                                <p class="text-muted">Gérer les sessions actives</p>
                                <a href="{{ route('admin.session-management') }}" class="btn btn-outline-info btn-sm">
                                    Gérer
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-network-wired fa-2x text-success mb-2"></i>
                                <h5>Liste blanche IP</h5>
                                <p class="text-muted">Contrôler l'accès par IP</p>
                                <a href="{{ route('admin.ip-whitelist') }}" class="btn btn-outline-success btn-sm">
                                    Gérer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liens rapides sécurité -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h4><i class="fas fa-shield-alt"></i> Sécurité et surveillance</h4>
                    </div>
                </div>
                <div class="row">
                    @can('super admin')
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-list fa-2x text-primary mb-2"></i>
                                <h5>Logs avancés</h5>
                                <p class="text-muted">Surveillance complète</p>
                                <a href="{{ route('admin.advanced-logs') }}" class="btn btn-outline-primary btn-sm">
                                    Accéder
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                                <h5>Activité suspecte</h5>
                                <p class="text-muted">Détection automatique</p>
                                <a href="{{ route('admin.suspicious-activity') }}" class="btn btn-outline-warning btn-sm">
                                    Surveiller
                                </a>
                            </div>
                        </div>
                    </div>
                    @endcan
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-file-alt fa-2x text-info mb-2"></i>
                                <h5>Rapports</h5>
                                <p class="text-muted">Export et audit</p>
                                <a href="{{ route('admin.security-logs') }}" class="btn btn-outline-info btn-sm">
                                    Générer
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <i class="fas fa-cogs fa-2x text-success mb-2"></i>
                                <h5>Configuration</h5>
                                <p class="text-muted">Paramètres sécurité</p>
                                <a href="{{ route('admin.security-checklist') }}" class="btn btn-outline-success btn-sm">
                                    Configurer
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

<!-- Security Notification Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Notification de sécurité</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.security.notify') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Sujet *</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message *</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Destinataires *</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recipients[]" value="all" id="recipient_all">
                            <label class="form-check-label" for="recipient_all">Tous les utilisateurs</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recipients[]" value="active" id="recipient_active">
                            <label class="form-check-label" for="recipient_active">Utilisateurs actifs uniquement</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recipients[]" value="admins" id="recipient_admins">
                            <label class="form-check-label" for="recipient_admins">Administrateurs uniquement</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Envoyer la notification</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection