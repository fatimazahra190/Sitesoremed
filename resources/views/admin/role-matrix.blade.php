@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Matrice des rôles et permissions</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Permissions</th>
                                    @foreach($roles as $role)
                                        <th class="text-center">{{ $role->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td>
                                            <strong>{{ $permission->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $permission->guard_name }}</small>
                                        </td>
                                        @foreach($roles as $role)
                                            <td class="text-center">
                                                @if($role->hasPermissionTo($permission))
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check"></i>
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">
                                                        <i class="fas fa-times"></i>
                                                    </span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Résumé des rôles</h5>
                                </div>
                                <div class="card-body">
                                    @foreach($roles as $role)
                                        <div class="mb-3">
                                            <h6>{{ $role->name }}</h6>
                                            <p class="text-muted mb-1">
                                                {{ $role->permissions->count() }} permissions
                                            </p>
                                            @if($role->permissions->count() > 0)
                                                <div class="small">
                                                    @foreach($role->permissions->take(3) as $permission)
                                                        <span class="badge badge-info">{{ $permission->name }}</span>
                                                    @endforeach
                                                    @if($role->permissions->count() > 3)
                                                        <span class="text-muted">+{{ $role->permissions->count() - 3 }} autres</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Statistiques</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h4 class="text-primary">{{ $roles->count() }}</h4>
                                                <small class="text-muted">Rôles</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-center">
                                                <h4 class="text-success">{{ $permissions->count() }}</h4>
                                                <small class="text-muted">Permissions</small>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="small text-muted">
                                        <strong>Légende :</strong><br>
                                        <span class="badge badge-success"><i class="fas fa-check"></i></span> Permission accordée<br>
                                        <span class="badge badge-secondary"><i class="fas fa-times"></i></span> Permission refusée
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 