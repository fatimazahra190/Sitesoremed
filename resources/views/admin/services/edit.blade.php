@extends('layouts.admin')
@section('admin-title', 'Edit Service')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Edit Service</h4>
    <a href="{{ route('admin.services.index') }}" class="btn btn-outline-success">Back to List</a>
</div>
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.services.update', $service) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $service->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description', $service->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                    <option value="">Select Type</option>
                    <option value="medicine" {{ old('type', $service->type) == 'medicine' ? 'selected' : '' }}>Medicine</option>
                    <option value="parapharmacy" {{ old('type', $service->type) == 'parapharmacy' ? 'selected' : '' }}>Parapharmacy</option>
                    <option value="logistics" {{ old('type', $service->type) == 'logistics' ? 'selected' : '' }}>Logistics</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="area" class="form-label">Area</label>
                <input type="text" class="form-control @error('area') is-invalid @enderror" id="area" name="area" value="{{ old('area', $service->area) }}" required>
                @error('area')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-success">Update Service</button>
        </form>
    </div>
</div>
@endsection 