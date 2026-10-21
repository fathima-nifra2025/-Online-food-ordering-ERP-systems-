<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Dashboard - Food ERP')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS (CDN for guaranteed load) -->
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
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.2);
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-bg);
            color: #334155;
            margin: 0;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6, .brand-text {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--dark-bg);
            color: #fff;
            z-index: 1040;
            transition: all 0.3s ease;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            overflow-y: auto;
        }
        
        .sidebar-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--secondary) 0%, var(--dark-bg) 100%);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-brand-icon {
            font-size: 1.8rem;
            color: var(--primary);
            text-shadow: 0 0 15px rgba(255,94,58,0.5);
        }

        .nav-link {
            color: #94a3b8;
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 4px solid transparent;
            margin-bottom: 4px;
        }

        .nav-link:hover, .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.05);
            border-left-color: var(--primary);
        }
        
        .nav-link i {
            font-size: 1.2rem;
            opacity: 0.8;
            transition: transform 0.2s;
        }
        
        .nav-link:hover i, .nav-link.active i {
            color: var(--primary);
            opacity: 1;
            transform: scale(1.1);
        }

        /* Main Content */
        #main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
        }

        /* Top Navbar */
        .top-navbar {
            height: 70px;
            background-color: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .page-content {
            padding: 2rem;
            flex: 1;
        }

        /* UI Elements */
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            font-weight: 500;
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255,94,58,0.3);
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #fff;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 0.5rem;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .dropdown-item:hover {
            background-color: #f1f5f9;
            color: var(--primary);
        }

        @media (max-width: 992px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #main-content {
                margin-left: 0;
            }
            .sidebar-open #sidebar {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <i class="bi bi-egg-fried sidebar-brand-icon"></i>
            <h4 class="mb-0 brand-text text-white">FoodERP</h4>
        </div>
        
        <div class="pt-3 pb-5">
            <ul class="nav flex-column">
                <li class="nav-item px-3 mb-2">
                    <small class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem; letter-spacing: 1px;">Core System</small>
                </li>
                
                @if(Auth::user()->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                        <i class="bi bi-receipt"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}" href="{{ route('admin.menu.index') }}">
                        <i class="bi bi-list-stars"></i> Menu Items
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                        <i class="bi bi-tags-fill"></i> Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
                        <i class="bi bi-people-fill"></i> Customers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.deliveries.*') ? 'active' : '' }}" href="{{ route('admin.deliveries.index') }}">
                        <i class="bi bi-truck"></i> Deliveries
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                        <i class="bi bi-graph-up-arrow"></i> Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.sales.history') ? 'active' : '' }}" href="{{ route('admin.sales.history') }}">
                        <i class="bi bi-journal-text"></i> Sales History
                    </a>
                </li>
                @endif
                
                @if(Auth::user()->role === 'restaurant_staff')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}" href="{{ route('staff.dashboard') }}">
                        <i class="bi bi-journal-text"></i> Kitchen Orders
                    </a>
                </li>
                @endif

                @if(Auth::user()->role === 'delivery_staff')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('delivery.dashboard') ? 'active' : '' }}" href="{{ route('delivery.dashboard') }}">
                        <i class="bi bi-bicycle"></i> My Deliveries
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div id="main-content">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h4 class="mb-0 text-dark fw-bold d-none d-md-block">@yield('page_title', 'Dashboard')</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill btn-sm d-none d-sm-block">
                    <i class="bi bi-shop me-1"></i> View Store
                </a>
                
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 40px; height: 40px; font-weight: 600;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="d-none d-md-block text-dark">
                            <div class="fw-bold lh-1">{{ Auth::user()->name }}</div>
                            <small class="text-muted text-capitalize">{{ str_replace('_', ' ', Auth::user()->role) }}</small>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                        <li><h6 class="dropdown-header">Account</h6></li>
                        <li>
                            <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i> {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="page-content bg-light">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.body.classList.toggle('sidebar-open');
        });
    </script>
    @stack('scripts')
</body>
</html>
