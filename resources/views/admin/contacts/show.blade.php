@extends('layouts.admin')
@section('admin-title', 'View Message')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">View Message</h4>
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-success">Back to List</a>
</div>
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-bold">Name</label>
            <p class="form-control-plaintext">{{ $contact->name }}</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Email</label>
            <p class="form-control-plaintext">{{ $contact->email }}</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Subject</label>
            <p class="form-control-plaintext">{{ $contact->subject }}</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Message</label>
            <div class="form-control-plaintext" style="min-height: 200px; white-space: pre-wrap;">{{ $contact->message }}</div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Received At</label>
            <p class="form-control-plaintext">{{ $contact->created_at->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</div>
@endsection 