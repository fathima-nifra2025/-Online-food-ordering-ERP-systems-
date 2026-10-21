@extends('layouts.app')

@section('title', 'Shopping Cart - FoodERP System')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-cart3 text-danger me-2"></i> Your Shopping Cart</h2>
            <p class="text-muted small mb-0">Review items in your cart before placing your order</p>
        </div>
        <a href="{{ route('menu.index') }}" class="btn btn-outline-dark rounded-pill px-4 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Continue Browsing
        </a>
    </div>

    @if(count($cart) > 0)
        <div class="row g-4">
            <!-- Cart Items List -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Item Details</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th class="pe-4 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $id => $item)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3 py-2">
                                                @if(isset($item['image']) && $item['image'])
                                                    <img src="{{ asset('storage/' . $item['image']) }}" class="rounded-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                        <i class="bi bi-egg-fried text-muted fs-4"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="fw-bold text-dark mb-1">{{ $item['name'] }}</h6>
                                                    @if(!empty($item['special_instructions']))
                                                        <small class="text-muted"><i class="bi bi-chat-left-text me-1"></i>{{ $item['special_instructions'] }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="fw-semibold text-muted">${{ number_format($item['price'], 2) }}</td>
                                        <td style="width: 140px;">
                                            <form action="{{ route('cart.update', $id) }}" method="POST" id="form-{{ $id }}">
                                                @csrf
                                                <div class="input-group input-group-sm" style="width: 110px;">
                                                    <button type="button" class="btn btn-outline-secondary" onclick="let input = document.getElementById('qty-{{ $id }}'); if(input.value > 1) { input.value--; document.getElementById('form-{{ $id }}').submit(); }"><i class="bi bi-dash"></i></button>
                                                    <input type="number" name="quantity" id="qty-{{ $id }}" value="{{ $item['quantity'] }}" min="1" max="99" class="form-control text-center fw-bold px-0" readonly style="background-color: #fff;">
                                                    <button type="button" class="btn btn-outline-secondary" onclick="let input = document.getElementById('qty-{{ $id }}'); input.value++; document.getElementById('form-{{ $id }}').submit();"><i class="bi bi-plus"></i></button>
                                                </div>
                                            </form>
                                        </td>
                                        <td class="fw-bold text-danger">${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                        <td class="pe-4 text-end">
                                            <form action="{{ route('cart.remove', $id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-link text-danger p-0" title="Remove Item">
                                                    <i class="bi bi-trash fs-5"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-light p-3 d-flex justify-content-between align-items-center">
                        <form action="{{ route('cart.clear') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                <i class="bi bi-trash me-1"></i> Clear Cart
                            </button>
                        </form>
                        <span class="text-muted small">Items in Cart: {{ count($cart) }}</span>
                    </div>
                </div>
            </div>

            <!-- Order Summary Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top" style="top: 100px;">
                    <div class="card-header bg-dark text-white p-4 border-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
                        <h5 class="fw-bold mb-0"><i class="bi bi-receipt me-2"></i> Order Summary</h5>
                    </div>
                    <div class="card-body p-4">
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
                            <span class="fw-bold text-dark fs-5">Total Amount</span>
                            <span class="fw-extrabold text-danger fs-4">${{ number_format($totals['total'], 2) }}</span>
                        </div>

                        <a href="{{ route('cart.checkout') }}" class="btn btn-gradient btn-lg w-100 rounded-pill py-3 fw-bold shadow-sm">
                            Proceed to Checkout <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5 bg-white rounded-4 shadow-sm p-5">
            <i class="bi bi-cart-x display-1 text-muted opacity-50 mb-3 d-block"></i>
            <h3 class="fw-bold text-dark">Your cart is empty</h3>
            <p class="text-muted">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('menu.index') }}" class="btn btn-gradient btn-lg rounded-pill px-5 mt-2 fw-bold">
                <i class="bi bi-bag-plus me-2"></i> Explore Food Menu
            </a>
        </div>
    @endif
</div>
@endsection
