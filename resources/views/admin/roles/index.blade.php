@extends('layouts.admin')
@section('admin-title', 'Roles Management')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Roles List</h4>
        @can('create roles')
        <a href="{{ route('admin.roles.create') }}" class="btn btn-success">Add Role</a>
        @endcan
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($roles as $role)
                <tr>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->description }}</td>
                    <td>
                        @can('edit roles')
                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-success">Edit</a>
                        @endcan
                        @can('delete roles')
                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this role?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No roles found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection 