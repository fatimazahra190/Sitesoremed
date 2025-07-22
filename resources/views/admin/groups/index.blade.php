@extends('layouts.admin')
@section('admin-title', 'Gestion des groupes')
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Groups List</h4>
        @can('create groups')
        <a href="{{ route('admin.groups.create') }}" class="btn btn-success">Add Group</a>
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
                <th>Membres</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($groups as $group)
                <tr>
                    <td>{{ $group->name }}</td>
                    <td>{{ $group->description }}</td>
                    <td>
                        @foreach($group->users as $user)
                            <span class="badge bg-secondary">{{ $user->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        @can('edit groups')
                        <a href="{{ route('admin.groups.edit', $group) }}" class="btn btn-sm btn-success">Edit</a>
                        @endcan
                        @can('delete groups')
                        <form action="{{ route('admin.groups.destroy', $group) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this group?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No groups found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection 