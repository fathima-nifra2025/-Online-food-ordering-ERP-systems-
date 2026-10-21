@extends('layouts.admin')

@section('title', 'Add Food Item - FoodERP System')
@section('page_title', 'Add Food Menu Item')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-dark text-white p-4 border-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-egg-fried text-danger me-2"></i> Add Food Item</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label for="name" class="form-label fw-bold text-muted small">Food Name</label>
                            <input type="text" class="form-control bg-light @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. Double Bacon Smash Burger">
                        </div>
                        <div class="col-md-4">
                            <label for="category_id" class="form-label fw-bold text-muted small">Category</label>
                            <select name="category_id" class="form-select bg-light @error('category_id') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="price" class="form-label fw-bold text-muted small">Price ($)</label>
                            <input type="number" step="0.01" min="0" class="form-control bg-light @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required placeholder="14.99">
                        </div>
                        <div class="col-md-4">
                            <label for="prep_time_minutes" class="form-label fw-bold text-muted small">Prep Time (Minutes)</label>
                            <input type="number" min="1" max="180" class="form-control bg-light @error('prep_time_minutes') is-invalid @enderror" id="prep_time_minutes" name="prep_time_minutes" value="{{ old('prep_time_minutes', 15) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="stock_quantity" class="form-label fw-bold text-muted small">Stock / Quantity Available</label>
                            <input type="number" min="0" class="form-control bg-light @error('stock_quantity') is-invalid @enderror" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 100) }}" required placeholder="100">
                            @error('stock_quantity')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold text-muted small">Description</label>
                        <textarea class="form-control bg-light" id="description" name="description" rows="3" placeholder="Ingredients, flavors, special description...">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="form-label fw-bold text-muted small">Food Image Upload (Optional)</label>
                        <input type="file" class="form-control bg-light" id="image" name="image" accept="image/*">
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" checked>
                                <label class="form-check-label fw-semibold text-dark" for="is_available">Available for Customer Order</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1">
                                <label class="form-check-label fw-semibold text-dark" for="is_featured">Highlight as Featured Dish</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">Save Food Item</button>
                        <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
