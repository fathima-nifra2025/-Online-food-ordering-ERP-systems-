@extends('layouts.admin')

@section('title', 'Food Menu Management - FoodERP System')
@section('page_title', 'Food Menu Items Management')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Food Items Inventory</h4>
        <p class="text-muted small mb-0">Manage food items, prices, availability, and categories</p>
    </div>
    <a href="{{ route('admin.menu.create') }}" class="btn btn-danger rounded-pill px-4 fw-semibold shadow-sm">
        <i class="bi bi-plus-lg me-1"></i> Add Food Item
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
    <div class="card-body p-3 border-bottom bg-light">
        <form action="{{ route('admin.menu.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by food name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category_id" class="form-select form-select-sm">
                    <option value="">-- All Categories --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="availability" class="form-select form-select-sm">
                    <option value="">-- All Availability --</option>
                    <option value="1" {{ request('availability') == '1' ? 'selected' : '' }}>Available Only</option>
                    <option value="0" {{ request('availability') == '0' ? 'selected' : '' }}>Unavailable Only</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-dark btn-sm rounded-pill w-100 fw-semibold">Filter</button>
                @if(request('search') || request('category_id') || request('availability'))
                    <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Item</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Prep Time</th>
                    <th>Featured</th>
                    <th>Status</th>
                    <th class="pe-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menuItems as $item)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" class="rounded-3" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-egg-fried text-muted fs-4"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="fw-bold text-dark mb-0">{{ $item->name }}</div>
                                    <small class="text-muted d-block">{{ Str::limit($item->description, 40) }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark fw-semibold border">{{ $item->category->name }}</span></td>
                        <td class="fw-extrabold text-danger">${{ number_format($item->price, 2) }}</td>
                        <td>
                            @php $qty = $item->stock_quantity ?? 0; @endphp
                            <span class="badge rounded-pill px-3 py-2 {{ $qty > 20 ? 'bg-success' : ($qty > 5 ? 'bg-warning text-dark' : 'bg-danger') }}">
                                <i class="bi bi-box-seam me-1"></i>{{ $qty }}
                            </span>
                        </td>
                        <td class="small text-muted"><i class="bi bi-clock me-1"></i>{{ $item->prep_time_minutes }} mins</td>
                        <td>
                            @if($item->is_featured)
                                <span class="badge bg-warning text-dark"><i class="bi bi-star-fill me-1"></i> Featured</span>
                            @else
                                <span class="text-muted small">Standard</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.menu.toggle', $item->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm rounded-pill px-3 {{ $item->is_available ? 'btn-success' : 'btn-secondary' }}">
                                    {{ $item->is_available ? 'Available' : 'Unavailable' }}
                                </button>
                            </form>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('admin.menu.edit', $item->id) }}" class="btn btn-outline-dark btn-sm rounded-3 me-1">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <form action="{{ route('admin.menu.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete {{ $item->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-3">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No food items found matching criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-light p-3">
        {{ $menuItems->links() }}
    </div>
</div>
@endsection
