@extends('layouts.app')
@section('content')

<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(135deg, #388e3c 0%, #66bb6a 100%); color: white; padding: 80px 0; text-align: center;">
    <div class="container">
        <h1 class="display-4 mb-4">Products & Services</h1>
        <p class="lead">Comprehensive healthcare solutions for professionals and institutions</p>
    </div>
</section>

<!-- Services Overview -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 style="color: #388e3c;">Our Service Categories</h2>
                <p class="lead">We offer three main categories of services to meet all your healthcare distribution needs</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm text-center" style="background: #fff;">
                    <div class="card-body p-4">
                        <div class="mb-3 rounded-circle d-flex align-items-center justify-content-center" style="background: #fff; width: 90px; height: 90px; margin: 0 auto; border: 2px solid #43a047;">
                            <i class="fas fa-pills fa-3x" style="color: #43a047;"></i>
                        </div>
                        <h3 style="color: #388e3c;">Medicines</h3>
                        <p>Comprehensive pharmaceutical distribution including prescription drugs, over-the-counter medications, and specialty pharmaceuticals.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm text-center" style="background: #fff;">
                    <div class="card-body p-4">
                        <div class="mb-3 rounded-circle d-flex align-items-center justify-content-center" style="background: #fff; width: 90px; height: 90px; margin: 0 auto; border: 2px solid #43a047;">
                            <i class="fas fa-first-aid fa-3x" style="color: #43a047;"></i>
                        </div>
                        <h3 style="color: #388e3c;">Parapharmacy</h3>
                        <p>Complete range of health and wellness products including medical devices, personal care items, and health supplements.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm text-center" style="background: #fff;">
                    <div class="card-body p-4">
                        <div class="mb-3 rounded-circle d-flex align-items-center justify-content-center" style="background: #fff; width: 90px; height: 90px; margin: 0 auto; border: 2px solid #43a047;">
                            <i class="fas fa-truck fa-3x" style="color: #43a047;"></i>
                        </div>
                        <h3 style="color: #388e3c;">Logistics</h3>
                        <p>Professional logistics and supply chain management services ensuring timely and secure delivery of healthcare products.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Detailed Services -->
<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <h2 class="text-center mb-5" style="color: #388e3c;">Our Services</h2>
        
        @if($services->count() > 0)
            <div class="row">
                @foreach($services as $service)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title mb-0" style="color: #388e3c;">{{ $service->name }}</h5>
                                    <span class="badge bg-success">{{ ucfirst($service->type) }}</span>
                                </div>
                                <p class="card-text">{{ $service->description }}</p>
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i> {{ $service->area }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center">
                <p class="text-muted">No services available at the moment. Please check back later.</p>
            </div>
        @endif
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 style="color: #388e3c;">Why Choose Our Services?</h2>
                <div class="row">
                    <div class="col-12 mb-3">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-check-circle fa-2x" style="color: #388e3c;"></i>
                            </div>
                            <div>
                                <h5>Quality Assurance</h5>
                                <p>All products meet the highest quality standards and regulatory requirements.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-clock fa-2x" style="color: #388e3c;"></i>
                            </div>
                            <div>
                                <h5>Fast Delivery</h5>
                                <p>Quick and reliable delivery to ensure your products arrive when you need them.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-headset fa-2x" style="color: #388e3c;"></i>
                            </div>
                            <div>
                                <h5>24/7 Support</h5>
                                <p>Round-the-clock customer support to assist you with any questions or concerns.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <img src="{{asset('assets/images/quality.png')}}" alt="Quality Service" class="img-fluid rounded" style="max-width: 400px; height: auto;">
            </div>
        </div>
    </div>
</section>

@endsection 