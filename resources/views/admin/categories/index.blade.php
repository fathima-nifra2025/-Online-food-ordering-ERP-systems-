@extends('layouts.admin')

@section('title', 'Categories Management - FoodERP System')
@section('page_title', 'Menu Categories Management')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Food Menu Categories</h4>
        <p class="text-muted small mb-0">Organize food items into structured categories</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-danger rounded-pill px-4 fw-semibold shadow-sm">
        <i class="bi bi-plus-lg me-1"></i> Add New Category
    </a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
    <div class="card-body p-3 border-bottom bg-light">
        <form action="{{ route('admin.categories.index') }}" method="GET" class="row g-2 align-items-center">
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Search category by name..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-dark btn-sm rounded-pill w-100 fw-semibold">Search</button>
            </div>
            @if(request('search'))
                <div class="col-md-2">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill w-100">Clear</a>
                </div>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Category Name</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Menu Items Count</th>
                    <th>Status</th>
                    <th class="pe-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">{{ $category->name }}</td>
                        <td><code class="small text-danger">{{ $category->slug }}</code></td>
                        <td class="small text-muted">{{ Str::limit($category->description, 50) ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-primary rounded-pill px-3 py-2 fs-6">{{ $category->menu_items_count }} Items</span>
                        </td>
                        <td>
                            <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-outline-dark btn-sm rounded-3 me-1">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category?');">
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
                        <td colspan="6" class="text-center py-4 text-muted">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-light p-3">
        {{ $categories->links() }}
    </div>
</div>
@endsection
