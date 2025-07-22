@extends('layouts.app')
@section('content')

<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(135deg, #388e3c 0%, #66bb6a 100%); color: white; padding: 80px 0; text-align: center;">
    <div class="container">
        <h1 class="display-4 mb-4">Contact Us</h1>
        <p class="lead">Get in touch with us for any questions or inquiries about our services</p>
    </div>
</section>

<!-- Contact Form & Info Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <!-- Contact Form -->
            <div class="col-md-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h3 class="mb-0">Send us a Message</h3>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subject *</label>
                                <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message *</label>
                                <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success btn-lg">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Contact Information</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="d-flex align-items-start mb-3">
                                <div class="me-3">
                                    <i class="fas fa-map-marker-alt fa-2x" style="color: #388e3c;"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Address</h6>
                                    <p class="mb-0 text-muted">Zone indistruelle Tassila <br>N 176,Inzegane <br> Agadir</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mb-3">
                                <div class="me-3">
                                    <i class="fas fa-phone fa-2x" style="color: #388e3c;"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Phone</h6>
                                    <p class="mb-0 text-muted">+212 5 28 33 22 66</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mb-3">
                                <div class="me-3">
                                    <i class="fas fa-envelope fa-2x" style="color: #388e3c;"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Email</h6>
                                    <p class="mb-0 text-muted">soremedsupport@gmail.com</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <i class="fas fa-clock fa-2x" style="color: #388e3c;"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Business Hours</h6>
                                    <p class="mb-0 text-muted">Monday - Friday: 9:00 AM - 7:00 PM<br>Saturday: 9:00 AM - 2:00 PM<br>Sunday: Closed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Quick Links</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="list-group list-group-flush">
                            <a href="{{ route('services.index') }}" class="list-group-item list-group-item-action border-0">
                                <i class="fas fa-arrow-right me-2" style="color: #388e3c;"></i>Our Services
                            </a>
                            <a href="{{ route('service-areas') }}" class="list-group-item list-group-item-action border-0">
                                <i class="fas fa-arrow-right me-2" style="color: #388e3c;"></i>Service Areas
                            </a>
                            <a href="{{ route('news.index') }}" class="list-group-item list-group-item-action border-0">
                                <i class="fas fa-arrow-right me-2" style="color: #388e3c;"></i>Latest News
                            </a>
                            <a href="{{ route('about') }}" class="list-group-item list-group-item-action border-0">
                                <i class="fas fa-arrow-right me-2" style="color: #388e3c;"></i>About Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <h2 class="text-center mb-5" style="color: #388e3c;">Find Us</h2>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div id="contactMap" style="height: 400px; width: 100%; border-radius: 10px; border: 2px solid #388e3c;"></div>
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
    var map = L.map('contactMap').setView([30.390309704428965, -9.528427448734652], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    L.marker([30.390309704428965, -9.528427448734652]).addTo(map)
        .bindPopup('SOREMED - Contact principal')
        .openPopup();
});
</script>
@endpush 