@extends('layouts.app')
@section('content')

<!-- Hero Section -->
<section class="hero-section" style="background: linear-gradient(135deg, #388e3c 0%, #66bb6a 100%); color: white; padding: 80px 0; text-align: center;">
    <div class="container">
        <h1 class="display-4 mb-4">About Our Company</h1>
        <p class="lead">Dedicated to excellence in healthcare distribution and logistics</p>
    </div>
</section>

<!-- History Section -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="mb-4" style="color: #388e3c;">Our History</h2>
                <p class="lead">Founded with a vision to revolutionize healthcare distribution, our company has been at the forefront of pharmaceutical logistics for over two decades.</p>
                <p>We started as a small local distributor and have grown into a trusted partner for healthcare providers across the region. Our commitment to quality, reliability, and innovation has been the cornerstone of our success.</p>
                <p>Today, we serve hundreds of pharmacies, hospitals, and healthcare facilities, ensuring that essential medicines and medical supplies reach those who need them most.</p>
            </div>
            <div class="col-md-6">
                <img src="{{asset('assets/images/growth.jpg')}}" alt="Company History" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

<!-- Mission & Values Section -->
<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-bullseye fa-3x" style="color: #388e3c;"></i>
                        </div>
                        <h3 style="color: #388e3c;">Our Mission</h3>
                        <p>To provide reliable, efficient, and innovative healthcare distribution solutions that improve patient care and support healthcare providers in delivering exceptional service to their communities.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-heart fa-3x" style="color: #388e3c;"></i>
                        </div>
                        <h3 style="color: #388e3c;">Our Values</h3>
                        <ul class="list-unstyled">
                            <li class="mb-2"><strong>Integrity:</strong> Honest and transparent in all our dealings</li>
                            <li class="mb-2"><strong>Excellence:</strong> Striving for the highest quality in everything we do</li>
                            <li class="mb-2"><strong>Innovation:</strong> Embracing new technologies and solutions</li>
                            <li class="mb-2"><strong>Service:</strong> Putting our customers' needs first</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5" style="color: #388e3c;">Our Leadership Team</h2>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body p-4">
                        <img src="https://via.placeholder.com/200x200/4caf50/ffffff?text=CEO" alt="CEO" class="rounded-circle mb-3" style="width: 150px; height: 150px;">
                        <h4 style="color: #388e3c;">John Smith</h4>
                        <p class="text-muted">Chief Executive Officer</p>
                        <p>With over 20 years of experience in healthcare distribution, John leads our company with vision and dedication.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body p-4">
                        <img src="https://via.placeholder.com/200x200/4caf50/ffffff?text=CTO" alt="CTO" class="rounded-circle mb-3" style="width: 150px; height: 150px;">
                        <h4 style="color: #388e3c;">Sarah Johnson</h4>
                        <p class="text-muted">Chief Technology Officer</p>
                        <p>Sarah drives our technological innovation, ensuring we stay ahead in digital solutions and automation.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-center border-0 shadow-sm">
                    <div class="card-body p-4">
                        <img src="https://via.placeholder.com/200x200/4caf50/ffffff?text=COO" alt="COO" class="rounded-circle mb-3" style="width: 150px; height: 150px;">
                        <h4 style="color: #388e3c;">Michael Brown</h4>
                        <p class="text-muted">Chief Operations Officer</p>
                        <p>Michael oversees our day-to-day operations, ensuring efficiency and quality across all our services.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection 