@extends('layouts.admin')
@section('admin-title', 'Manage Messages')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Contact Messages</h4>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<table class="table table-bordered bg-white">
    <thead class="table-success">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @forelse($contacts as $contact)
        <tr>
            <td>{{ $contact->name }}</td>
            <td>{{ $contact->email }}</td>
            <td>{{ $contact->subject }}</td>
            <td>{{ $contact->created_at->format('Y-m-d H:i') }}</td>
            <td>
                <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-outline-success">View</a>
                @can('delete contacts')
                <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this contact?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
                @endcan
            </td>
        </tr>
    @empty
        <tr><td colspan="5">No messages found.</td></tr>
    @endforelse
    </tbody>
</table>
{{ $contacts->links() }}
@endsection 