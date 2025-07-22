<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SOREMED</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="stylesite.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <link rel="preconnect" href="https://fonts.gstatic.com/">
    <link
        href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Allura&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/css/plugins/swiper.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
        integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
  

    @stack("styles")
</head>

<body>

  <header>
    <!-- SVG symboles -->
    <svg style="display: none;">
      <symbol id="icon_search" viewBox="0 0 20 20">
        <g clip-path="url(#clip0_6_7)">
          <path d="M8.80758 0C3.95121 0 0 3.95121 0 8.80758C0 13.6642 3.95121 17.6152 8.80758 17.6152C13.6642 17.6152 17.6152 13.6642 17.6152 8.80758C17.6152 3.95121 13.6642 0 8.80758 0ZM8.80758 15.9892C4.84769 15.9892 1.62602 12.7675 1.62602 8.80762C1.62602 4.84773 4.84769 1.62602 8.80758 1.62602C12.7675 1.62602 15.9891 4.84769 15.9891 8.80758C15.9891 12.7675 12.7675 15.9892 8.80758 15.9892Z" fill="currentColor" />
          <path d="M19.7618 18.6122L15.1006 13.9509C14.783 13.6333 14.2686 13.6333 13.951 13.9509C13.6334 14.2683 13.6334 14.7832 13.951 15.1005L18.6122 19.7618C18.771 19.9206 18.9789 20 19.187 20C19.3949 20 19.603 19.9206 19.7618 19.7618C20.0795 19.4444 20.0795 18.9295 19.7618 18.6122Z" fill="currentColor" />
        </g>
        <defs>
          <clipPath id="clip0_6_7">
            <rect width="20" height="20" fill="White" />
          </clipPath>
        </defs>
      </symbol>
    </svg>

   
  <header id="header" class="header header-fullwidth header-transparent-bg ">
    <div class="container mx-auto px-4 py-2 flex items-center justify-between" style="background: transparent; min-height: 56px;">
      <!-- Logo à gauche -->
      <div class="flex items-center flex-shrink-0">
        <img src="{{asset('assets/images/logo.jpg')}}" alt="SOREMED Logo" class="h-12" style="margin-left:0; padding-left:0;">
      </div>
      <!-- Liens de navigation alignés à droite -->
      <nav class="navbar hidden md:flex flex-1 justify-end" style="margin-left: 80px;">
        <ul class="nav-links" style="gap: 32px;">
          <li>
            <a href="{{route('home')}}" class="navigation__link text-green-800 hover:text-green-600 font-medium">Home</a>
          </li>
          <li>
            <a href="{{route('about')}}" class="navigation__link">About</a>
          </li>
          <li>
            <a href="{{route('products-services')}}" class="navigation__link">Services</a>
          </li>
          <li>
            <a href="{{route('service-areas')}}" class="navigation__link">Areas</a>
          </li>
          <li>
                             <a href="{{route('news.index')}}" class="navigation__link">News</a>
          </li>
          <li>
            <a href="{{route('contact.index')}}" class="navigation__link">Contact</a>
          </li>
          @guest
            <li>
              <a class="navigation__link" href="{{ route('login') }}" style="font-weight:600;">Connexion</a>
            </li>
          @else
            <li class="nav-item dropdown">
              <a class="navigation__link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  {{ Auth::user()->name }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                  <li>
                    @php
                      $dashboardRoute = route('user.dashboard');
                      if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super admin')) {
                          $dashboardRoute = route('admin.index');
                      } elseif (Auth::user()->hasRole('manager')) {
                          $dashboardRoute = route('manager.dashboard');
                      } elseif (Auth::user()->hasRole('editor')) {
                          $dashboardRoute = route('editor.dashboard');
                      } elseif (Auth::user()->hasRole('viewer')) {
                          $dashboardRoute = route('viewer.dashboard');
                      }
                    @endphp
                    <a class="dropdown-item" href="{{ $dashboardRoute }}">
                        <i class="fas fa-tachometer-alt me-2"></i>Tableau de bord
                    </a>
                  </li>
                  <li><hr class="dropdown-divider"></li>
                  <li>
                      <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                          <i class="fas fa-sign-out-alt me-2"></i>Déconnexion
                      </a>
                      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                          @csrf
                      </form>
                  </li>
              </ul>
            </li>
          @endguest
        </ul>
      </nav>
    </div>
    <script>
  document.addEventListener("DOMContentLoaded", function () {
    const toggleBtn = document.querySelector(".js-search-toggle");
    const searchBar = document.querySelector("#searchBar");

    toggleBtn.addEventListener("click", function (e) {
      e.preventDefault();
      searchBar.classList.toggle("active");
    });
  });
