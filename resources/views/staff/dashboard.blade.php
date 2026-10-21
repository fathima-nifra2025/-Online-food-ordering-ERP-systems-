@extends('layouts.admin')

@section('title', 'Kitchen Queue - FoodERP System')
@section('page_title', 'Restaurant Kitchen Fulfillment Queue')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-bold text-dark mb-1"><i class="bi bi-egg-fried text-warning me-2"></i> Kitchen Live Orders Queue</h4>
        <p class="text-muted small mb-0">Real-time order fulfillment board for restaurant kitchen staff</p>
    </div>
    <div class="badge bg-success fs-6 px-3 py-2 rounded-pill">
        <i class="bi bi-check-circle-fill me-1"></i> Completed Today: {{ $completedToday }}
    </div>
</div>

<div class="row g-4">
    <!-- 1. Pending Orders Column -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-light h-100">
            <div class="card-header bg-secondary text-white p-3 border-0 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock me-2"></i> Incoming Pending ({{ $pendingOrders->count() }})</h6>
                <span class="badge bg-white text-dark">Step 1</span>
            </div>
            <div class="card-body p-3 overflow-auto max-height-700">
                @forelse($pendingOrders as $order)
                    <div class="card border-0 shadow-sm rounded-3 mb-3 bg-white p-3 border-start border-4 border-secondary">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-dark fs-5">#{{ $order->order_number }}</span>
                            <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="small text-muted mb-2"><i class="bi bi-person me-1"></i>{{ $order->customer->name ?? 'Customer' }}</div>

                        <div class="p-2 bg-light rounded-3 mb-3 small">
                            <strong class="d-block text-dark mb-1">Dishes:</strong>
                            <ul class="mb-0 ps-3">
                                @foreach($order->orderDetails as $det)
                                    <li><strong>{{ $det->quantity }}x</strong> {{ $det->item_name }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <form action="{{ route('staff.orders.status', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="btn btn-info text-dark w-100 fw-bold rounded-pill">
                                <i class="bi bi-check-lg me-1"></i> Confirm Order
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted small">No new pending orders in queue.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 2. Confirmed Orders Column -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-light h-100">
            <div class="card-header bg-info text-dark p-3 border-0 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0"><i class="bi bi-check-circle me-2"></i> Confirmed Orders ({{ $confirmedOrders->count() }})</h6>
                <span class="badge bg-dark text-white">Step 2</span>
            </div>
            <div class="card-body p-3 overflow-auto max-height-700">
                @forelse($confirmedOrders as $order)
                    <div class="card border-0 shadow-sm rounded-3 mb-3 bg-white p-3 border-start border-4 border-info">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-dark fs-5">#{{ $order->order_number }}</span>
                            <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                        </div>
                        <div class="small text-muted mb-2"><i class="bi bi-person me-1"></i>{{ $order->customer->name ?? 'Customer' }}</div>

                        <div class="p-2 bg-light rounded-3 mb-3 small">
                            <strong class="d-block text-dark mb-1">Dishes to Cook:</strong>
                            <ul class="mb-0 ps-3">
                                @foreach($order->orderDetails as $det)
                                    <li><strong>{{ $det->quantity }}x</strong> {{ $det->item_name }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <form action="{{ route('staff.orders.status', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="preparing">
                            <button type="submit" class="btn btn-warning text-dark w-100 fw-bold rounded-pill">
                                <i class="bi bi-fire me-1"></i> Start Cooking / Preparing
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted small">No confirmed orders waiting.</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 3. Preparing / Cooking Column -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-light h-100">
            <div class="card-header bg-warning text-dark p-3 border-0 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0"><i class="bi bi-fire me-2"></i> Kitchen Cooking ({{ $preparingOrders->count() }})</h6>
                <span class="badge bg-dark text-white">Step 3</span>
            </div>
            <div class="card-body p-3 overflow-auto max-height-700">
                @forelse($preparingOrders as $order)
                    <div class="card border-0 shadow-sm rounded-3 mb-3 bg-white p-3 border-start border-4 border-warning">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="fw-bold text-dark fs-5">#{{ $order->order_number }}</span>
                            <span class="badge bg-warning text-dark">Active Cooking</span>
                        </div>
                        <div class="small text-muted mb-2"><i class="bi bi-person me-1"></i>{{ $order->customer->name ?? 'Customer' }}</div>

                        <div class="p-2 bg-light rounded-3 mb-3 small">
                            <strong class="d-block text-dark mb-1">Dishes:</strong>
                            <ul class="mb-0 ps-3">
                                @foreach($order->orderDetails as $det)
                                    <li><strong>{{ $det->quantity }}x</strong> {{ $det->item_name }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <form action="{{ route('staff.orders.status', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="out_for_delivery">
                            <button type="submit" class="btn btn-success text-white w-100 fw-bold rounded-pill">
                                <i class="bi bi-bag-check me-1"></i> Mark Ready for Delivery
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted small">Kitchen is currently idle.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
