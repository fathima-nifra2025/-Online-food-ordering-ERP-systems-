@extends('layouts.app')

@section('title', 'Food Menu - Online Food Ordering ERP')

@section('content')
<!-- Hero Section -->
<div class="container mb-5">
    <div class="p-4 p-md-5 rounded-4 text-white shadow-lg position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #ff5e3a 100%);">
        <div class="row align-items-center position-relative z-1 py-3">
            <div class="col-lg-7">
                <span class="badge bg-danger rounded-pill px-3 py-2 text-uppercase mb-3 fw-bold tracking-wider">Fast &amp; Fresh Delivery</span>
                <h1 class="display-5 fw-extrabold mb-3">Craving Something Extraordinary?</h1>
                <p class="lead opacity-90 mb-4">Explore our artisan menu crafted by master chefs. From flame-grilled steaks to gourmet pizzas, delivered piping hot to your door.</p>
                
                <!-- Search Bar -->
                <form action="{{ route('menu.index') }}" method="GET" class="row g-2">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    <div class="col-md-9">
                        <div class="input-group input-group-lg bg-white rounded-pill shadow-sm p-1">
                            <span class="input-group-text bg-transparent border-0 text-muted ps-3"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" class="form-control border-0 bg-transparent shadow-none" placeholder="Search dishes, burgers, pizza..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-gradient btn-lg rounded-pill w-100 h-100 shadow-sm fw-bold">Search</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <i class="bi bi-egg-fried display-1 text-white opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<div class="container mb-5">
    <!-- Category Filter Pills -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">Our Menu Categories</h4>
            <p class="text-muted small mb-0">Select a category to filter your favorite food items</p>
        </div>
        <div class="d-flex align-items-center gap-2 overflow-auto py-2">
            <a href="{{ route('menu.index') }}" class="btn {{ !request('category') ? 'btn-danger shadow-sm' : 'btn-outline-secondary' }} rounded-pill px-3 py-2 fw-semibold text-nowrap">
                <i class="bi bi-grid me-1"></i> All Items
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('menu.index', ['category' => $cat->slug]) }}" class="btn {{ request('category') == $cat->slug ? 'btn-danger shadow-sm' : 'btn-outline-secondary' }} rounded-pill px-3 py-2 fw-semibold text-nowrap">
                    {{ $cat->name }} <span class="badge bg-light text-dark rounded-pill ms-1">{{ $cat->menu_items_count }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Food Cards Grid -->
    <div class="row g-4">
        @forelse($menuItems as $item)
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card food-card h-100 position-relative">
                    @if($item->is_featured)
                        <span class="position-absolute top-0 start-0 m-3 badge bg-danger rounded-pill px-3 py-2 shadow-sm">
                            <i class="bi bi-star-fill me-1"></i> Chef's Choice
                        </span>
                    @endif

                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->name }}">
                    @else
                        <div class="bg-gradient text-white d-flex align-items-center justify-content-center" style="height: 200px; background: linear-gradient(135deg, #1e293b 0%, #334155 100%);">
                            <i class="bi bi-egg-fried display-3 opacity-50"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge-category">{{ $item->category->name }}</span>
                            <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $item->prep_time_minutes }} mins</small>
                        </div>

                        <h5 class="card-title fw-bold text-dark mb-2">{{ $item->name }}</h5>
                        <p class="card-text text-muted small flex-grow-1 mb-3">{{ Str::limit($item->description, 75) }}</p>

                        <div class="d-flex align-items-center justify-content-between mt-auto pt-3 border-top">
                            <div>
                                <span class="small text-muted d-block">Price</span>
                                <span class="fs-4 fw-extrabold text-danger">${{ number_format($item->price, 2) }}</span>
                            </div>
                            
                            <form action="{{ route('cart.add', $item->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-gradient rounded-pill px-3 py-2 shadow-sm fw-semibold d-flex align-items-center gap-1">
                                    <i class="bi bi-plus-circle-fill fs-6"></i> Add
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="p-5 bg-white rounded-4 shadow-sm">
                    <i class="bi bi-emoji-frown display-1 text-muted opacity-50 mb-3 d-block"></i>
                    <h4 class="fw-bold text-dark">No food items found</h4>
                    <p class="text-muted">We couldn't find any dishes matching your current filter criteria.</p>
                    <a href="{{ route('menu.index') }}" class="btn btn-gradient rounded-pill px-4">Reset Menu Filters</a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-5">
        {{ $menuItems->links() }}
    </div>
</div>
@endsection
