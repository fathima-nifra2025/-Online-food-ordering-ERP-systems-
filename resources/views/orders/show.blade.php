@extends(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isRestaurantStaff() || auth()->user()->isDeliveryStaff()) ? 'layouts.admin' : 'layouts.app')

@section('title', 'Order Details - ' . $order->order_number)
@section('page_title', 'Order Details: ' . $order->order_number)

@section('content')
<div class="container py-3">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h2 class="fw-bold text-dark mb-0">Invoice / Order #{{ $order->order_number }}</h2>
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
                <span class="badge {{ $badgeClass }} px-3 py-2 text-uppercase rounded-pill fs-6">
                    {{ str_replace('_', ' ', $order->status) }}
                </span>
            </div>
            <small class="text-muted"><i class="bi bi-calendar3 me-1"></i> Placed on {{ $order->created_at->format('F d, Y - h:i A') }}</small>
        </div>

        <div class="d-flex align-items-center gap-2">
            <button onclick="window.print()" class="btn btn-primary rounded-pill px-3 me-2 d-none d-md-inline-block">
                <i class="bi bi-printer me-1"></i> Print Invoice
            </button>
            @if(auth()->check() && auth()->user()->isAdmin())
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Back to Orders
                </a>
            @elseif(auth()->check() && auth()->user()->isRestaurantStaff())
                <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Kitchen Queue
                </a>
            @elseif(auth()->check() && auth()->user()->isDeliveryStaff())
                <a href="{{ route('delivery.dashboard') }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Delivery Dashboard
                </a>
            @else
                <a href="{{ route('customer.orders') }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> My Orders
                </a>
            @endif
        </div>
    </div>

    <!-- Visual Status Timeline Tracker -->
    @if($order->status !== 'cancelled')
        <div class="card border-0 shadow-sm rounded-4 mb-4 p-4 bg-white">
            <h6 class="fw-bold text-muted text-uppercase mb-4 text-center">Order Delivery Pipeline Status</h6>
            <div class="row text-center position-relative g-3">
                @php
                    $steps = [
                        'pending'          => ['title' => 'Order Received',   'icon' => 'bi-receipt'],
                        'confirmed'        => ['title' => 'Confirmed',         'icon' => 'bi-check-circle'],
                        'preparing'        => ['title' => 'Kitchen Preparing', 'icon' => 'bi-fire'],
                        'out_for_delivery' => ['title' => 'Out for Delivery',  'icon' => 'bi-truck'],
                        'delivered'        => ['title' => 'Delivered',         'icon' => 'bi-house-check-fill'],
                    ];
                    $statusesOrder = ['pending', 'confirmed', 'preparing', 'out_for_delivery', 'delivered'];
                    $currentIndex  = array_search($order->status, $statusesOrder);
                    $isStaff       = auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isRestaurantStaff());
                @endphp

                @foreach($statusesOrder as $idx => $st)
                    <div class="col">
                        <div class="d-flex flex-column align-items-center">
                            @if($isStaff && $idx !== $currentIndex)
                                {{-- ✅ Clickable step for Admin / Restaurant Staff --}}
                                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST"
                                      class="d-flex flex-column align-items-center"
                                      title="Set status: {{ $steps[$st]['title'] }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $st }}">
                                    <button type="submit"
                                            class="rounded-circle d-flex align-items-center justify-content-center mb-2 border-0
                                                   {{ $idx < $currentIndex ? 'bg-success text-white' : 'bg-light text-secondary border' }}"
                                            style="width:50px;height:50px;font-size:1.3rem;cursor:pointer;transition:transform .18s,box-shadow .18s;"
                                            onmouseover="this.style.transform='scale(1.18)';this.style.boxShadow='0 4px 16px rgba(0,0,0,.2)';"
                                            onmouseout="this.style.transform='';this.style.boxShadow='';">
                                        <i class="bi {{ $steps[$st]['icon'] }}"></i>
                                    </button>
                                    <button type="submit"
                                            class="btn btn-link p-0 fw-bold small text-decoration-none
                                                   {{ $idx < $currentIndex ? 'text-success' : 'text-muted' }}"
                                            style="font-size:.78rem;">
                                        {{ $steps[$st]['title'] }}
                                        @if($idx < $currentIndex)<i class="bi bi-check-lg text-success ms-1"></i>@endif
                                    </button>
                                </form>
                            @else
                                {{-- Current active step (non-clickable) or customer read-only view --}}
                                <div class="rounded-circle d-flex align-items-center justify-content-center mb-2 shadow-sm
                                            {{ $idx <= $currentIndex ? 'bg-danger text-white' : 'bg-light text-muted border' }}"
                                     style="width:50px;height:50px;font-size:1.3rem;">
                                    <i class="bi {{ $steps[$st]['icon'] }}"></i>
                                </div>
                                <span class="fw-bold small {{ $idx <= $currentIndex ? 'text-dark' : 'text-muted' }}">
                                    {{ $steps[$st]['title'] }}
                                </span>
                                @if($idx === $currentIndex)
                                    <span class="badge bg-danger mt-1">Current Step</span>
                                @endif
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            @if($isStaff)
                <p class="text-center text-muted small mt-3 mb-0">
                    <i class="bi bi-hand-index-thumb me-1"></i>
                    Click any pipeline step to instantly update the order status
                </p>
            @endif
        </div>
    @endif


    <div class="row g-4">
        <!-- Order Details & Items -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-dark text-white p-4 border-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
                    <h5 class="fw-bold mb-0"><i class="bi bi-egg-fried text-warning me-2"></i> Ordered Food Items</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Item Name</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th class="pe-4 text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderDetails as $detail)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $detail->item_name }}</div>
                                        @if($detail->special_instructions)
                                            <small class="text-danger"><i class="bi bi-info-circle me-1"></i>Note: {{ $detail->special_instructions }}</small>
                                        @endif
                                    </td>
                                    <td class="fw-semibold text-muted">${{ number_format($detail->unit_price, 2) }}</td>
                                    <td class="fw-bold text-dark">{{ $detail->quantity }}</td>
                                    <td class="pe-4 text-end fw-bold text-danger">${{ number_format($detail->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-light p-4">
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-semibold">${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Tax (5%)</span>
                                <span class="fw-semibold">${{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Delivery Fee</span>
                                <span class="fw-semibold">${{ number_format($order->delivery_fee, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold text-dark fs-5">Total Paid / Due</span>
                                <span class="fw-extrabold text-danger fs-4">${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Info Card -->
        <div class="col-lg-4">
            <!-- Customer Delivery Details -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-dark text-white p-4 border-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
                    <h5 class="fw-bold mb-0"><i class="bi bi-person-badge-fill me-2"></i> Customer Info</h5>
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <small class="text-muted text-uppercase fw-bold d-block">Customer Name</small>
                        <span class="fw-bold text-dark fs-6">{{ $order->customer->name ?? 'Guest' }}</span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted text-uppercase fw-bold d-block">Contact Phone</small>
                        <a href="tel:{{ $order->contact_phone }}" class="fw-semibold text-danger text-decoration-none">
                            <i class="bi bi-telephone-fill me-1"></i> {{ $order->contact_phone }}
                        </a>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted text-uppercase fw-bold d-block">Delivery Address</small>
                        <span class="fw-semibold text-dark"><i class="bi bi-geo-alt-fill text-danger me-1"></i> {{ $order->delivery_address }}</span>
                    </div>

                    <div class="mb-3 p-3 bg-light rounded-3 border">
                        <small class="text-muted text-uppercase fw-bold d-block mb-1"><i class="bi bi-credit-card-2-front-fill me-1"></i> Payment Information</small>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="small text-muted">Method:</span>
                            <span class="fw-bold text-dark text-uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="small text-muted">Status:</span>
                            <span class="fw-bold {{ $order->payment_status === 'paid' ? 'text-success' : 'text-warning' }} text-uppercase">
                                <i class="bi {{ $order->payment_status === 'paid' ? 'bi-check-circle-fill' : 'bi-clock-history' }} me-1"></i> {{ $order->payment_status }}
                            </span>
                        </div>
                    </div>

                    @if($order->customer_notes)
                        <div class="mb-0 p-3 bg-light rounded-3 border">
                            <small class="text-muted text-uppercase fw-bold d-block mb-1"><i class="bi bi-sticky me-1"></i> Customer Notes</small>
                            <span class="small text-dark">{{ $order->customer_notes }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Delivery Staff Info -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-dark text-white p-4 border-0" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%) !important;">
                    <h5 class="fw-bold mb-0"><i class="bi bi-truck text-success me-2"></i> Delivery Dispatch</h5>
                </div>
                <div class="card-body p-4">
                    @if($order->delivery)
                        <div class="mb-3">
                            <small class="text-muted text-uppercase fw-bold d-block">Assigned Rider</small>
                            <span class="fw-bold text-dark"><i class="bi bi-person-circle text-primary me-1"></i> {{ $order->delivery->deliveryStaff->name }}</span>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted text-uppercase fw-bold d-block">Rider Phone</small>
                            <a href="tel:{{ $order->delivery->deliveryStaff->phone }}" class="fw-semibold text-dark text-decoration-none">
                                <i class="bi bi-telephone me-1"></i> {{ $order->delivery->deliveryStaff->phone }}
                            </a>
                        </div>
                        <div>
                            <small class="text-muted text-uppercase fw-bold d-block">Dispatch Status</small>
                            <span class="badge bg-success text-uppercase">{{ str_replace('_', ' ', $order->delivery->status) }}</span>
                        </div>
                    @else
                        <div class="text-muted small">No delivery rider assigned to this order yet.</div>
                    @endif
                </div>
            </div>

            <!-- Admin / Staff Quick Status Control -->
            @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isRestaurantStaff()))
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden border-danger border-2">
                    <div class="card-header bg-danger text-white p-3">
                        <h6 class="fw-bold mb-0"><i class="bi bi-gear-fill me-2"></i> Staff Quick Order Control</h6>
                    </div>
                    <div class="card-body p-3">
                        <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Update Status</label>
                                <select name="status" class="form-select fw-semibold">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Preparing (Kitchen)</option>
                                    <option value="out_for_delivery" {{ $order->status === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger w-100 fw-bold">Update Order Status</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
