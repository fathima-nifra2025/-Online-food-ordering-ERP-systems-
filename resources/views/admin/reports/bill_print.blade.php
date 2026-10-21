<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }} – FoodERP</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* ═══ ROOT VARIABLES ═══════════════════════════════════════════════════════ */
        :root {
            --primary:   #ff5e3a;
            --dark:      #0f172a;
            --accent:    #1e293b;
            --muted:     #64748b;
            --border:    #e2e8f0;
            --light:     #f8fafc;
            --success:   #22c55e;
            --warning:   #f59e0b;
        }

        /* ═══ RESET / BASE ═════════════════════════════════════════════════════════ */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
            color: #334155;
            min-height: 100vh;
            padding: 30px 20px;
        }

        h1,h2,h3,h4,h5,h6 { font-family: 'Outfit', sans-serif; }

        /* ═══ INVOICE WRAPPER ══════════════════════════════════════════════════════ */
        .invoice-wrapper {
            max-width: 820px;
            margin: 0 auto;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,.12);
        }

        /* ═══ HEADER BANNER ════════════════════════════════════════════════════════ */
        .invoice-header {
            background: linear-gradient(135deg, var(--dark) 0%, var(--accent) 100%);
            color: #fff;
            padding: 40px 50px 30px;
            position: relative;
            overflow: hidden;
        }

        .invoice-header::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 200px; height: 200px;
            background: rgba(255,255,255,.04);
            border-radius: 50%;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .brand-logo .icon {
            font-size: 2.4rem;
            color: var(--primary);
            text-shadow: 0 0 20px rgba(255,94,58,.6);
        }

        .brand-logo h2 {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .brand-logo span { color: var(--primary); }

        .invoice-meta {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 16px;
        }

        .invoice-meta .invoice-title {
            font-size: 2.4rem;
            font-weight: 800;
            letter-spacing: -1px;
            opacity: .9;
        }

        .invoice-meta .order-id {
            font-size: 1rem;
            opacity: .6;
            margin-top: 4px;
        }

        .status-pill {
            display: inline-block;
            padding: 6px 18px;
            border-radius: 50px;
            font-weight: 700;
            font-size: .85rem;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        /* ═══ INFO SECTION (2-col grid) ════════════════════════════════════════════ */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            border-bottom: 2px solid var(--border);
        }

        .info-block {
            padding: 28px 40px;
            border-right: 1px solid var(--border);
        }

        .info-block:last-child { border-right: none; }

        .info-block h6 {
            font-size: .72rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 12px;
            font-weight: 600;
        }

        .info-block p {
            font-size: .95rem;
            line-height: 1.7;
            color: #1e293b;
            font-weight: 500;
        }

        .info-block strong { color: var(--dark); }

        /* ═══ ITEMS TABLE ══════════════════════════════════════════════════════════ */
        .items-section { padding: 30px 40px; }

        .items-section h5 {
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: var(--muted);
            margin-bottom: 16px;
        }

        table.items-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.items-table thead th {
            background: var(--light);
            padding: 12px 16px;
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--muted);
            border-top: 2px solid var(--border);
            border-bottom: 2px solid var(--border);
        }

        table.items-table tbody td {
            padding: 14px 16px;
            font-size: .93rem;
            border-bottom: 1px solid var(--border);
            color: #334155;
        }

        table.items-table tbody tr:last-child td { border-bottom: none; }

        table.items-table tbody tr:hover td { background: #fafafa; }

        .item-name { font-weight: 600; color: var(--dark); }
        .item-note { font-size: .78rem; color: var(--primary); margin-top: 2px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* ═══ TOTALS SECTION ═══════════════════════════════════════════════════════ */
        .totals-section {
            padding: 0 40px 30px;
            display: flex;
            justify-content: flex-end;
        }

        .totals-box {
            width: 320px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 18px;
            border-bottom: 1px solid var(--border);
            font-size: .92rem;
        }

        .totals-row:last-child { border-bottom: none; }

        .totals-row.grand-total {
            background: var(--dark);
            color: #fff;
            padding: 14px 18px;
        }

        .totals-row.grand-total .amount {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--primary);
        }

        .totals-row .label { color: var(--muted); font-weight: 500; }
        .totals-row .amount { font-weight: 700; color: var(--dark); }

        /* ═══ PAYMENT BADGE ════════════════════════════════════════════════════════ */
        .payment-section {
            padding: 0 40px 28px;
        }

        .payment-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: .9rem;
        }

        /* ═══ FOOTER ═══════════════════════════════════════════════════════════════ */
        .invoice-footer {
            background: var(--light);
            border-top: 2px solid var(--border);
            padding: 24px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .invoice-footer p { font-size: .82rem; color: var(--muted); }

        /* ═══ ACTION BAR (hidden on print) ════════════════════════════════════════ */
        .action-bar {
            max-width: 820px;
            margin: 20px auto 0;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            border-radius: 50px;
            font-weight: 700;
            font-size: .95rem;
            cursor: pointer;
            border: none;
            transition: all .2s;
            text-decoration: none;
        }

        .btn-print {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 15px rgba(255,94,58,.35);
        }

        .btn-print:hover {
            background: #ff471a;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255,94,58,.45);
            color: #fff;
        }

        .btn-back {
            background: #fff;
            color: var(--dark);
            border: 2px solid var(--border);
        }

        .btn-back:hover {
            background: var(--light);
            transform: translateY(-2px);
            color: var(--dark);
        }

        /* ═══ PRINT STYLES ═════════════════════════════════════════════════════════ */
        @media print {
            body {
                background: #fff !important;
                padding: 0 !important;
            }

            .action-bar { display: none !important; }

            .invoice-wrapper {
                box-shadow: none !important;
                border-radius: 0 !important;
                max-width: 100% !important;
            }

            @page {
                size: A4;
                margin: 10mm 12mm;
            }
        }
    </style>
</head>
<body>

{{-- ── Action Bar (screen only) ── --}}
<div class="action-bar">
    <button onclick="window.print()" class="btn-action btn-print">
        <i class="bi bi-printer-fill"></i> Print / Save as PDF
    </button>
    <a href="{{ route('admin.sales.history') }}" class="btn-action btn-back">
        <i class="bi bi-arrow-left"></i> Back to Sales History
    </a>
    <a href="{{ route('orders.show', $order->id) }}" class="btn-action btn-back">
        <i class="bi bi-eye"></i> View Order Details
    </a>
</div>

{{-- ── Invoice Document ── --}}
<div class="invoice-wrapper">

    {{-- Header --}}
    <div class="invoice-header">
        <div class="brand-logo">
            <i class="bi bi-egg-fried icon"></i>
            <h2>Food<span>ERP</span></h2>
        </div>
        <div class="invoice-meta">
            <div>
                <div class="invoice-title">INVOICE</div>
                <div class="order-id">{{ $order->order_number }}</div>
                <div style="margin-top:10px; font-size:.85rem; opacity:.6;">
                    <i class="bi bi-calendar3 me-1"></i>
                    Issued: {{ $order->created_at->format('F d, Y  h:i A') }}
                </div>
            </div>
            <div style="text-align:right;">
                @php
                    $statusStyle = match($order->status) {
                        'delivered'        => 'background:#22c55e; color:#fff;',
                        'cancelled'        => 'background:#ef4444; color:#fff;',
                        'out_for_delivery' => 'background:#3b82f6; color:#fff;',
                        'preparing'        => 'background:#f59e0b; color:#000;',
                        'confirmed'        => 'background:#06b6d4; color:#000;',
                        default            => 'background:#94a3b8; color:#fff;',
                    };
                @endphp
                <span class="status-pill" style="{{ $statusStyle }}">
                    {{ str_replace('_', ' ', $order->status) }}
                </span>
                <div style="margin-top:12px; font-size:.8rem; opacity:.55;">
                    <i class="bi bi-shield-check me-1"></i> Payment:
                    {{ ucfirst($order->payment_status) }} via {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                </div>
            </div>
        </div>
    </div>

    {{-- Billing Info Grid --}}
    <div class="info-grid">
        <div class="info-block">
            <h6><i class="bi bi-person-fill me-1"></i> Bill To (Customer)</h6>
            <p>
                <strong>{{ $order->customer->name ?? 'Guest Customer' }}</strong><br>
                @if($order->customer?->email)
                    <i class="bi bi-envelope me-1" style="color:var(--muted);"></i>{{ $order->customer->email }}<br>
                @endif
                <i class="bi bi-telephone me-1" style="color:var(--muted);"></i>{{ $order->contact_phone }}<br>
                <i class="bi bi-geo-alt me-1" style="color:var(--primary);"></i>{{ $order->delivery_address }}
            </p>
        </div>
        <div class="info-block">
            <h6><i class="bi bi-shop me-1"></i> Billed From (Restaurant)</h6>
            <p>
                <strong>FoodERP Restaurant</strong><br>
                <i class="bi bi-envelope me-1" style="color:var(--muted);"></i>info@fooderp.com<br>
                <i class="bi bi-telephone me-1" style="color:var(--muted);"></i>+1 (800) 123-4567<br>
                <i class="bi bi-geo-alt me-1" style="color:var(--primary);"></i>123 Food Street, Main City
            </p>
        </div>
        @if($order->delivery)
        <div class="info-block" style="border-top:1px solid var(--border);">
            <h6><i class="bi bi-truck me-1"></i> Delivery Rider</h6>
            <p>
                <strong>{{ $order->delivery->deliveryStaff->name ?? 'N/A' }}</strong><br>
                <i class="bi bi-telephone me-1" style="color:var(--muted);"></i>{{ $order->delivery->deliveryStaff->phone ?? '—' }}<br>
                @if($order->delivery->delivered_at)
                    <i class="bi bi-check-circle me-1" style="color:var(--success);"></i>
                    Delivered: {{ $order->delivery->delivered_at->format('M d, Y h:i A') }}
                @else
                    <span style="color:var(--warning);"><i class="bi bi-clock me-1"></i> In Transit</span>
                @endif
            </p>
        </div>
        <div class="info-block" style="border-top:1px solid var(--border);">
        @else
        <div class="info-block" style="grid-column:span 1; border-top:1px solid var(--border);">
        @endif
            <h6><i class="bi bi-sticky me-1"></i> Order Notes</h6>
            <p>{{ $order->customer_notes ?: 'No special instructions provided.' }}</p>
        </div>
    </div>

    {{-- Ordered Items Table --}}
    <div class="items-section">
        <h5><i class="bi bi-bag-check me-2"></i>Ordered Items</h5>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width:40px;">#</th>
                    <th>Food Item</th>
                    <th class="text-center">Qty</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderDetails as $idx => $detail)
                <tr>
                    <td style="color:var(--muted); font-weight:600;">{{ $idx + 1 }}</td>
                    <td>
                        <div class="item-name">{{ $detail->item_name }}</div>
                        @if($detail->special_instructions)
                            <div class="item-note"><i class="bi bi-info-circle me-1"></i>{{ $detail->special_instructions }}</div>
                        @endif
                    </td>
                    <td class="text-center">
                        <span style="background:#f1f5f9; padding:3px 12px; border-radius:20px; font-weight:700; font-size:.9rem;">
                            {{ $detail->quantity }}
                        </span>
                    </td>
                    <td class="text-right" style="color:var(--muted);">${{ number_format($detail->unit_price, 2) }}</td>
                    <td class="text-right" style="font-weight:700; color:var(--dark);">${{ number_format($detail->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Totals Box --}}
    <div class="totals-section">
        <div class="totals-box">
            <div class="totals-row">
                <span class="label">Subtotal</span>
                <span class="amount">${{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="totals-row">
                <span class="label">Tax (5%)</span>
                <span class="amount" style="color:var(--warning);">${{ number_format($order->tax_amount, 2) }}</span>
            </div>
            <div class="totals-row">
                <span class="label">Delivery Fee</span>
                <span class="amount">${{ number_format($order->delivery_fee, 2) }}</span>
            </div>
            <div class="totals-row grand-total">
                <span style="font-weight:700; font-size:1rem;">TOTAL DUE</span>
                <span class="amount">${{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Payment Status Badge --}}
    <div class="payment-section">
        @if($order->payment_status === 'paid')
            <div class="payment-badge" style="background:#dcfce7; color:#166534; border:1.5px solid #bbf7d0;">
                <i class="bi bi-patch-check-fill" style="font-size:1.1rem;"></i>
                PAYMENT RECEIVED — {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}
            </div>
        @else
            <div class="payment-badge" style="background:#fef9c3; color:#854d0e; border:1.5px solid #fde68a;">
                <i class="bi bi-clock-history" style="font-size:1.1rem;"></i>
                PAYMENT PENDING — {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}
            </div>
        @endif
    </div>

    {{-- Footer --}}
    <div class="invoice-footer">
        <div>
            <p style="font-weight:700; color:var(--dark); margin-bottom:2px;">Thank you for your order! 🍔</p>
            <p>Questions? Contact us at <strong>info@fooderp.com</strong> or call <strong>+1 (800) 123-4567</strong></p>
        </div>
        <div style="text-align:right;">
            <p style="font-weight:700; color:var(--dark);">Invoice #{{ $order->order_number }}</p>
            <p>Generated: {{ now()->format('M d, Y h:i A') }}</p>
        </div>
    </div>

</div>{{-- /.invoice-wrapper --}}

</body>
</html>
