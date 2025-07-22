@extends('layouts.admin')
@section('admin-title', 'Détails de l\'utilisateur')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Détails de l'utilisateur : {{ $user->name }} {{ $user->surname }}</h2>
        <div>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informations personnelles</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Nom complet :</th>
                            <td>{{ $user->name }} {{ $user->surname }}</td>
                        </tr>
                        <tr>
                            <th>Email :</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Statut :</th>
                            <td>
                                @if($user->status === 'active')
                                    <span class="badge bg-success">Actif</span>
                                @else
                                    <span class="badge bg-danger">Inactif</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Type :</th>
                            <td>
                                @if($user->utype === 'ADM')
                                    <span class="badge bg-success">Admin</span>
                                @else
                                    <span class="badge bg-secondary">Utilisateur</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Date de création :</th>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Dernière connexion :</th>
                            <td>{{ $user->last_login_at ?? 'Jamais connecté' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Rôles et permissions</h5>
                </div>
                <div class="card-body">
                    <h6>Rôles assignés :</h6>
                    @if($user->roles->count() > 0)
                        <div class="mb-3">
                            @foreach($user->roles as $role)
                                <span class="badge bg-primary me-1">{{ $role->name }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Aucun rôle assigné</p>
                    @endif
                    
                    <h6>Groupes :</h6>
                    @if($user->groups->count() > 0)
                        <div class="mb-3">
                            @foreach($user->groups as $group)
                                <span class="badge bg-info me-1">{{ $group->name }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Aucun groupe assigné</p>
                    @endif
                    
                    <h6>Permissions directes :</h6>
                    @if($user->getAllPermissions()->count() > 0)
                        <div class="mb-3">
                            @foreach($user->getAllPermissions() as $permission)
                                <span class="badge bg-secondary me-1">{{ $permission->name }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Aucune permission directe</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Actions rapides</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <form action="{{ route('admin.users.reset-password', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Réinitialiser le mot de passe de cet utilisateur ?')">
                                <i class="fas fa-key"></i> Reset password
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-{{ $user->status === 'active' ? 'danger' : 'success' }}">
                                <i class="fas fa-{{ $user->status === 'active' ? 'ban' : 'check' }}"></i>
                                {{ $user->status === 'active' ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                        
                        <a href="{{ route('admin.users.assign-roles-form', $user) }}" class="btn btn-info">
                            <i class="fas fa-user-tag"></i> Assigner rôles
                        </a>
                        
                        <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="datetime-local" name="suspended_until" class="form-control d-inline" style="width:200px;display:inline-block;" required>
                            <button type="submit" class="btn btn-secondary" onclick="return confirm('Suspendre cet utilisateur ?')">
                                <i class="fas fa-clock"></i> Suspendre
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                        
                        <form action="{{ route('admin.users.permanent-delete', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('ATTENTION: Cette action supprimera définitivement l\'utilisateur (droit à l\'oubli RGPD). Êtes-vous absolument sûr ?')">
                                <i class="fas fa-trash-alt"></i> Suppression définitive (RGPD)
                            </button>
                        </form>
                        
                        <a href="{{ route('admin.users.export', $user) }}" class="btn btn-info">
                            <i class="fas fa-download"></i> Exporter données (RGPD)
                        </a>
                        
                        @if($user->mfa_enabled)
                            <form action="{{ route('admin.users.disable-mfa', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Désactiver MFA pour cet utilisateur ?')">
                                    <i class="fas fa-shield-alt"></i> Désactiver MFA
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.users.enable-mfa', $user) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-shield-alt"></i> Activer MFA
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('admin.users.force-password-change', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key"></i> Forcer changement mot de passe
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Activité récente</h5>
                </div>
                <div class="card-body">
                    @if($logs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Action</th>
                                        <th>Détails</th>
                                        <th>IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $log)
                                        <tr>
                                            <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                            <td>
                                                <span class="badge bg-{{ $log->action === 'login' ? 'success' : ($log->action === 'logout' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($log->action) }}
                                                </span>
                                            </td>
                                            <td>{{ $log->details }}</td>
                                            <td><code>{{ $log->ip_address }}</code></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $logs->links() }}
                        </div>
                    @else
                        <p class="text-muted">Aucune activité enregistrée</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 