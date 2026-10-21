@extends('layouts.admin')

@section('title', 'Customer Profile - FoodERP System')
@section('page_title', 'Customer Details: ' . $customer->name)

@section('content')
<div class="row g-4 mb-4">
    <!-- Customer Profile Info -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
            <div class="card-header bg-dark text-white p-4 border-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 55px; height: 55px;">
                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-white">{{ $customer->name }}</h5>
                        <small class="text-secondary">Registered Customer Account</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <small class="text-muted text-uppercase fw-bold d-block">Email Address</small>
                    <span class="fw-semibold text-dark">{{ $customer->email ?? 'N/A' }}</span>
                </div>
                <div class="mb-3">
                    <small class="text-muted text-uppercase fw-bold d-block">Phone Number</small>
                    <a href="tel:{{ $customer->phone }}" class="fw-bold text-danger text-decoration-none">
                        <i class="bi bi-telephone-fill me-1"></i> {{ $customer->phone }}
                    </a>
                </div>
                <div class="mb-3">
                    <small class="text-muted text-uppercase fw-bold d-block">Default Address</small>
                    <span class="fw-semibold text-dark"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $customer->address }} ({{ $customer->city }})</span>
                </div>

                @if($customer->notes)
                    <div class="p-3 bg-light rounded-3 border mb-3">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1">Customer Preferences</small>
                        <span class="small text-dark">{{ $customer->notes }}</span>
                    </div>
                @endif

                <hr>

                <div class="row text-center g-2">
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted small d-block">Lifetime Spending</span>
                            <span class="fs-4 fw-extrabold text-danger">${{ number_format($totalSpent, 2) }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 bg-light rounded-3 border">
                            <span class="text-muted small d-block">Completed Orders</span>
                            <span class="fs-4 fw-extrabold text-success">{{ $completedOrdersCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light p-3 d-flex gap-2">
                <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-outline-dark btn-sm rounded-pill w-100 fw-semibold">
                    <i class="bi bi-pencil me-1"></i> Edit Profile
                </a>
                <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill w-100">
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- Complete Order History -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
            <div class="card-header bg-dark text-white p-4 border-0 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
                <h5 class="fw-bold mb-0"><i class="bi bi-clock-history text-warning me-2"></i> Order History Log</h5>
                <span class="badge bg-secondary rounded-pill">{{ $customer->orders->count() }} Total Orders</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Order #</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->orders as $order)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $order->order_number }}</td>
                                <td class="small text-muted">{{ $order->created_at->format('M d, Y - h:i A') }}</td>
                                <td class="fw-bold text-danger">${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($order->status) {
                                            'pending' => 'bg-secondary',
                                            'confirmed' => 'bg-info text-dark',
                                            'preparing' => 'bg-warning text-dark',
                                            'out_for_delivery' => 'bg-primary',
                                            'delivered' => 'bg-success',
                                            'cancelled' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} text-uppercase rounded-pill">
                                        {{ str_replace('_', ' ', $order->status) }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">View Receipt</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">This customer has not placed any orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
