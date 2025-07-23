@extends('layouts.admin')
@section('admin-title', 'Users Management')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Users List</h4>
        @can('create users')
        <a href="{{ route('admin.users.create') }}" class="btn btn-success">Add User</a>
        @endcan
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <form method="GET" class="row g-2 mb-3 align-items-end">
        <div class="col-md-3">
            <input type="text" name="search" class="form-control" placeholder="Recherche nom ou email" value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">Statut</option>
                <option value="active" @if(request('status')==='active') selected @endif>Actif</option>
                <option value="inactive" @if(request('status')==='inactive') selected @endif>Inactif</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="utype" class="form-select">
                <option value="">Type d'utilisateur</option>
                <option value="ADM" @if(request('utype')==='ADM') selected @endif>Admin</option>
                <option value="USR" @if(request('utype')==='USR') selected @endif>Utilisateur</option>
            </select>
        </div>
        <div class="col-md-2">
            <select name="role" class="form-select">
                <option value="">Rôle Spatie</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" @if(request('role')==$role->id) selected @endif>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="group_id" class="form-select">
                <option value="">Groupe</option>
                @foreach($groups as $group)
                    <option value="{{ $group->id }}" @if(request('group_id')==$group->id) selected @endif>{{ $group->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-success w-100">Filtrer</button>
        </div>
    </form>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->utype === 'ADM')
                            <span class="badge bg-success">Admin</span>
                        @else
                            <span class="badge bg-secondary">Utilisateur</span>
                        @endif
                    </td>
                    <td>
                        @if($user->status === 'active')
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-danger">Inactif</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info">Voir</a>
                        @can('edit users')
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">Modifier</a>
                        @endcan
                        <form action="{{ route('admin.users.updateRole', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <select name="utype" class="form-select form-select-sm d-inline w-auto">
                                <option value="USR" @if($user->utype==='USR') selected @endif>Utilisateur</option>
                                <option value="ADM" @if($user->utype==='ADM') selected @endif>Admin</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Changer</button>
                        </form>
                        <form action="{{ route('admin.users.reset-password', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Réinitialiser le mot de passe de cet utilisateur ?')">Reset password</button>
                        </form>
                        <a href="{{ route('admin.users.assign-roles-form', $user) }}" class="btn btn-sm btn-info">Assigner rôles</a>
                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $user->status === 'active' ? 'btn-danger' : 'btn-success' }}">
                                {{ $user->status === 'active' ? 'Désactiver' : 'Activer' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="d-inline">
                            @csrf
                            <input type="datetime-local" name="suspended_until" class="form-control form-control-sm d-inline w-auto" style="width:180px;display:inline-block;" required>
                            <button type="submit" class="btn btn-sm btn-secondary" onclick="return confirm('Suspendre cet utilisateur jusqu\'à la date choisie ?')">Suspendre</button>
                        </form>
                        @can('delete users')
                        <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">Supprimer</button>
                        </form>
                        @endcan
                        @can('export data')
                        <a href="{{ route('admin.users.export', $user) }}" class="btn btn-sm btn-success" target="_blank">Exporter</a>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Aucun utilisateur trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-3">
        {{ $users->links() }}
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Importer des utilisateurs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Fichier CSV</label>
                        <input type="file" class="form-control" id="csv_file" name="csv_file" accept=".csv,.txt" required>
                        <small class="form-text text-muted">
                            Format CSV attendu : email, name, surname, status, roles<br>
                            Exemple : john@example.com, John, Doe, active, Admin,Editor
                        </small>
                    </div>
                    <div class="alert alert-info">
                        <strong>Note :</strong>
                        <ul class="mb-0">
                            <li>Les mots de passe temporaires seront générés automatiquement</li>
                            <li>Des emails d'invitation seront envoyés</li>
                            <li>Les rôles doivent être séparés par des virgules</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Importer</button>
                </div>
            </form>
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
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recipients[]" value="selected" id="recipient_selected">
                            <label class="form-check-label" for="recipient_selected">Utilisateurs sélectionnés</label>
                        </div>
                    </div>
                    <div class="mb-3" id="selectedUsersDiv" style="display: none;">
                        <label class="form-label">Sélectionner les utilisateurs</label>
                        <select class="form-select" name="selected_users[]" multiple size="6">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} {{ $user->surname }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide selected users div based on checkbox
    document.getElementById('recipient_selected').addEventListener('change', function() {
        const selectedUsersDiv = document.getElementById('selectedUsersDiv');
        selectedUsersDiv.style.display = this.checked ? 'block' : 'none';
    });
});
</script>
@endsection 