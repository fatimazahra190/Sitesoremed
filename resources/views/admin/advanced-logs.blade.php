@extends('layouts.admin')

@section('content')
@can('super admin')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-shield-alt"></i> Logs de sécurité avancés</h2>
        <div>
            <a href="{{ route('admin.suspicious-activity') }}" class="btn btn-warning me-2">
                <i class="fas fa-exclamation-triangle"></i> Activité suspecte
            </a>
            <a href="{{ route('admin.security-dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($stats['total_logs']) }}</h4>
                            <p class="mb-0">Total logs</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-list fa-2x"></i>
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
                            <h4>{{ number_format($stats['today_logs']) }}</h4>
                            <p class="mb-0">Logs aujourd'hui</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-day fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($stats['failed_logins']) }}</h4>
                            <p class="mb-0">Échecs connexion</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x"></i>
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
                            <h4>{{ number_format($stats['suspicious_ips']) }}</h4>
                            <p class="mb-0">IPs suspectes</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter"></i> Filtres et recherche
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.advanced-logs') }}" id="filterForm">
                <div class="row">
                    <div class="col-md-3">
                        <label for="action_type" class="form-label">Type d'action</label>
                        <select class="form-select" id="action_type" name="action_type">
                            <option value="">Tous les types</option>
                            @foreach($actionTypes as $action)
                                <option value="{{ $action }}" {{ request('action_type') == $action ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $action)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="ip_address" class="form-label">Adresse IP</label>
                        <input type="text" class="form-control" id="ip_address" name="ip_address" 
                               value="{{ request('ip_address') }}" placeholder="192.168.1.1">
                    </div>
                    <div class="col-md-3">
                        <label for="user_id" class="form-label">Utilisateur</label>
                        <select class="form-select" id="user_id" name="user_id">
                            <option value="">Tous les utilisateurs</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Rechercher...">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-2">
                        <label for="date_from" class="form-label">Date début</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="date_to" class="form-label">Date fin</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="sort_by" class="form-label">Trier par</label>
                        <select class="form-select" id="sort_by" name="sort_by">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date</option>
                            <option value="action" {{ request('sort_by') == 'action' ? 'selected' : '' }}>Action</option>
                            <option value="ip_address" {{ request('sort_by') == 'ip_address' ? 'selected' : '' }}>IP</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="sort_order" class="form-label">Ordre</label>
                        <select class="form-select" id="sort_order" name="sort_order">
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Croissant</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Filtrer
                        </button>
                        <a href="{{ route('admin.advanced-logs') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times"></i> Réinitialiser
                        </a>
                        <div class="dropdown">
                            <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-download"></i> Exporter
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}">
                                    <i class="fas fa-file-excel"></i> Excel/CSV
                                </a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des logs -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-table"></i> Logs de sécurité ({{ $logs->total() }} résultats)
            </h5>
        </div>
        <div class="card-body">
            @if($logs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>Description</th>
                                <th>Utilisateur</th>
                                <th>IP</th>
                                <th>Détails</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>
                                        <small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $this->getActionBadgeClass($log->action) }}">
                                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span title="{{ $log->description }}">
                                            {{ Str::limit($log->description, 50) }}
                                        </span>
                                    </td>
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
                                    <td>
                                        <span class="badge bg-info">{{ $log->ip_address }}</span>
                                    </td>
                                    <td>
                                        @if($log->details && is_array($log->details))
                                            <button class="btn btn-sm btn-outline-info" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailsModal{{ $log->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.log-details', $log) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-search"></i>
                                        </a>
                                    </td>
                                </tr>

                                <!-- Modal pour les détails -->
                                @if($log->details && is_array($log->details))
                                    <div class="modal fade" id="detailsModal{{ $log->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Détails du log</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <pre>{{ json_encode($log->details, JSON_PRETTY_PRINT) }}</pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center text-muted py-5">
                    <i class="fas fa-search fa-3x mb-3"></i>
                    <h5>Aucun log trouvé</h5>
                    <p>Aucun log ne correspond aux critères de recherche.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@else
<div class="container-fluid">
    <div class="text-center py-5">
        <i class="fas fa-lock fa-3x text-danger mb-3"></i>
        <h3>Accès non autorisé</h3>
        <p class="text-muted">Cette fonctionnalité est réservée aux super administrateurs.</p>
        <a href="{{ route('admin.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Retour au dashboard
        </a>
    </div>
</div>
@endcan
@endsection

<style>
.badge {
    font-size: 0.8em;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change
    const filterInputs = document.querySelectorAll('#filterForm select, #filterForm input[type="date"]');
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    });
});
</script> 