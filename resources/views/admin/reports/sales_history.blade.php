@extends('layouts.admin')

@section('title', 'Sales History - FoodERP System')
@section('page_title', 'Sales History & Billing Log')

@section('content')

{{-- ── Page Header ── --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1">
            <i class="bi bi-journal-richtext text-danger me-2"></i>Sales History & Billing Records
        </h4>
        <p class="text-muted small mb-0">Full transactional sales log with individual printable invoices & PDF billing</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.reports.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold">
            <i class="bi bi-graph-up-arrow me-1"></i> Reports
        </a>
        <button onclick="window.print()" class="btn btn-dark rounded-pill px-4 fw-semibold shadow-sm">
            <i class="bi bi-printer me-1"></i> Print This Page
        </button>
    </div>
</div>

{{-- ── Filter Bar ── --}}
<div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
    <div class="card-body p-4">
        <form action="{{ route('admin.sales.history') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-bold small text-muted text-uppercase">Search</label>
                <input type="text" name="search" class="form-control rounded-3"
                       placeholder="Order #, Customer, Phone..." value="{{ $search }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold small text-muted text-uppercase">Status</label>
                <select name="status" class="form-select rounded-3 fw-semibold">
                    <option value="">All Statuses</option>
                    @foreach(['pending','confirmed','preparing','out_for_delivery','delivered','cancelled'] as $st)
                        <option value="{{ $st }}" {{ $status === $st ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_',' ',$st)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold small text-muted text-uppercase">From Date</label>
                <input type="date" name="date_from" class="form-control rounded-3" value="{{ $dateFrom }}">
            </div>
            <div class="col-md-2">
                <label class="form-label fw-bold small text-muted text-uppercase">To Date</label>
                <input type="date" name="date_to" class="form-control rounded-3" value="{{ $dateTo }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-danger rounded-pill w-100 fw-bold shadow-sm py-2">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                <a href="{{ route('admin.sales.history') }}" class="btn btn-outline-secondary rounded-pill px-3">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- ── KPI Summary Cards ── --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 rounded-4 p-4 text-white" style="background: linear-gradient(135deg,#ff5e3a,#ff471a);">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <p class="mb-1 small fw-semibold opacity-75">Total Revenue</p>
                    <h3 class="fw-bold mb-0">${{ number_format($grandTotal, 2) }}</h3>
                </div>
                <i class="bi bi-cash-coin" style="font-size:2.5rem;opacity:.4;"></i>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 rounded-4 p-4 bg-white shadow-sm">
            <p class="mb-1 small fw-semibold text-muted text-uppercase">Subtotal (excl. tax)</p>
            <h3 class="fw-bold text-dark mb-0">${{ number_format($grandSubtotal, 2) }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 rounded-4 p-4 bg-white shadow-sm">
            <p class="mb-1 small fw-semibold text-muted text-uppercase">Total Tax Collected</p>
            <h3 class="fw-bold text-warning mb-0">${{ number_format($grandTax, 2) }}</h3>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card border-0 rounded-4 p-4 bg-white shadow-sm">
            <p class="mb-1 small fw-semibold text-muted text-uppercase">Delivery Fees</p>
            <h3 class="fw-bold text-info mb-0">${{ number_format($grandDelivery, 2) }}</h3>
        </div>
    </div>
</div>

{{-- ── Sales Table ── --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
    <div class="card-header p-4 border-0 d-flex align-items-center justify-content-between"
         style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <h5 class="fw-bold mb-0 text-white">
            <i class="bi bi-receipt-cutoff text-warning me-2"></i>
            Transaction Records
            <span class="badge bg-danger ms-2 fs-6">{{ $totalCount }} total</span>
        </h5>
        <span class="text-muted small opacity-75">
            {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} &rarr; {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-4 py-3">Order #</th>
                    <th>Date &amp; Time</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th class="text-end">Subtotal</th>
                    <th class="text-end">Tax</th>
                    <th class="text-end">Total</th>
                    <th class="pe-4 text-center">Bill / Print</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    @php
                        $statusColors = [
                            'pending'          => 'bg-secondary',
                            'confirmed'        => 'bg-info text-dark',
                            'preparing'        => 'bg-warning text-dark',
                            'out_for_delivery' => 'bg-primary',
                            'delivered'        => 'bg-success',
                            'cancelled'        => 'bg-danger',
                        ];
                        $sc = $statusColors[$order->status] ?? 'bg-secondary';
                    @endphp
                    <tr>
                        <td class="ps-4 fw-bold text-dark">{{ $order->order_number }}</td>
                        <td class="small text-muted">
                            {{ $order->created_at->format('M d, Y') }}<br>
                            <span class="text-dark fw-semibold">{{ $order->created_at->format('h:i A') }}</span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $order->customer->name ?? 'Guest' }}</div>
                            <small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $order->contact_phone }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $order->orderDetails->sum('quantity') }} items
                            </span>
                        </td>
                        <td>
                            <span class="badge rounded-pill px-2 py-1 {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                <i class="bi {{ $order->payment_status === 'paid' ? 'bi-check-circle' : 'bi-clock' }} me-1"></i>
                                {{ ucfirst($order->payment_method) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $sc }} rounded-pill px-3 py-1 text-uppercase" style="font-size:.72rem;">
                                {{ str_replace('_',' ',$order->status) }}
                            </span>
                        </td>
                        <td class="text-end fw-semibold text-muted">${{ number_format($order->subtotal, 2) }}</td>
                        <td class="text-end fw-semibold text-warning">${{ number_format($order->tax_amount, 2) }}</td>
                        <td class="text-end fw-bold text-danger fs-6">${{ number_format($order->total_amount, 2) }}</td>
                        <td class="pe-4 text-center">
                            <a href="{{ route('admin.orders.print-bill', $order->id) }}"
                               target="_blank"
                               class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-semibold"
                               title="Print Bill / PDF Invoice">
                                <i class="bi bi-file-earmark-pdf me-1"></i> Bill
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                            No sales records found for the selected filters.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if($orders->count())
            <tfoot class="table-dark">
                <tr>
                    <td colspan="6" class="ps-4 fw-bold text-white py-3">
                        Page Total ({{ $orders->count() }} shown)
                    </td>
                    <td class="text-end fw-bold text-warning">
                        ${{ number_format($orders->sum('subtotal'), 2) }}
                    </td>
                    <td class="text-end fw-bold text-warning">
                        ${{ number_format($orders->sum('tax_amount'), 2) }}
                    </td>
                    <td class="text-end fw-bold text-danger fs-5">
                        ${{ number_format($orders->sum('total_amount'), 2) }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    <div class="card-footer bg-light p-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
        <small class="text-muted">
            Showing {{ $orders->firstItem() ?? 0 }}–{{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} records
        </small>
        {{ $orders->links() }}
    </div>
</div>

@push('scripts')
<style>
    @media print {
        #sidebar, .top-navbar, .card-footer, form, .btn, nav { display: none !important; }
        #main-content { margin-left: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
    }
</style>
@endpush

@endsection
