@extends('layouts.admin')

@section('content')
@can('super admin')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-search"></i> Détails du log #{{ $log->id }}</h2>
        <div>
            <a href="{{ route('admin.advanced-logs') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour aux logs
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> Informations du log
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID :</strong></td>
                                    <td>{{ $log->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date :</strong></td>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Action :</strong></td>
                                    <td>
                                        <span class="badge {{ $this->getActionBadgeClass($log->action) }}">
                                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Description :</strong></td>
                                    <td>{{ $log->description }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>IP Address :</strong></td>
                                    <td>
                                        <span class="badge bg-info">{{ $log->ip_address }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>User Agent :</strong></td>
                                    <td>
                                        <small class="text-muted">{{ Str::limit($log->user_agent, 100) }}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Utilisateur :</strong></td>
                                    <td>
                                        @if($log->user)
                                            <div>
                                                <strong>{{ $log->user->name }}</strong>
                                                <br><small class="text-muted">{{ $log->user->email }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Détails :</strong></td>
                                    <td>
                                        @if($log->details && is_array($log->details))
                                            <button class="btn btn-sm btn-outline-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailsModal">
                                                <i class="fas fa-eye"></i> Voir détails
                                            </button>
                                        @else
                                            <span class="text-muted">Aucun détail</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line"></i> Statistiques
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Actions similaires (24h)</h6>
                        <div class="d-flex justify-content-between">
                            <span>Même action :</span>
                            <span class="badge bg-primary">
                                {{ SecurityLog::where('action', $log->action)
                                    ->where('created_at', '>=', now()->subDay())
                                    ->count() }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Même IP :</span>
                            <span class="badge bg-info">
                                {{ SecurityLog::where('ip_address', $log->ip_address)
                                    ->where('created_at', '>=', now()->subDay())
                                    ->count() }}
                            </span>
                        </div>
                        @if($log->user)
                        <div class="d-flex justify-content-between">
                            <span>Même utilisateur :</span>
                            <span class="badge bg-success">
                                {{ SecurityLog::where('user_id', $log->user_id)
                                    ->where('created_at', '>=', now()->subDay())
                                    ->count() }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <h6>Actions récentes</h6>
                        @php
                            $recentLogs = SecurityLog::where('ip_address', $log->ip_address)
                                ->where('id', '!=', $log->id)
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                        @if($recentLogs->count() > 0)
                            @foreach($recentLogs as $recentLog)
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small>{{ $recentLog->created_at->format('H:i') }}</small>
                                    <span class="badge {{ $this->getActionBadgeClass($recentLog->action) }}">
                                        {{ ucfirst(str_replace('_', ' ', $recentLog->action)) }}
                                    </span>
                                </div>
                            @endforeach
                        @else
                            <small class="text-muted">Aucune autre action récente</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tools"></i> Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <button class="btn btn-warning w-100 mb-2" 
                                    onclick="addToWhitelist('{{ $log->ip_address }}')">
                                <i class="fas fa-shield-alt"></i> Ajouter IP à la liste blanche
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-danger w-100 mb-2" 
                                    onclick="blockIP('{{ $log->ip_address }}')">
                                <i class="fas fa-ban"></i> Bloquer IP
                            </button>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-info w-100 mb-2" 
                                    onclick="investigateUser('{{ $log->user_id }}')">
                                <i class="fas fa-user-search"></i> Enquêter utilisateur
                            </button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('admin.advanced-logs', ['ip_address' => $log->ip_address]) }}" 
                               class="btn btn-secondary w-100 mb-2">
                                <i class="fas fa-search"></i> Voir tous les logs de cette IP
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour les détails -->
@if($log->details && is_array($log->details))
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails complets du log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre class="bg-light p-3 rounded">{{ json_encode($log->details, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
    </div>
</div>
@endif

<script>
function addToWhitelist(ip) {
    if (confirm('Ajouter cette IP à la liste blanche ?')) {
        // Implémentation de l'ajout à la liste blanche
        alert('Fonctionnalité à implémenter');
    }
}

function blockIP(ip) {
    if (confirm('Bloquer cette IP ?')) {
        // Implémentation du blocage d'IP
        alert('Fonctionnalité à implémenter');
    }
}

function investigateUser(userId) {
    if (userId) {
        window.open(`{{ route('admin.users.show', '') }}/${userId}`, '_blank');
    } else {
        alert('Aucun utilisateur associé à ce log');
    }
}
</script>
@endsection 