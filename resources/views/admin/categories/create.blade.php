@extends('layouts.admin')

@section('title', 'Add Category - FoodERP System')
@section('page_title', 'Create Menu Category')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-dark text-white p-4 border-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-folder-plus text-danger me-2"></i> Add Category</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold text-muted small">Category Name</label>
                        <input type="text" class="form-control bg-light @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. Starters, Gourmet Burgers">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold text-muted small">Description</label>
                        <textarea class="form-control bg-light" id="description" name="description" rows="3" placeholder="Brief description of category dishes...">{{ old('description') }}</textarea>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label fw-semibold text-dark" for="is_active">Category Available / Active</label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">Save Category</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
