@extends('layouts.admin')

@section('title', 'Admin Dashboard - FoodERP System')
@section('page_title', 'ERP Overview Dashboard')

@section('content')
<!-- KPI Cards Row -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat p-4 border-0 shadow-sm rounded-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total Sales</span>
                    <h3 class="fw-extrabold text-dark mb-0">${{ number_format($totalSales, 2) }}</h3>
                </div>
                <div class="icon-box bg-success bg-opacity-10 text-success">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat p-4 border-0 shadow-sm rounded-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total Orders</span>
                    <h3 class="fw-extrabold text-dark mb-0">{{ number_format($totalOrders) }}</h3>
                </div>
                <div class="icon-box bg-primary bg-opacity-10 text-primary">
                    <i class="bi bi-bag-check-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat p-4 border-0 shadow-sm rounded-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Pending Orders</span>
                    <h3 class="fw-extrabold text-warning mb-0">{{ number_format($pendingOrders) }}</h3>
                </div>
                <div class="icon-box bg-warning bg-opacity-10 text-warning">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-stat p-4 border-0 shadow-sm rounded-4">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1">Delivered Orders</span>
                    <h3 class="fw-extrabold text-success mb-0">{{ number_format($deliveredOrders) }}</h3>
                </div>
                <div class="icon-box bg-success bg-opacity-10 text-success">
                    <i class="bi bi-house-check-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Metrics Bar -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-info bg-opacity-10 text-info">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">{{ number_format($totalCustomers) }}</h4>
                    <span class="text-muted small">Registered Customers</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-journal-check"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">{{ number_format($availableMenuItems) }} / {{ $totalMenuItems }}</h4>
                    <span class="text-muted small">Available Food Menu Items</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="icon-box bg-dark bg-opacity-10 text-dark">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-0">{{ $deliveryStaffCount }} Staff / {{ $restaurantStaffCount }} Kitchen</h4>
                    <span class="text-muted small">Active Personnel Team</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts & Order Pipeline Status breakdown -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="bi bi-graph-up-arrow text-danger me-2"></i> Monthly Sales &amp; Revenue</h5>
                <span class="badge bg-light text-dark">Recent Months</span>
            </div>
            <canvas id="salesChart" height="110"></canvas>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-pie-chart-fill text-primary me-2"></i> Order Status Breakdown</h5>
            <div class="d-grid gap-3">
                @foreach($orderStats as $statusName => $count)
                    <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                        <span class="fw-semibold text-dark">{{ $statusName }}</span>
                        <span class="badge bg-dark rounded-pill px-3 py-2 fs-6">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders Table -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
    <div class="card-header bg-dark text-white p-4 border-0 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
        <h5 class="fw-bold mb-0"><i class="bi bi-clock-history text-warning me-2"></i> Recent Food Orders</h5>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">View All Orders</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Order #</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="pe-4 text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                    <tr>
                        <td class="ps-4 fw-bold text-dark">{{ $order->order_number }}</td>
                        <td class="fw-semibold">{{ $order->customer->name ?? 'N/A' }}</td>
                        <td class="small text-muted">{{ $order->contact_phone }}</td>
                        <td class="small text-muted">{{ $order->created_at->format('M d, h:i A') }}</td>
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
                            <span class="badge {{ $badgeClass }} text-uppercase rounded-pill px-3 py-2">
                                {{ str_replace('_', ' ', $order->status) }}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">View Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No recent orders recorded.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const monthlyData = @json($monthlySales);

    const labels = monthlyData.map(item => item.month);
    const totals = monthlyData.map(item => item.total_sales);

    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: labels.length ? labels : ['Current Month'],
            datasets: [{
                label: 'Sales Revenue ($)',
                data: totals.length ? totals : [{{ $totalSales }}],
                borderColor: '#ff5e3a',
                backgroundColor: 'rgba(255, 94, 58, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 6,
                pointBackgroundColor: '#ff5e3a'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
