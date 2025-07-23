@extends('layouts.admin')
@section('admin-title', 'Manage News')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">News List</h4>
    @can('create news')
    <a href="{{ route('admin.news.create') }}" class="btn btn-success">Add News</a>
    @endcan
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<table class="table table-bordered bg-white">
    <thead class="table-success">
        <tr>
            <th>Title</th>
            <th>Published At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @forelse($news as $item)
        <tr>
            <td>{{ $item->title }}</td>
            <td>{{ $item->published_at ? \Illuminate\Support\Carbon::parse($item->published_at)->format('Y-m-d') : '-' }}</td>
            <td>
                @can('view news')
                <a href="{{ route('admin.news.show', $item) }}" class="btn btn-sm btn-outline-success">View</a>
                @endcan
                @can('edit news')
                <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-sm btn-success">Edit</a>
                @endcan
                @can('publish news')
                @if(!$item->published)
                <form action="{{ route('admin.news.publish', $item) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-primary">Publier</button>
                </form>
                @else
                <form action="{{ route('admin.news.unpublish', $item) }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-sm btn-warning">Dépublier</button>
                </form>
                @endif
                @endcan
                @can('delete news')
                <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this news?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
                @endcan
            </td>
        </tr>
    @empty
        <tr><td colspan="3">No news found.</td></tr>
    @endforelse
    </tbody>
</table>
{{ $news->links() }}
@endsection 