@extends('layouts.admin')

@section('title', 'Delivery Portal - FoodERP System')
@section('page_title', 'Delivery Staff Portal')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1"><i class="bi bi-truck text-success me-2"></i> Delivery Staff Dashboard</h4>
        <p class="text-muted small mb-0">Assigned deliveries, customer contact details, and location tracking</p>
    </div>
    <div class="badge bg-success fs-6 px-3 py-2 rounded-pill">
        <i class="bi bi-check-all me-1"></i> Today Delivered: {{ $todayDeliveredCount }}
    </div>
</div>

<!-- Active Assigned Deliveries -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
    <div class="card-header bg-dark text-white p-4 border-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
        <h5 class="fw-bold mb-0"><i class="bi bi-geo-alt-fill text-danger me-2"></i> Active Assigned Deliveries ({{ $activeDeliveries->count() }})</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            @forelse($activeDeliveries as $delivery)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm rounded-4 p-4 bg-light border-start border-4 border-danger h-100 d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-extrabold text-dark fs-5">#{{ $delivery->order->order_number }}</span>
                            @php
                                $stBadge = match($delivery->status) {
                                    'assigned' => 'bg-secondary',
                                    'picked_up' => 'bg-warning text-dark',
                                    'out_for_delivery' => 'bg-primary',
                                    'delivered' => 'bg-success',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $stBadge }} text-uppercase rounded-pill px-3 py-2">
                                {{ str_replace('_', ' ', $delivery->status) }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted text-uppercase fw-bold d-block">Recipient Customer</small>
                            <span class="fw-bold text-dark fs-6">{{ $delivery->order->customer->name ?? 'Customer' }}</span>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted text-uppercase fw-bold d-block">Contact Phone</small>
                            <a href="tel:{{ $delivery->order->contact_phone }}" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold mt-1">
                                <i class="bi bi-telephone-fill me-1"></i> {{ $delivery->order->contact_phone }}
                            </a>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted text-uppercase fw-bold d-block">Delivery Address</small>
                            <span class="fw-semibold text-dark"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $delivery->order->delivery_address }}</span>
                        </div>

                        <div class="p-3 bg-white rounded-3 border mb-4 small flex-grow-1">
                            <strong class="d-block text-dark mb-1">Order Items:</strong>
                            <ul class="mb-0 ps-3">
                                @foreach($delivery->order->orderDetails as $det)
                                    <li><strong>{{ $det->quantity }}x</strong> {{ $det->item_name }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Status Action Controls -->
                        <div class="mt-auto">
                            <small class="text-muted fw-bold d-block mb-2">Update Delivery Milestone:</small>
                            @if($delivery->status === 'assigned')
                                <form action="{{ route('delivery.status.update', $delivery->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="picked_up">
                                    <button type="submit" class="btn btn-warning text-dark w-100 fw-bold rounded-pill shadow-sm">
                                        <i class="bi bi-bag-check me-1"></i> Mark Picked Up from Kitchen
                                    </button>
                                </form>
                            @elseif($delivery->status === 'picked_up')
                                <form action="{{ route('delivery.status.update', $delivery->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="out_for_delivery">
                                    <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm">
                                        <i class="bi bi-truck me-1"></i> Mark Out for Delivery
                                    </button>
                                </form>
                            @elseif($delivery->status === 'out_for_delivery')
                                <form action="{{ route('delivery.status.update', $delivery->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="delivered">
                                    <button type="submit" class="btn btn-success w-100 fw-bold rounded-pill shadow-sm">
                                        <i class="bi bi-house-check-fill me-1"></i> Mark Successfully Delivered
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="bi bi-check-circle display-3 text-success opacity-50 d-block mb-3"></i>
                    <h5>No active deliveries assigned to you right now</h5>
                    <p class="small">Check back later when new orders are dispatched by admin.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Completed Deliveries History -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
    <div class="card-header bg-light p-4 border-bottom">
        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history text-secondary me-2"></i> Recent Completed Deliveries</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Order #</th>
                    <th>Customer Name</th>
                    <th>Address</th>
                    <th>Delivered At</th>
                    <th class="pe-4 text-end">Receipt</th>
                </tr>
            </thead>
            <tbody>
                @forelse($completedDeliveries as $cDel)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">#{{ $cDel->order->order_number }}</td>
                        <td class="fw-semibold">{{ $cDel->order->customer->name ?? 'Guest' }}</td>
                        <td class="small text-muted">{{ Str::limit($cDel->order->delivery_address, 40) }}</td>
                        <td class="small text-muted">{{ $cDel->delivered_at ? $cDel->delivered_at->format('M d, Y - h:i A') : 'N/A' }}</td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('orders.show', $cDel->order->id) }}" class="btn btn-outline-dark btn-sm rounded-3">View Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No completed deliveries history yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
