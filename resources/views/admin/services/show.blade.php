@extends('layouts.admin')
@section('admin-title', 'View Service')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">View Service</h4>
    <div>
        <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-success">Edit</a>
        <a href="{{ route('admin.services.index') }}" class="btn btn-outline-success">Back to List</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-bold">Name</label>
            <p class="form-control-plaintext">{{ $service->name }}</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Description</label>
            <div class="form-control-plaintext" style="min-height: 100px; white-space: pre-wrap;">{{ $service->description }}</div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Type</label>
            <p class="form-control-plaintext"><span class="badge bg-success">{{ $service->type }}</span></p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Area</label>
            <p class="form-control-plaintext">{{ $service->area }}</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Created At</label>
            <p class="form-control-plaintext">{{ $service->created_at->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</div>
@endsection 