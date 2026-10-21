@extends('layouts.admin')

@section('title', 'Delivery Management - FoodERP System')
@section('page_title', 'Delivery Dispatch & Staff Tracking')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Delivery Dispatch Management</h4>
        <p class="text-muted small mb-0">Assign riders, track live delivery statuses, and view completed dispatches</p>
    </div>
</div>

<!-- Unassigned Active Orders Banner -->
@if($unassignedOrders->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 bg-warning bg-opacity-10 border-warning border-start border-4 mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold text-dark mb-2"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i> {{ $unassignedOrders->count() }} Orders Ready for Rider Assignment</h5>
            <p class="small text-muted mb-3">Assign delivery staff to these active orders to ensure speedy delivery to customers.</p>
            
            <div class="row g-3">
                @foreach($unassignedOrders as $unOrder)
                    <div class="col-md-6 col-lg-4">
                        <div class="p-3 bg-white rounded-3 border shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-dark">#{{ $unOrder->order_number }}</span>
                                <span class="badge bg-danger text-uppercase">{{ str_replace('_', ' ', $unOrder->status) }}</span>
                            </div>
                            <div class="small text-muted mb-2"><i class="bi bi-person me-1"></i>{{ $unOrder->customer->name ?? 'Customer' }}</div>
                            <div class="small text-muted mb-3"><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($unOrder->delivery_address, 35) }}</div>
                            
                            <!-- Assign Form -->
                            <form action="{{ route('admin.deliveries.assign') }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <input type="hidden" name="order_id" value="{{ $unOrder->id }}">
                                <select name="delivery_staff_id" class="form-select form-select-sm" required>
                                    <option value="">Choose Staff...</option>
                                    @foreach($deliveryStaffList as $stf)
                                        <option value="{{ $stf->id }}">{{ $stf->name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-danger btn-sm fw-bold text-nowrap">Assign</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

<!-- Master Deliveries Log Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
    <div class="card-header bg-dark text-white p-4 border-0 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
        <h5 class="fw-bold mb-0"><i class="bi bi-truck text-success me-2"></i> Deliveries Dispatch Log</h5>
        <span class="badge bg-secondary">Total: {{ $deliveries->total() }}</span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Order #</th>
                    <th>Customer Name &amp; Address</th>
                    <th>Assigned Delivery Staff</th>
                    <th>Status</th>
                    <th>Assigned At</th>
                    <th>Delivered At</th>
                    <th class="pe-4 text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveries as $del)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">
                            <a href="{{ route('orders.show', $del->order->id) }}" class="text-danger text-decoration-none">
                                #{{ $del->order->order_number }}
                            </a>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $del->order->customer->name ?? 'Guest' }}</div>
                            <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ Str::limit($del->order->delivery_address, 35) }}</small>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark"><i class="bi bi-person-badge text-primary me-1"></i>{{ $del->deliveryStaff->name }}</div>
                            <small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $del->deliveryStaff->phone }}</small>
                        </td>
                        <td>
                            @php
                                $dBadge = match($del->status) {
                                    'assigned' => 'bg-secondary',
                                    'picked_up' => 'bg-warning text-dark',
                                    'out_for_delivery' => 'bg-primary',
                                    'delivered' => 'bg-success',
                                    default => 'bg-secondary',
                                };
                            @endphp
                            <span class="badge {{ $dBadge }} text-uppercase rounded-pill px-3 py-2">
                                {{ str_replace('_', ' ', $del->status) }}
                            </span>
                        </td>
                        <td class="small text-muted">{{ $del->assigned_at ? $del->assigned_at->format('M d, h:i A') : 'N/A' }}</td>
                        <td class="small text-muted">{{ $del->delivered_at ? $del->delivered_at->format('M d, h:i A') : 'Pending' }}</td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('orders.show', $del->order->id) }}" class="btn btn-outline-dark btn-sm rounded-3">
                                View Order
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No delivery dispatch records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-light p-3">
        {{ $deliveries->links() }}
    </div>
</div>
@endsection
