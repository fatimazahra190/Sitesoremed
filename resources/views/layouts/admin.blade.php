<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f6fbf7; }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #388e3c 0%, #66bb6a 100%);
            color: #fff;
        }
        .sidebar a { color: #fff; text-decoration: none; display: block; padding: 12px 20px; border-radius: 4px; }
        .sidebar a.active, .sidebar a:hover { background: #2e7d32; }
        .admin-header { background: #388e3c; color: #fff; padding: 16px 24px; }
        .content { padding: 32px; }
        .btn-success { background: #43a047; border: none; }
        .btn-success:hover { background: #388e3c; }
    </style>
</head>
<body>
<div class="d-flex">
    <nav class="sidebar p-3">
        <h4 class="mb-4">Admin</h4>
        <a href="{{ route('admin.index') }}">Dashboard</a>
        <a href="{{ route('admin.news.index') }}">News</a>
        <a href="{{ route('admin.services.index') }}">Services</a>
        <a href="{{ route('admin.contacts.index') }}">Messages</a>
        <a href="{{ route('admin.users.index') }}">Utilisateurs</a>
        <a href="{{ route('admin.roles.index') }}">Rôles</a>
        <a href="{{ route('home') }}">View Site</a>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </nav>
    <div class="flex-grow-1">
        <div class="admin-header">
            <h2 class="mb-0">@yield('admin-title', 'Admin Panel')</h2>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 