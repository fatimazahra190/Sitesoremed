@extends('layouts.app')
@section('content')

<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(135deg, #388e3c 0%, #66bb6a 100%); color: white; padding: 80px 0; text-align: center;">
    <div class="container">
        <h1 class="display-4 mb-4">Service Areas</h1>
        <p class="lead">Discover where we provide our healthcare distribution services</p>
    </div>
</section>

<!-- Map Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h2 class="mb-4" style="color: #388e3c;">Interactive Service Map</h2>
                <div id="map" style="height: 500px; width: 100%; border-radius: 10px; border: 2px solid #388e3c;"></div>
            </div>
            <div class="col-md-4">
                <h3 style="color: #388e3c;">Service Areas</h3>
                <div class="list-group">
                    @if($services->count() > 0)
                        @foreach($services->unique('area') as $service)
                            <div class="list-group-item border-0 shadow-sm mb-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $service->area }}</h6>
                                        <small class="text-muted">
                                            @php
                                                $areaServices = $services->where('area', $service->area);
                                            @endphp
                                            {{ $areaServices->count() }} service{{ $areaServices->count() > 1 ? 's' : '' }} available
                                        </small>
                                    </div>
                                    <span class="badge bg-success">{{ ucfirst($service->type) }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted">No service areas available at the moment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Service Details by Area -->
<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <h2 class="text-center mb-5" style="color: #388e3c;">Services by Area</h2>
        
        @if($services->count() > 0)
            @foreach($services->groupBy('area') as $area => $areaServices)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">{{ $area }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($areaServices as $service)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            @if($service->type == 'medicine')
                                                <i class="fas fa-pills fa-2x" style="color: #388e3c;"></i>
                                            @elseif($service->type == 'parapharmacy')
                                                <i class="fas fa-first-aid fa-2x" style="color: #388e3c;"></i>
                                            @else
                                                <i class="fas fa-truck fa-2x" style="color: #388e3c;"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ $service->name }}</h6>
                                            <p class="mb-1 text-muted">{{ $service->description }}</p>
                                            <span class="badge bg-success">{{ ucfirst($service->type) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center">
                <p class="text-muted">No services available at the moment. Please check back later.</p>
            </div>
        @endif
    </div>
</section>

<!-- Contact CTA -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h2 style="color: #388e3c;">Need Our Services?</h2>
                <p class="lead mb-4">Contact us to learn more about our services in your area or to request a quote.</p>
                <a href="{{ route('contact.index') }}" class="btn btn-success btn-lg">Contact Us</a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var map = L.map('map').setView([30.390309704428965, -9.528427448734652], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Liste des régions avec coordonnées
    var regions = [
        { name: 'Agadir', coords: [30.427755, -9.598107] },
        { name: 'Marrakech', coords: [31.629472, -7.981084] },
        { name: 'Dakhla', coords: [23.684774, -15.957976] },
        { name: 'Taroudante', coords: [30.470282, -8.876985] },
        { name: 'Laayoune', coords: [27.153612, -13.203969] }
    ];

    regions.forEach(function(region) {
        L.marker(region.coords).addTo(map)
            .bindPopup(region.name);
    });
});
</script>
@endpush 