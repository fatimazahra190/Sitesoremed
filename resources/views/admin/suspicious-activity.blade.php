@extends('layouts.admin')

@section('content')
@can('super admin')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-exclamation-triangle"></i> Activité suspecte</h2>
        <div>
            <a href="{{ route('admin.advanced-logs') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour aux logs
            </a>
        </div>
    </div>

    <!-- Statistiques de sécurité -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $securityStats['total_suspicious_ips'] }}</h4>
                            <p class="mb-0">IPs suspectes</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $securityStats['failed_logins_today'] }}</h4>
                            <p class="mb-0">Échecs aujourd'hui</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $securityStats['unauthorized_access_today'] }}</h4>
                            <p class="mb-0">Accès non autorisés</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-lock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $suspiciousActions->count() }}</h4>
                            <p class="mb-0">Actions suspectes</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-shield-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- IPs suspectes -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-network-wired"></i> IPs suspectes (>5 échecs)
                    </h5>
                </div>
                <div class="card-body">
                    @if($suspiciousIPs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>IP Address</th>
                                        <th>Échecs</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suspiciousIPs as $ip)
                                        <tr>
                                            <td>
                                                <span class="badge bg-danger">{{ $ip->ip_address }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">{{ $ip->logs_count }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-danger" 
                                                            onclick="blockIP('{{ $ip->ip_address }}')">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                    <button class="btn btn-outline-warning" 
                                                            onclick="addToWhitelist('{{ $ip->ip_address }}')">
                                                        <i class="fas fa-shield-alt"></i>
                                                    </button>
                                                    <a href="{{ route('admin.advanced-logs', ['ip_address' => $ip->ip_address]) }}" 
                                                       class="btn btn-outline-info">
                                                        <i class="fas fa-search"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                            <p>Aucune IP suspecte détectée</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions suspectes récentes -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-circle"></i> Actions suspectes récentes
                    </h5>
                </div>
                <div class="card-body">
                    @if($suspiciousActions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Action</th>
                                        <th>Utilisateur</th>
                                        <th>IP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suspiciousActions as $action)
                                        <tr>
                                            <td>
                                                <small>{{ $action->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge {{ $this->getActionBadgeClass($action->action) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $action->action)) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($action->user)
                                                    <small>{{ $action->user->name }}</small>
                                                @else
                                                    <small class="text-muted">N/A</small>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $action->ip_address }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                            <p>Aucune action suspecte récente</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes de sécurité -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bell"></i> Alertes de sécurité
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $alerts = [];
                        
                        // Alerte si trop d'échecs aujourd'hui
                        if ($securityStats['failed_logins_today'] > 10) {
                            $alerts[] = [
                                'type' => 'danger',
                                'icon' => 'fas fa-exclamation-triangle',
                                'title' => 'Trop de tentatives échouées',
                                'message' => "{$securityStats['failed_logins_today']} tentatives échouées aujourd'hui. Vérifiez les logs."
                            ];
                        }
                        
                        // Alerte si IPs suspectes
                        if ($securityStats['total_suspicious_ips'] > 0) {
                            $alerts[] = [
                                'type' => 'warning',
                                'icon' => 'fas fa-shield-alt',
                                'title' => 'IPs suspectes détectées',
                                'message' => "{$securityStats['total_suspicious_ips']} IP(s) avec plus de 5 échecs de connexion."
                            ];
                        }
                        
                        // Alerte si accès non autorisés
                        if ($securityStats['unauthorized_access_today'] > 5) {
                            $alerts[] = [
                                'type' => 'danger',
                                'icon' => 'fas fa-lock',
                                'title' => 'Accès non autorisés',
                                'message' => "{$securityStats['unauthorized_access_today']} tentatives d'accès non autorisé aujourd'hui."
                            ];
                        }
                        
                        // Si aucune alerte
                        if (empty($alerts)) {
                            $alerts[] = [
                                'type' => 'success',
                                'icon' => 'fas fa-check-circle',
                                'title' => 'Système sécurisé',
                                'message' => 'Aucune activité suspecte détectée. Le système est sécurisé.'
                            ];
                        }
                    @endphp

                    @foreach($alerts as $alert)
                        <div class="alert alert-{{ $alert['type'] }} d-flex align-items-center">
                            <i class="{{ $alert['icon'] }} me-2"></i>
                            <div>
                                <strong>{{ $alert['title'] }}</strong><br>
                                {{ $alert['message'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Actions de sécurité -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tools"></i> Actions de sécurité
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <button class="btn btn-danger w-100 mb-2" onclick="blockAllSuspiciousIPs()">
                                <i class="fas fa-ban"></i> Bloquer toutes les IPs suspectes
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-warning w-100 mb-2" onclick="sendSecurityAlert()">
                                <i class="fas fa-bell"></i> Envoyer alerte sécurité
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info w-100 mb-2" onclick="generateSecurityReport()">
                                <i class="fas fa-file-alt"></i> Générer rapport
                            </button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.security-dashboard') }}" class="btn btn-secondary w-100 mb-2">
                                <i class="fas fa-dashboard"></i> Dashboard sécurité
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function blockIP(ip) {
    if (confirm(`Bloquer l'IP ${ip} ?`)) {
        // Implémentation du blocage
        alert('Fonctionnalité à implémenter');
    }
}

function addToWhitelist(ip) {
    if (confirm(`Ajouter l'IP ${ip} à la liste blanche ?`)) {
        // Implémentation de l'ajout à la liste blanche
        alert('Fonctionnalité à implémenter');
    }
}

function blockAllSuspiciousIPs() {
    if (confirm('Bloquer toutes les IPs suspectes ? Cette action est irréversible.')) {
        // Implémentation du blocage en masse
        alert('Fonctionnalité à implémenter');
    }
}

function sendSecurityAlert() {
    if (confirm('Envoyer une alerte de sécurité à tous les administrateurs ?')) {
        // Implémentation de l'envoi d'alerte
        alert('Fonctionnalité à implémenter');
    }
}

function generateSecurityReport() {
    if (confirm('Générer un rapport de sécurité ?')) {
        // Implémentation de la génération de rapport
        alert('Fonctionnalité à implémenter');
    }
}
</script>
@endsection 