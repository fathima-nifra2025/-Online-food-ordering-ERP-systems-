@extends('layouts.admin')

@section('title', 'Edit Food Item - FoodERP System')
@section('page_title', 'Edit Food Item: ' . $menuItem->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-dark text-white p-4 border-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square text-warning me-2"></i> Edit Food Item</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.menu.update', $menuItem->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label for="name" class="form-label fw-bold text-muted small">Food Name</label>
                            <input type="text" class="form-control bg-light @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $menuItem->name) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="category_id" class="form-label fw-bold text-muted small">Category</label>
                            <select name="category_id" class="form-select bg-light @error('category_id') is-invalid @enderror" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $menuItem->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label for="price" class="form-label fw-bold text-muted small">Price ($)</label>
                            <input type="number" step="0.01" min="0" class="form-control bg-light @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $menuItem->price) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="prep_time_minutes" class="form-label fw-bold text-muted small">Prep Time (Minutes)</label>
                            <input type="number" min="1" max="180" class="form-control bg-light @error('prep_time_minutes') is-invalid @enderror" id="prep_time_minutes" name="prep_time_minutes" value="{{ old('prep_time_minutes', $menuItem->prep_time_minutes) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label for="stock_quantity" class="form-label fw-bold text-muted small">Stock / Quantity Available</label>
                            <input type="number" min="0" class="form-control bg-light @error('stock_quantity') is-invalid @enderror" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $menuItem->stock_quantity) }}" required>
                            @error('stock_quantity')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold text-muted small">Description</label>
                        <textarea class="form-control bg-light" id="description" name="description" rows="3">{{ old('description', $menuItem->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="form-label fw-bold text-muted small">Change Food Image (Optional)</label>
                        @if($menuItem->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $menuItem->image) }}" class="rounded-3 border" style="width: 80px; height: 80px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" class="form-control bg-light" id="image" name="image" accept="image/*">
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" {{ $menuItem->is_available ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-dark" for="is_available">Available for Customer Order</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ $menuItem->is_featured ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold text-dark" for="is_featured">Highlight as Featured Dish</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">Update Food Item</button>
                        <a href="{{ route('admin.menu.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
