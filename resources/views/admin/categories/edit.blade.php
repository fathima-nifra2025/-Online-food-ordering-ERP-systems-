@extends('layouts.admin')

@section('title', 'Edit Category - FoodERP System')
@section('page_title', 'Edit Category: ' . $category->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-dark text-white p-4 border-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square text-warning me-2"></i> Edit Category</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold text-muted small">Category Name</label>
                        <input type="text" class="form-control bg-light @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold text-muted small">Description</label>
                        <textarea class="form-control bg-light" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold text-dark" for="is_active">Category Available / Active</label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">Update Category</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
