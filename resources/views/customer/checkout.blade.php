@extends('layouts.app')

@section('title', 'Checkout - FoodERP System')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-shield-check text-success me-2"></i> Checkout &amp; Order</h2>
            <p class="text-muted small mb-0">Provide delivery details to complete your food order</p>
        </div>
        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Back to Cart
        </a>
    </div>

    <form action="{{ route('orders.place') }}" method="POST">
        @csrf

        <div class="row g-4">
            <!-- Delivery & Contact Info Form -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-dark text-white p-4 border-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
                        <h5 class="fw-bold mb-0"><i class="bi bi-geo-alt-fill text-danger me-2"></i> Delivery Destination</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold text-dark">Recipient Name</label>
                            <input type="text" class="form-control bg-light @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name ?? $customer->name ?? '') }}" required placeholder="Full Name">
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-semibold text-dark">Contact Phone</label>
                                <input type="text" class="form-control bg-light @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone ?? $customer->phone ?? '') }}" required placeholder="+1 (555) 000-0000">
                            </div>
                            <div class="col-md-6">
                                <label for="city" class="form-label fw-semibold text-dark">City / Area</label>
                                <input type="text" class="form-control bg-light @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $customer->city ?? 'Metropolis') }}" required placeholder="City">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="delivery_address" class="form-label fw-semibold text-dark">Full Delivery Address</label>
                            <textarea class="form-control bg-light @error('delivery_address') is-invalid @enderror" id="delivery_address" name="delivery_address" rows="3" required placeholder="Street address, building name, house/suite number...">{{ old('delivery_address', $customer->address ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="customer_notes" class="form-label fw-semibold text-dark">Order Instructions / Notes (Optional)</label>
                            <textarea class="form-control bg-light" id="customer_notes" name="customer_notes" rows="2" placeholder="e.g. Ring door bell twice, extra hot sauce, etc.">{{ old('customer_notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-dark text-white p-4 border-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
                        <h5 class="fw-bold mb-0"><i class="bi bi-credit-card-2-front-fill text-warning me-2"></i> Payment Method</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-grid gap-3">
                            <div class="form-check border p-3 rounded-3 bg-light">
                                <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay_cash" value="cash" checked>
                                <label class="form-check-label fw-semibold text-dark" for="pay_cash">
                                    <i class="bi bi-cash-stack text-success me-2 fs-5"></i> Cash on Delivery (COD)
                                    <span class="d-block small text-muted font-normal">Pay with cash directly to the delivery rider upon food arrival.</span>
                                </label>
                            </div>

                            <div class="form-check border p-3 rounded-3 bg-light">
                                <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay_card" value="card">
                                <label class="form-check-label fw-semibold text-dark" for="pay_card">
                                    <i class="bi bi-credit-card text-primary me-2 fs-5"></i> Credit / Debit Card on Delivery
                                    <span class="d-block small text-muted font-normal">Delivery rider will bring a mobile POS card terminal.</span>
                                </label>
                            </div>

                            <div class="form-check border p-3 rounded-3 bg-light">
                                <input class="form-check-input ms-0 me-3" type="radio" name="payment_method" id="pay_online" value="online">
                                <label class="form-check-label fw-semibold text-dark" for="pay_online">
                                    <i class="bi bi-phone-fill text-danger me-2 fs-5"></i> Online Instant Payment
                                    <span class="d-block small text-muted font-normal">Simulated instant online checkout transaction.</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Review & Confirm -->
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top: 100px;">
                    <div class="card-header bg-dark text-white p-4 border-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
                        <h5 class="fw-bold mb-0"><i class="bi bi-bag-check-fill me-2"></i> Order Items Summary</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-3 max-height-300 overflow-auto">
                            @foreach($cart as $item)
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0">{{ $item['name'] }}</h6>
                                        <small class="text-muted">Qty: {{ $item['quantity'] }} &times; ${{ number_format($item['price'], 2) }}</small>
                                    </div>
                                    <span class="fw-bold text-dark">${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-semibold">${{ number_format($totals['subtotal'], 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Estimated Tax (5%)</span>
                            <span class="fw-semibold">${{ number_format($totals['tax'], 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Delivery Fee</span>
                            <span class="fw-semibold">${{ number_format($totals['delivery_fee'], 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold text-dark fs-5">Grand Total</span>
                            <span class="fw-extrabold text-danger fs-4">${{ number_format($totals['total'], 2) }}</span>
                        </div>

                        <button type="submit" class="btn btn-gradient btn-lg w-100 rounded-pill py-3 fw-bold shadow-sm">
                            <i class="bi bi-check-circle-fill me-2"></i> Place Food Order Now
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
