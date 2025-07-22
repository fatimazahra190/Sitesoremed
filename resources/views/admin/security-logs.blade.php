@extends('layouts.admin')
@section('admin-title', 'Security Logs')
@section('content')
<div class="container mt-4">
    <h2>Security Logs</h2>
    <table class="table table-bordered table-striped mt-4">
        <thead class="table-success">
                            <tr>
                                <th>Date</th>
                <th>User</th>
                                <th>Action</th>
                                <th>IP</th>
                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
        @forelse($logs as $log)
            <tr>
                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                <td>{{ $log->user ? $log->user->name : 'N/A' }}</td>
                <td>{{ $log->action }}</td>
                <td>{{ $log->ip_address }}</td>
                                    <td>
                                        @if($log->details)
                        <pre class="mb-0">{{ is_array(json_decode($log->details, true)) ? json_encode(json_decode($log->details, true), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $log->details }}</pre>
                                        @endif
                                    </td>
                                </tr>
        @empty
            <tr><td colspan="5">No security logs found.</td></tr>
        @endforelse
                        </tbody>
                    </table>
    <div class="d-flex justify-content-center mt-3">
                    {{ $logs->links() }}
    </div>
</div>
@endsection 