</script>
</header>
  <style>
    #header {
      padding-top: 4px;
      padding-bottom: 4px;
      background: transparent !important;
      backdrop-filter: none;
      border-bottom: 1px solid rgba(0,0,0,0.1);
      min-height: 56px;
    }
    .header-fullwidth .container {
      max-width: 100vw;
      padding-left: 0;
      padding-right: 0;
    }
    .logo__image, .h-12 {
      max-width: 120px;
      height: auto;
      margin-left: 0;
      padding-left: 0;
    }
    .navbar {
      display: flex;
      align-items: center;
      background: transparent;
      position: relative;
      width: auto;
      z-index: 1000;
      margin: 0;
      padding: 0;
      gap: 48px;
      flex: 1;
      justify-content: flex-end;
    }
    .nav-links {
      list-style: none;
      display: flex;
      gap: 32px;
      padding: 0px;
      margin: 0px;
      align-items: center;
      justify-content: flex-end;
    }
    .nav-links li a, .nav-links li .navigation__link {
      text-decoration: none;
      color: #2e7d32;
      font-weight: 600;
      font-size: 15px;
      padding: 10px 18px;
      border-radius: 8px;
      transition: all 0.3s cubic-bezier(.4,0,.2,1);
      position: relative;
      background: rgba(46, 125, 50, 0.05);
      display: inline-block;
    }
    .nav-links li a:hover,.nav-links li a.active, .nav-links li .navigation__link:hover {
      color: #fff;
      background: linear-gradient(90deg, #2e7d32, #4caf50);
      transform: translateY(-2px) scale(1.05);
      box-shadow: 0 2px 8px rgba(46,125,50,0.08);
    }
    .nav-links li a.active::after,.nav-links li a:hover::after {
      content: "";
      width: 100%;
      height: 3px;
      background: linear-gradient(90deg, #2e7d32, #4caf50);
      position: absolute;
      bottom: 0; 
      left: 0;
      border-radius: 2px;
    }
    @media (max-width: 900px) {
      .navbar, .nav-links {
        gap: 16px !important;
      }
      .h-12 {
        max-width: 120px;
      }
    }
  </style>


  @yield('content')

  <hr class="mt-5 text-secondary" />
  <footer class="footer footer_type_2" style="background-color: #2e7d32; color: white; padding: 60px 0 30px 0;">
    <div class="footer-middle container">
      <div class="row row-cols-lg-4 row-cols-2">
        <div class="footer-column footer-store-info col-12 mb-5 mb-lg-0">
          <div class="logo">
            <a href="{{route('home')}}">
              <img src="{{asset('assets/images/logo.jpg')}}" alt="Healthcare Distribution" class="logo__image d-block" />
            </a>
          </div>
          <p class="footer-address"> Zone industrielle Tassila, 75001 Agadir, Maroc</p>
          <p class="m-0"><strong class="fw-medium">soremedsupport@gmail.com</strong></p>
          <p><strong class="fw-medium">+33 1 23 45 67 89</strong></p>

          <ul class="social-links list-unstyled d-flex flex-wrap mb-0">
            <li>
              <a href="#" class="footer__social-link d-block text-white">
                <i class="fab fa-facebook-f"></i>
              </a>
            </li>
            <li>
              <a href="#" class="footer__social-link d-block text-white">
                <i class="fab fa-twitter"></i>
              </a>
            </li>
            <li>
              <a href="#" class="footer__social-link d-block text-white">
                <i class="fab fa-linkedin-in"></i>
              </a>
            </li>
            <li>
              <a href="#" class="footer__social-link d-block text-white">
                <i class="fab fa-instagram"></i>
              </a>
            </li>
          </ul>
        </div>

        <div class="footer-column footer-menu mb-5 mb-lg-0">
          <h6 class="sub-menu__title text-uppercase text-white mb-4">Company</h6>
          <ul class="sub-menu__list list-unstyled">
            <li class="sub-menu__item mb-2"><a href="{{ route('about') }}" class="menu-link menu-link_us-s text-white-50">About Us</a></li>
            <li class="sub-menu__item mb-2"><a href="{{ route('news.index') }}" class="menu-link menu-link_us-s text-white-50">News</a></li>
            <li class="sub-menu__item mb-2"><a href="{{ route('contact.index') }}" class="menu-link menu-link_us-s text-white-50">Contact Us</a></li>
            <li class="sub-menu__item mb-2"><a href="#" class="menu-link menu-link_us-s text-white-50">Careers</a></li>
            <li class="sub-menu__item mb-2"><a href="#" class="menu-link menu-link_us-s text-white-50">Partners</a></li>
          </ul>
        </div>

        <div class="footer-column footer-menu mb-5 mb-lg-0">
          <h6 class="sub-menu__title text-uppercase text-white mb-4">Services</h6>
          <ul class="sub-menu__list list-unstyled">
            <li class="sub-menu__item mb-2"><a href="{{ route('products-services') }}" class="menu-link menu-link_us-s text-white-50">Products & Services</a></li>
            <li class="sub-menu__item mb-2"><a href="{{ route('service-areas') }}" class="menu-link menu-link_us-s text-white-50">Service Areas</a></li>
            <li class="sub-menu__item mb-2"><a href="#" class="menu-link menu-link_us-s text-white-50">Medicine Distribution</a></li>
            <li class="sub-menu__item mb-2"><a href="#" class="menu-link menu-link_us-s text-white-50">Parapharmacy</a></li>
            <li class="sub-menu__item mb-2"><a href="#" class="menu-link menu-link_us-s text-white-50">Logistics</a></li>
          </ul>
        </div>

        <div class="footer-column footer-menu mb-5 mb-lg-0">
          <h6 class="sub-menu__title text-uppercase text-white mb-4">Support</h6>
          <ul class="sub-menu__list list-unstyled">
            <li class="sub-menu__item mb-2"><a href="{{ route('contact.index') }}" class="menu-link menu-link_us-s text-white-50">Customer Service</a></li>
            <li class="sub-menu__item mb-2"><a href="#" class="menu-link menu-link_us-s text-white-50">Emergency Contact</a></li>
            <li class="sub-menu__item mb-2"><a href="#" class="menu-link menu-link_us-s text-white-50">Quality Assurance</a></li>
            <li class="sub-menu__item mb-2"><a href="#" class="menu-link menu-link_us-s text-white-50">Privacy Policy</a></li>
            <li class="sub-menu__item mb-2"><a href="#" class="menu-link menu-link_us-s text-white-50">Terms & Conditions</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="footer-bottom" style="background-color: #1b5e20; padding: 20px 0;">
      <div class="container d-md-flex align-items-center">
        <span class="footer-copyright me-auto text-white">©2024 SOREMED. All rights reserved.</span>
        <div class="footer-settings d-md-flex align-items-center">
          <a href="#" class="text-white-50">Privacy Policy</a> &nbsp;|&nbsp; <a href="#" class="text-white-50">Terms &amp; Conditions</a>
        </div>
      </div>
    </div>
  </footer>


  <footer class="footer-mobile container w-100 px-5 d-md-none bg-body">
    <div class="row text-center">
      <div class="col-4">
        <a href="{{route('home')}}" class="footer-mobile__link d-flex flex-column align-items-center">
          <svg class="d-block" width="18" height="18" viewBox="0 0 18 18" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <use href="#icon_home" />
          </svg>
          <span>Home</span>
        </a>
      </div>

      <div class="col-4">
        <a href="{{route('products-services')}}" class="footer-mobile__link d-flex flex-column align-items-center">
          <svg class="d-block" width="18" height="18" viewBox="0 0 18 18" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <use href="#icon_hanger" />
          </svg>
          <span>Services</span>
        </a>
      </div>

      <div class="col-4">
        <a href="{{route('contact.index')}}" class="footer-mobile__link d-flex flex-column align-items-center">
          <svg class="d-block" width="18" height="18" viewBox="0 0 18 18" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <use href="#icon_user" />
          </svg>
          <span>Contact</span>
        </a>
      </div>
    </div>
  </footer>

  <div id="scrollTop" class="visually-hidden end-0"></div>
  <div class="page-overlay"></div>

  <script src="{{asset('assets/js/plugins/jquery.min.js')}}"></script>
  <script src="{{asset('assets/js/plugins/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/js/plugins/bootstrap-slider.min.js')}}"></script>
  <script src="{{asset('assets/js/plugins/swiper.min.js')}}"></script>
  <script src="{{asset('assets/js/plugins/countdown.js')}}"></script>
  <script src="{{asset('assets/js/theme.js')}}"></script>
  @stack('scripts')
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
