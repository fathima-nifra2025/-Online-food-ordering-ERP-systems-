@extends('layouts.admin')

@section('title', 'Edit Customer - FoodERP System')
@section('page_title', 'Edit Customer Profile: ' . $customer->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-dark text-white p-4 border-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square text-warning me-2"></i> Edit Customer Details</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold text-muted small">Customer Name</label>
                        <input type="text" class="form-control bg-light @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $customer->name) }}" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold text-muted small">Email Address</label>
                            <input type="email" class="form-control bg-light @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $customer->email) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-bold text-muted small">Phone Number</label>
                            <input type="text" class="form-control bg-light @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label fw-bold text-muted small">Delivery Address</label>
                        <textarea class="form-control bg-light @error('address') is-invalid @enderror" id="address" name="address" rows="2" required>{{ old('address', $customer->address) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label fw-bold text-muted small">City</label>
                        <input type="text" class="form-control bg-light" id="city" name="city" value="{{ old('city', $customer->city) }}">
                    </div>

                    <div class="mb-4">
                        <label for="notes" class="form-label fw-bold text-muted small">Customer Notes (Optional)</label>
                        <textarea class="form-control bg-light" id="notes" name="notes" rows="2">{{ old('notes', $customer->notes) }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">Update Customer Details</button>
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
