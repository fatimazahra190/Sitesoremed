@extends('layouts.admin')
@section('admin-title', 'View News')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">View News</h4>
    <div>
        <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-success">Edit</a>
        <a href="{{ route('admin.news.index') }}" class="btn btn-outline-success">Back to List</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label fw-bold">Title</label>
            <p class="form-control-plaintext">{{ $news->title }}</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Content</label>
            <div class="form-control-plaintext" style="min-height: 200px; white-space: pre-wrap;">{{ $news->content }}</div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Published At</label>
            <p class="form-control-plaintext">{{ $news->published_at ? $news->published_at->format('Y-m-d H:i:s') : 'Not published' }}</p>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Created At</label>
            <p class="form-control-plaintext">{{ $news->created_at->format('Y-m-d H:i:s') }}</p>
        </div>
    </div>
</div>
@endsection 