@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Gestion des sessions</h2>
        <a href="{{ route('admin.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users"></i> Sessions actives (24h)
                    </h5>
                </div>
                <div class="card-body">
                    @if($activeSessions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>ID Session</th>
                                        <th>IP</th>
                                        <th>Dernière activité</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeSessions as $session)
                                        <tr>
                                            <td>
                                                <small class="text-muted">{{ Str::limit($session->id, 20) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $session->ip_address ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <small>{{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.session-management.terminate') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="session_id" value="{{ $session->id }}">
                                                    <button type="submit" class="btn btn-danger btn-sm" 
                                                            onclick="return confirm('Terminer cette session ?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p>Aucune session active</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle text-warning"></i> Sessions suspectes
                    </h5>
                </div>
                <div class="card-body">
                    @if($suspiciousSessions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>ID Session</th>
                                        <th>IP</th>
                                        <th>Dernière activité</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suspiciousSessions as $session)
                                        <tr class="table-warning">
                                            <td>
                                                <small class="text-muted">{{ Str::limit($session->id, 20) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">{{ $session->ip_address ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <small>{{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.session-management.terminate') }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="session_id" value="{{ $session->id }}">
                                                    <button type="submit" class="btn btn-danger btn-sm" 
                                                            onclick="return confirm('Terminer cette session suspecte ?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-shield-alt fa-2x mb-2 text-success"></i>
                            <p>Aucune session suspecte détectée</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line"></i> Statistiques des sessions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-primary">{{ $activeSessions->count() }}</h3>
                                <p class="text-muted">Sessions actives</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-warning">{{ $suspiciousSessions->count() }}</h3>
                                <p class="text-muted">Sessions suspectes</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-info">{{ $activeSessions->unique('ip_address')->count() }}</h3>
                                <p class="text-muted">IPs uniques</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-center">
                                <h3 class="text-success">{{ $activeSessions->where('last_activity', '>=', now()->subHour()->timestamp)->count() }}</h3>
                                <p class="text-muted">Actives (1h)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs"></i> Actions de sécurité
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <form action="{{ route('admin.session-management.terminate') }}" method="POST" 
                                  onsubmit="return confirm('Terminer toutes les sessions ? Cette action déconnectera tous les utilisateurs.')">
                                @csrf
                                <input type="hidden" name="session_id" value="all">
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-power-off"></i> Terminer toutes les sessions
                                </button>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-warning w-100" onclick="refreshPage()">
                                <i class="fas fa-sync"></i> Actualiser
                            </button>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('admin.security-dashboard') }}" class="btn btn-info w-100">
                                <i class="fas fa-shield-alt"></i> Dashboard sécurité
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function refreshPage() {
    location.reload();
}
</script>
@endsection 