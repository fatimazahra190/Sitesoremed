@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="mb-4">Manager Dashboard</h1>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">News à valider</h5>
                    <h2 class="mb-0">{{ $stats['news_count'] }}</h2>
                    <a href="{{ route('admin.news.index') }}" class="text-white">Voir les news</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Services à valider</h5>
                    <h2 class="mb-0">{{ $stats['services_count'] }}</h2>
                    <a href="{{ route('admin.services.index') }}" class="text-white">Voir les services</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Messages en attente</h5>
                    <h2 class="mb-0">{{ $stats['contacts_count'] }}</h2>
                    <a href="{{ route('admin.contacts.index') }}" class="text-white">Voir les messages</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h4>News à valider</h4>
            <ul class="list-group">
                @foreach($pendingNews as $news)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $news->title }}
                        <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-sm btn-success">Valider</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-6">
            <h4>Services à valider</h4>
            <ul class="list-group">
                @foreach($pendingServices as $service)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $service->name }}
                        <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-success">Valider</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary">Créer une news</a>
        <a href="{{ route('admin.services.create') }}" class="btn btn-primary">Créer un service</a>
    </div>
</div>
@endsection 