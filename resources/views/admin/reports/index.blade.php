@extends('layouts.admin')

@section('title', 'ERP Reports & Analytics - FoodERP System')
@section('page_title', 'Business Analytics & ERP Reports')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">Reports &amp; Business Intelligence Hub</h4>
        <p class="text-muted small mb-0">Generate financial summaries, order statistics, delivery logs, and top dish rankings</p>
    </div>
    <button onclick="window.print()" class="btn btn-dark rounded-pill px-4 fw-semibold shadow-sm">
        <i class="bi bi-printer me-1"></i> Print / Export Report
    </button>
</div>

<!-- Date Range Filter Bar -->
<div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-bold small text-muted">Report Section</label>
                <select name="type" class="form-select fw-semibold">
                    <option value="daily" {{ request('type') === 'daily' ? 'selected' : '' }}>Daily Orders &amp; Sales</option>
                    <option value="monthly" {{ request('type') === 'monthly' ? 'selected' : '' }}>Monthly Revenue Matrix</option>
                    <option value="dishes" {{ request('type') === 'dishes' ? 'selected' : '' }}>Most Popular Food Items</option>
                    <option value="customers" {{ request('type') === 'customers' ? 'selected' : '' }}>Customer Order History</option>
                    <option value="delivery" {{ request('type') === 'delivery' ? 'selected' : '' }}>Delivery Status Report</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small text-muted">Start Date (From)</label>
                <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small text-muted">End Date (To)</label>
                <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-danger rounded-pill w-100 fw-bold shadow-sm py-2">
                    <i class="bi bi-funnel me-1"></i> Generate Report
                </button>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary rounded-pill px-3">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- KPI Summary Highlights Row -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Filtered Revenue</span>
            <h3 class="fw-extrabold text-danger mb-0">${{ number_format($totalSales, 2) }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Total Orders</span>
            <h3 class="fw-extrabold text-dark mb-0">{{ number_format($totalOrders) }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Fulfilled / Delivered</span>
            <h3 class="fw-extrabold text-success mb-0">{{ number_format($deliveredOrders) }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
            <span class="text-muted small fw-bold text-uppercase d-block mb-1">Cancelled Orders</span>
            <h3 class="fw-extrabold text-muted mb-0">{{ number_format($cancelledOrders) }}</h3>
        </div>
    </div>
</div>

<!-- Tabular Reports -->

<!-- 1. Daily Orders & Sales Report -->
@if(request('type', 'daily') === 'daily')
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="card-header bg-dark text-white p-4 border-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
            <h5 class="fw-bold mb-0"><i class="bi bi-calendar-check text-warning me-2"></i> Daily Orders &amp; Sales Summary Report</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Date</th>
                        <th>Total Orders</th>
                        <th>Delivered Count</th>
                        <th>Cancelled Count</th>
                        <th class="pe-4 text-end">Total Revenue ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($dailyReport as $row)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ \Carbon\Carbon::parse($row->date)->format('F d, Y') }}</td>
                            <td class="fw-semibold">{{ number_format($row->total_orders) }}</td>
                            <td><span class="badge bg-success rounded-pill px-3">{{ number_format($row->delivered_count) }}</span></td>
                            <td><span class="badge bg-secondary rounded-pill px-3">{{ number_format($row->cancelled_count) }}</span></td>
                            <td class="pe-4 text-end fw-extrabold text-danger">${{ number_format($row->total_revenue, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No daily records for this date range.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- 2. Monthly Revenue Matrix Report -->
@if(request('type') === 'monthly')
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="card-header bg-dark text-white p-4 border-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
            <h5 class="fw-bold mb-0"><i class="bi bi-graph-up text-success me-2"></i> Monthly Sales &amp; Revenue Matrix</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Month</th>
                        <th>Total Orders</th>
                        <th>Avg Order Value</th>
                        <th class="pe-4 text-end">Total Sales Revenue ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthlyReport as $row)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ \Carbon\Carbon::parse($row->month . '-01')->format('F Y') }}</td>
                            <td class="fw-semibold">{{ number_format($row->total_orders) }}</td>
                            <td class="fw-semibold text-muted">${{ number_format($row->avg_order_value, 2) }}</td>
                            <td class="pe-4 text-end fw-extrabold text-danger">${{ number_format($row->total_revenue, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">No monthly sales data recorded.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- 3. Most Popular Food Items -->
@if(request('type') === 'dishes')
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="card-header bg-dark text-white p-4 border-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
            <h5 class="fw-bold mb-0"><i class="bi bi-trophy-fill text-warning me-2"></i> Top 10 Most Ordered Food Items</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Rank</th>
                        <th>Food Item Name</th>
                        <th>Total Units Sold</th>
                        <th class="pe-4 text-end">Generated Revenue ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topDishes as $idx => $dish)
                        <tr>
                            <td class="ps-4"><span class="badge bg-danger rounded-circle p-2" style="width: 30px; height: 30px;">#{{ $idx + 1 }}</span></td>
                            <td class="fw-bold text-dark">{{ $dish->item_name }}</td>
                            <td><span class="badge bg-primary rounded-pill px-3 py-2 fs-6">{{ number_format($dish->total_quantity) }} units</span></td>
                            <td class="pe-4 text-end fw-extrabold text-danger">${{ number_format($dish->total_revenue, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-4 text-muted">No dish ordering data available.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- 4. Customer Order History Report -->
@if(request('type') === 'customers')
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="card-header bg-dark text-white p-4 border-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
            <h5 class="fw-bold mb-0"><i class="bi bi-people-fill text-primary me-2"></i> Top Customer Value Ranking</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Customer Name</th>
                        <th>Contact Phone</th>
                        <th>City</th>
                        <th>Total Orders Placed</th>
                        <th class="pe-4 text-end">Total Lifetime Value ($)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customerReport as $cust)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">{{ $cust->name }}</td>
                            <td class="small text-muted">{{ $cust->phone }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $cust->city }}</span></td>
                            <td class="fw-semibold">{{ number_format($cust->orders_count) }}</td>
                            <td class="pe-4 text-end fw-extrabold text-danger">${{ number_format($cust->orders_sum_total_amount ?? 0, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No customer data found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- 5. Delivery Status Report -->
@if(request('type') === 'delivery')
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
        <div class="card-header bg-dark text-white p-4 border-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
            <h5 class="fw-bold mb-0"><i class="bi bi-truck text-success me-2"></i> Delivery Efficiency &amp; Dispatch Report</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Order #</th>
                        <th>Rider Name</th>
                        <th>Status</th>
                        <th>Assigned At</th>
                        <th class="pe-4 text-end">Delivered At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveryReport as $del)
                        <tr>
                            <td class="ps-4 fw-bold text-dark">#{{ $del->order->order_number }}</td>
                            <td class="fw-semibold text-primary"><i class="bi bi-person-circle me-1"></i>{{ $del->deliveryStaff->name }}</td>
                            <td>
                                <span class="badge bg-success text-uppercase rounded-pill px-3">{{ str_replace('_', ' ', $del->status) }}</span>
                            </td>
                            <td class="small text-muted">{{ $del->assigned_at ? $del->assigned_at->format('M d, h:i A') : 'N/A' }}</td>
                            <td class="pe-4 text-end small text-muted">{{ $del->delivered_at ? $del->delivered_at->format('M d, h:i A') : 'In Transit' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No delivery logs for this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
