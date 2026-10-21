@extends('layouts.admin')

@section('title', 'Order Management - FoodERP System')
@section('page_title', 'Master Order Management Console')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Orders Dispatch Console</h4>
        <p class="text-muted small mb-0">Monitor incoming orders, transition kitchen pipeline, and assign delivery riders</p>
    </div>
</div>

<!-- Status Filter Tabs -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
    <div class="card-header bg-white p-3 border-bottom">
        <ul class="nav nav-pills card-header-pills gap-1">
            <li class="nav-item">
                <a class="nav-link rounded-pill fw-semibold {{ !request('status') ? 'active bg-danger' : 'text-dark' }}" href="{{ route('admin.orders.index') }}">
                    All Orders
                </a>
            </li>
            @foreach(['pending', 'confirmed', 'preparing', 'out_for_delivery', 'delivered', 'cancelled'] as $st)
                <li class="nav-item">
                    <a class="nav-link rounded-pill fw-semibold {{ request('status') === $st ? 'active bg-danger' : 'text-dark' }}" href="{{ route('admin.orders.index', ['status' => $st]) }}">
                        {{ ucfirst(str_replace('_', ' ', $st)) }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Search & Date Filter Bar -->
    <div class="card-body p-3 bg-light border-bottom">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="row g-2 align-items-center">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search order #, phone, customer name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}" placeholder="From date">
            </div>
            <div class="col-md-3">
                <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}" placeholder="To date">
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-dark btn-sm rounded-pill w-100 fw-semibold">Filter</button>
                @if(request('search') || request('date_from') || request('date_to'))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Order #</th>
                    <th>Customer Info</th>
                    <th>Date &amp; Time</th>
                    <th>Items Count</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Delivery Staff</th>
                    <th class="pe-4 text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">{{ $order->order_number }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $order->customer->name ?? 'Guest' }}</div>
                            <small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $order->contact_phone }}</small>
                        </td>
                        <td class="small text-muted">{{ $order->created_at->format('M d, Y - h:i A') }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $order->orderDetails->sum('quantity') }} items</span></td>
                        <td class="fw-extrabold text-danger">${{ number_format($order->total_amount, 2) }}</td>
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
                            <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm fw-bold border-0 {{ $badgeClass }} rounded-pill px-2 text-center" onchange="this.form.submit()" style="width: 140px;">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Preparing</option>
                                    <option value="out_for_delivery" {{ $order->status === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            @if($order->delivery)
                                <span class="badge bg-success text-uppercase"><i class="bi bi-person-check me-1"></i> {{ $order->delivery->deliveryStaff->name }}</span>
                            @else
                                <!-- Quick Assign Modal Trigger -->
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill py-0 px-2 small" data-bs-toggle="modal" data-bs-target="#assignModal{{ $order->id }}">
                                    + Assign Rider
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="assignModal{{ $order->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content text-start">
                                            <form action="{{ route('admin.deliveries.assign') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                <div class="modal-header bg-dark text-white">
                                                    <h5 class="modal-title fw-bold">Assign Delivery Staff - #{{ $order->order_number }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-muted">Select Delivery Staff</label>
                                                        <select name="delivery_staff_id" class="form-select" required>
                                                            <option value="">-- Choose Active Rider --</option>
                                                            @foreach($deliveryStaffList as $staff)
                                                                <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->phone }})</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold small text-muted">Delivery Notes / Instructions</label>
                                                        <textarea name="delivery_notes" class="form-control" rows="2" placeholder="Fragile food box, handle with care..."></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-danger fw-bold rounded-pill px-4">Assign Staff</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-dark btn-sm rounded-3">
                                <i class="bi bi-eye"></i> Details
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No orders found matching criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer bg-light p-3">
        {{ $orders->links() }}
    </div>
</div>
@endsection
