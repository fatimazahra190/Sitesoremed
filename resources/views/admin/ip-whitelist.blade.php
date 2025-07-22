@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Liste blanche IP</h2>
        <a href="{{ route('admin.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus"></i> Ajouter une IP
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.ip-whitelist.add') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="ip_address" class="form-label">Adresse IP *</label>
                            <input type="text" class="form-control @error('ip_address') is-invalid @enderror" 
                                   id="ip_address" name="ip_address" placeholder="192.168.1.1" required>
                            @error('ip_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" 
                                      rows="3" placeholder="Ex: Bureau principal, Serveur VPN..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Ajouter
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shield-alt"></i> IPs autorisées
                    </h5>
                </div>
                <div class="card-body">
                    @if($whitelistedIPs->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Adresse IP</th>
                                        <th>Description</th>
                                        <th>Ajoutée par</th>
                                        <th>Date d'ajout</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($whitelistedIPs as $ip)
                                        <tr>
                                            <td>
                                                <span class="badge bg-success">{{ $ip->ip_address }}</span>
                                            </td>
                                            <td>
                                                {{ $ip->description ?? 'Aucune description' }}
                                            </td>
                                            <td>
                                                <small>{{ $ip->addedBy->name ?? 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <small>{{ $ip->created_at->format('d/m/Y H:i') }}</small>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.ip-whitelist.remove', $ip) }}" 
                                                      method="POST" class="d-inline"
                                                      onsubmit="return confirm('Retirer cette IP de la liste blanche ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
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
                            <p>Aucune IP dans la liste blanche</p>
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
                        <i class="fas fa-info-circle"></i> Informations
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Fonctionnement de la liste blanche :</h6>
                            <ul>
                                <li>Les IPs ajoutées ici sont autorisées à accéder au système</li>
                                <li>Utile pour restreindre l'accès aux bureaux ou serveurs spécifiques</li>
                                <li>Les tentatives d'accès depuis d'autres IPs peuvent être bloquées</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Recommandations :</h6>
                            <ul>
                                <li>Ajoutez uniquement les IPs de confiance</li>
                                <li>Documentez chaque IP avec une description claire</li>
                                <li>Révisée régulièrement la liste</li>
                                <li>Utilisez en complément d'autres mesures de sécurité</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 