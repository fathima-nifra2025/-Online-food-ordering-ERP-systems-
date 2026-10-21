<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Food ERP System')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #ff5e3a;
            --primary-hover: #ff471a;
            --secondary: #1e293b;
            --dark-bg: #0f172a;
            --light-bg: #f8fafc;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: #334155;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-size: 1.5rem;
            color: var(--dark-bg) !important;
        }
        
        .navbar-brand i {
            color: var(--primary);
        }

        .nav-link {
            font-weight: 500;
            color: #475569 !important;
            transition: all 0.2s;
            margin: 0 0.5rem;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary) !important;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            font-weight: 500;
            border-radius: 50rem;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255,94,58,0.3);
        }
        
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
            font-weight: 500;
            border-radius: 50rem;
            transition: all 0.3s;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #fff;
            transform: translateY(-2px);
        }

        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-weight: 600;
            font-size: 1.2rem;
            padding: 1.5rem;
            font-family: 'Outfit', sans-serif;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            border-color: #e2e8f0;
            background-color: #f8fafc;
            transition: all 0.2s;
        }
        
        .form-control:focus {
            background-color: #fff;
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(255,94,58,0.25);
        }

        main {
            flex: 1;
            padding: 3rem 0;
        }

        footer {
            background-color: var(--dark-bg);
            color: #94a3b8;
            padding: 2rem 0;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light sticky-top">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                    <i class="bi bi-egg-fried fs-3"></i>
                    <span>FoodERP</span>
                </a>
                <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('menu.index') ? 'active' : '' }}" href="{{ route('menu.index') }}">Menu</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item me-3">
                            <a href="{{ route('cart.index') }}" class="btn btn-outline-dark btn-sm position-relative rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-cart3 fs-5"></i>
                                @php $cartCount = collect(session()->get('cart', []))->sum('quantity'); @endphp
                                @if($cartCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                        
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-primary ms-2" href="{{ route('register') }}">{{ __('Sign Up') }}</a>
                                </li>
                            @endif
                        @else
                            {{-- Logged-in: show user info + role shortcut + logout --}}
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown"
                                   class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                                   href="#" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm fw-bold"
                                         style="width:34px;height:34px;font-size:.9rem;">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <div class="d-none d-md-block lh-1">
                                        <div class="fw-semibold text-dark" style="font-size:.88rem;">{{ Auth::user()->name }}</div>
                                        <small class="text-muted text-capitalize" style="font-size:.74rem;">
                                            {{ str_replace('_',' ', Auth::user()->role) }}
                                        </small>
                                    </div>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2 py-2"
                                     aria-labelledby="navbarDropdown"
                                     style="min-width:220px;">

                                    {{-- Role-based dashboard link --}}
                                    @if(Auth::user()->role === 'admin')
                                        <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2 text-primary"></i> Admin Dashboard
                                        </a>
                                    @elseif(Auth::user()->role === 'restaurant_staff')
                                        <a class="dropdown-item py-2" href="{{ route('staff.dashboard') }}">
                                            <i class="bi bi-journal-text me-2 text-warning"></i> Kitchen Dashboard
                                        </a>
                                    @elseif(Auth::user()->role === 'delivery_staff')
                                        <a class="dropdown-item py-2" href="{{ route('delivery.dashboard') }}">
                                            <i class="bi bi-bicycle me-2 text-info"></i> Delivery Dashboard
                                        </a>
                                    @else
                                        <a class="dropdown-item py-2" href="{{ route('customer.orders') }}">
                                            <i class="bi bi-receipt me-2 text-success"></i> My Orders
                                        </a>
                                    @endif

                                    <div class="dropdown-divider my-1"></div>

                                    {{-- ✅ Logout item --}}
                                    <a class="dropdown-item py-2 text-danger fw-semibold"
                                       href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form-nav').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </a>

                                    <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>

                            {{-- ✅ Visible "Logout" button directly in navbar (always visible, no click needed) --}}
                            <li class="nav-item ms-2">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-semibold d-flex align-items-center gap-1"
                                            title="Logout">
                                        <i class="bi bi-power"></i>
                                        <span class="d-none d-lg-inline">Logout</span>
                                    </button>
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            
            @yield('content')
        </main>
        
        <footer>
            <div class="container text-center">
                <p class="mb-0">&copy; {{ date('Y') }} FoodERP System. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
