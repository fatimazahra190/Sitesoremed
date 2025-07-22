@extends('layouts.admin')
@section('admin-title', 'Manage Services')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Services List</h4>
    @can('create services')
    <a href="{{ route('admin.services.create') }}" class="btn btn-success">Add Service</a>
    @endcan
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<table class="table table-bordered bg-white">
    <thead class="table-success">
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Area</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @forelse($services as $service)
        <tr>
            <td>{{ $service->name }}</td>
            <td><span class="badge bg-success">{{ $service->type }}</span></td>
            <td>{{ $service->area }}</td>
            <td>
                @can('view services')
                <a href="{{ route('admin.services.show', $service) }}" class="btn btn-sm btn-outline-success">View</a>
                @endcan
                @can('edit services')
                <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-success">Edit</a>
                @endcan
                @can('delete services')
                <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this service?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
                @endcan
            </td>
        </tr>
    @empty
        <tr><td colspan="4">No services found.</td></tr>
    @endforelse
    </tbody>
</table>
{{ $services->links() }}
@endsection 