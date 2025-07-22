@extends('layouts.app')
@section('content')
<div class="container">
    <h1 class="mb-4">Viewer Dashboard</h1>
    <div class="row mb-4">
        <div class="col-md-4">
            <h4>Dernières news</h4>
            <ul class="list-group">
                @foreach($latestNews as $news)
                    <li class="list-group-item">{{ $news->title }}</li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-4">
            <h4>Derniers services</h4>
            <ul class="list-group">
                @foreach($latestServices as $service)
                    <li class="list-group-item">{{ $service->name }}</li>
                @endforeach
            </ul>
        </div>
        <div class="col-md-4">
            <h4>Messages récents</h4>
            <ul class="list-group">
                @foreach($recentContacts as $contact)
                    <li class="list-group-item">{{ $contact->subject }} ({{ $contact->name }})</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection 