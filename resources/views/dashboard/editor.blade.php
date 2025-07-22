@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="mb-4">Editor Dashboard</h1>
    <div class="row mb-4">
        <div class="col-md-6">
            <h4>News à éditer</h4>
            <ul class="list-group">
                @foreach($editableNews as $news)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $news->title }}
                        <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-sm btn-info">Éditer</a>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-6">
            <h4>Services à éditer</h4>
            <ul class="list-group">
                @foreach($editableServices as $service)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $service->name }}
                        <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-info">Éditer</a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-12">
            <h4>Modifications récentes</h4>
            <ul class="list-group">
                @forelse($recentEdits as $edit)
                    <li class="list-group-item">{{ $edit }}</li>
                @empty
                    <li class="list-group-item text-muted">Aucune modification récente.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="mt-4">
        <a href="{{ route('admin.news.create') }}" class="btn btn-info">Créer une news</a>
        <a href="{{ route('admin.services.create') }}" class="btn btn-info">Créer un service</a>
    </div>
</div>
@endsection 