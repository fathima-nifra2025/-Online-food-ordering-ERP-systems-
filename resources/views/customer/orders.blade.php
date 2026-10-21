@extends('layouts.app')

@section('title', 'My Food Orders - FoodERP System')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-bag-check-fill text-danger me-2"></i> My Order History</h2>
            <p class="text-muted small mb-0">Track live order status and view complete receipt details</p>
        </div>
        <a href="{{ route('menu.index') }}" class="btn btn-gradient rounded-pill px-4 fw-semibold shadow-sm">
            <i class="bi bi-plus-lg me-1"></i> Order More Food
        </a>
    </div>

    @if($orders->count() > 0)
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
                        <tr>
                            <th class="ps-4 py-3">Order #</th>
                            <th class="py-3">Date &amp; Time</th>
                            <th class="py-3">Items Ordered</th>
                            <th class="py-3">Total Amount</th>
                            <th class="py-3">Payment</th>
                            <th class="py-3">Status</th>
                            <th class="pe-4 text-end py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $order->order_number }}</td>
                                <td class="small text-muted">{{ $order->created_at->format('M d, Y - h:i A') }}</td>
                                <td>
                                    <span class="fw-semibold text-dark">{{ $order->orderDetails->sum('quantity') }} items</span>
                                    <small class="text-muted d-block">{{ Str::limit($order->orderDetails->pluck('item_name')->join(', '), 40) }}</small>
                                </td>
                                <td class="fw-bold text-danger">${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }} text-uppercase">
                                        {{ $order->payment_status }} ({{ strtoupper($order->payment_method) }})
                                    </span>
                                </td>
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
                                    <span class="badge {{ $badgeClass }} px-3 py-2 text-uppercase rounded-pill fw-semibold">
                                        {{ str_replace('_', ' ', $order->status) }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-semibold">
                                        Track / Details <i class="bi bi-chevron-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-light p-3">
                {{ $orders->links() }}
            </div>
        </div>
    @else
        <div class="text-center py-5 bg-white rounded-4 shadow-sm p-5">
            <i class="bi bi-clock-history display-1 text-muted opacity-50 mb-3 d-block"></i>
            <h3 class="fw-bold text-dark">No orders placed yet</h3>
            <p class="text-muted">You haven't placed any food orders on FoodERP yet.</p>
            <a href="{{ route('menu.index') }}" class="btn btn-gradient btn-lg rounded-pill px-5 mt-2 fw-bold">
                <i class="bi bi-bag-plus me-2"></i> Start Ordering Now
            </a>
        </div>
    @endif
</div>
@endsection
