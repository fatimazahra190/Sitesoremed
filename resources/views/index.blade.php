@extends('layouts.app')
@section('content')

    <!-- Hero Section -->
    <section class="hero-section position-relative" style="min-height: 80vh; background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1587854692152-cbe660dbde88?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80') center center/cover no-repeat;">
        <div class="container h-100 position-relative" style="z-index: 2;">
            <div class="row h-100 align-items-center">
                <div class="col-lg-8 col-md-10 mx-auto text-center text-white">
                    <h1 class="display-3 fw-bold mb-4">Votre Partenaire de Confiance en Distribution Pharmaceutique</h1>
                    <p class="lead mb-5 fs-4">SOREMED assure la livraison rapide et sécurisée de médicaments aux pharmacies et établissements de santé, soutenant les soins aux patients avec efficacité et fiabilité.</p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="{{ route('contact.index') }}" class="btn btn-success btn-lg px-5 py-3 fw-bold" style="background-color: #28a745; color: black;">Nous Contacter</a>
                        <a href="{{ route('products-services') }}" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold">Nos Services</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <h2 class="display-5 fw-bold mb-4" style="color: #2e7d32;">À Propos de SOREMED</h2>
                    <p class="lead mb-4">Leader marocain de la distribution pharmaceutique, nous nous engageons à fournir des solutions fiables, efficaces et innovantes pour les professionnels de santé.</p>
                    <p class="mb-4">Avec plus de deux décennies d'expérience dans la logistique pharmaceutique, nous comprenons l'importance critique de la livraison rapide et sécurisée des produits de santé. Notre engagement envers la qualité, la conformité et le service client a fait de nous un partenaire de confiance pour les pharmacies, hôpitaux et établissements de santé à travers le Maroc.</p>
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Livraison rapide</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Qualité garantie</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Support 24/7</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span>Couverture nationale</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('about') }}" class="btn btn-success btn-lg">En Savoir Plus</a>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative">
                        <img src="https://images.unsplash.com/photo-1587854692152-cbe660dbde88?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80" alt="Distribution Pharmaceutique" class="img-fluid rounded-3 shadow-lg">
                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-success opacity-10 rounded-3" style="transform: translate(10px, 10px); z-index: -1;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Key Figures Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12">
                    <div class="text-center p-5 rounded-4" style="background: linear-gradient(135deg, #2e7d32 0%, #388e3c 100%); color: white; box-shadow: 0 10px 30px rgba(46, 125, 50, 0.3);">
                        <h2 class="text-center display-5 fw-bold mb-5">Chiffres Clés</h2>
                        <div class="row text-center g-4">
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="card border-0 bg-transparent text-white h-100">
                                    <div class="card-body p-4">
                                        <div class="mb-3">
                                            <i class="fas fa-truck fa-4x text-warning"></i>
                                        </div>
                                        <h3 class="display-6 fw-bold mb-2">500+</h3>
                                        <p class="fs-5 mb-0">Livraisons Quotidiennes</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="card border-0 bg-transparent text-white h-100">
                                    <div class="card-body p-4">
                                        <div class="mb-3">
                                            <i class="fas fa-hospital fa-4x text-warning"></i>
                                        </div>
                                        <h3 class="display-6 fw-bold mb-2">200+</h3>
                                        <p class="fs-5 mb-0">Partenaires de Santé</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="card border-0 bg-transparent text-white h-100">
                                    <div class="card-body p-4">
                                        <div class="mb-3">
                                            <i class="fas fa-map-marker-alt fa-4x text-warning"></i>
                                        </div>
                                        <h3 class="display-6 fw-bold mb-2">15+</h3>
                                        <p class="fs-5 mb-0">Zones de Service</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="card border-0 bg-transparent text-white h-100">
                                    <div class="card-body p-4">
                                        <div class="mb-3">
                                            <i class="fas fa-clock fa-4x text-warning"></i>
                                        </div>
                                        <h3 class="display-6 fw-bold mb-2">24/7</h3>
                                        <p class="fs-5 mb-0">Support Disponible</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-12"> <!-- Changed from col-lg-1 to col-lg-12 -->
                    <div class="bg-light p-5 rounded-4 shadow-lg">
                        <div class="row justify-content-center mb-5">
                            <div class="col-lg-8 text-center">
                                <h2 class="display-5 fw-bold mb-4" style="color: #2e7d32;">Nos Services</h2>
                                <p class="lead">Nous offrons une gamme complète de services de distribution pharmaceutique pour répondre à tous vos besoins.</p>
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-lg text-center hover-lift">
                                    <div class="card-body p-5">
                                        <div class="mb-4">
                                            <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background: rgba(46, 125, 50, 0.2) !important;">
                                                <i class="fas fa-pills fa-3x text-success"></i>
                                            </div>
                                        </div>
                                        <h4 class="fw-bold mb-3" style="color: #2e7d32;">Médicaments</h4>
                                        <p class="text-dark mb-4">Distribution pharmaceutique complète incluant les médicaments sur ordonnance, les médicaments en vente libre et les spécialités pharmaceutiques.</p>
                                        <a href="{{ route('products-services') }}" class="btn btn-success fw-bold">En Savoir Plus</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-lg text-center hover-lift">
                                    <div class="card-body p-5">
                                        <div class="mb-4">
                                            <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background: rgba(46, 125, 50, 0.2) !important;">
                                                <i class="fas fa-first-aid fa-3x text-success"></i>
                                            </div>
                                        </div>
                                        <h4 class="fw-bold mb-3" style="color: #2e7d32;">Parapharmacie</h4>
                                        <p class="text-dark mb-4">Gamme complète de produits de santé et de bien-être incluant les dispositifs médicaux, les produits de soins personnels et les compléments alimentaires.</p>
                                        <a href="{{ route('products-services') }}" class="btn btn-success fw-bold">En Savoir Plus</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100 border-0 shadow-lg text-center hover-lift">
                                    <div class="card-body p-5">
                                        <div class="mb-4">
                                            <div class="bg-success rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background: rgba(46, 125, 50, 0.2) !important;">
                                                <i class="fas fa-truck fa-3x text-success"></i>
                                            </div>
                                        </div>
                                        <h4 class="fw-bold mb-3" style="color: #2e7d32;">Logistique</h4>
                                        <p class="text-dark mb-4">Services professionnels de logistique et de gestion de la chaîne d'approvisionnement assurant la livraison rapide et sécurisée des produits de santé.</p>
                                        <a href="{{ route('products-services') }}" class="btn btn-success fw-bold">En Savoir Plus</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-md-10">
                    <div class="text-center p-5 rounded-4" style="background: linear-gradient(135deg, #2e7d32 0%, #388e3c 100%); color: white; box-shadow: 0 10px 30px rgba(46, 125, 50, 0.3);">
                        <h2 class="display-5 fw-bold mb-4">Prêt à Commencer ?</h2>
                        <p class="lead mb-5">Contactez-nous aujourd'hui pour en savoir plus sur nos services et comment nous pouvons aider votre établissement de santé.</p>
                        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                            <a href="{{ route('contact.index') }}" class="btn btn-light btn-lg px-5 py-3 fw-bold">Nous Contacter</a>
                            <a href="{{ route('service-areas') }}" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold">Zones de Service</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .hero-section {
            position: relative;
            overflow: hidden;
        }
        
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
        }
        
        .card {
            border-radius: 15px;
        }
        
        .btn {
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .btn:hover {
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .hero-section {
                min-height: 60vh;
            }
            
            .display-3 {
                font-size: 2.5rem;
            }
            
            .display-5 {
                font-size: 2rem;
            }
        }
    </style>

@endsection