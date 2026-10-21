<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Delivery;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reportType = $request->input('type', 'daily');
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo = $request->input('date_to', now()->toDateString());

        // KPI Highlights
        $totalSales = Order::whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $totalOrders = Order::whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])->count();
        $deliveredOrders = Order::whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])
            ->where('status', 'delivered')->count();
        $cancelledOrders = Order::whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])
            ->where('status', 'cancelled')->count();

        // Daily Orders Report
        $dailyReport = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as delivered_count'),
                DB::raw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_count'),
                DB::raw('SUM(CASE WHEN status != "cancelled" THEN total_amount ELSE 0 END) as total_revenue')
            )
            ->whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // Monthly Sales Report
        $monthlyReport = Order::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(CASE WHEN status != "cancelled" THEN total_amount ELSE 0 END) as total_revenue'),
                DB::raw('AVG(CASE WHEN status != "cancelled" THEN total_amount ELSE 0 END) as avg_order_value')
            )
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(12)
            ->get();

        // Customer Order History Report
        $customerReport = Customer::withCount(['orders' => function ($q) use ($dateFrom, $dateTo) {
                $q->whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo]);
            }])
            ->withSum(['orders' => function ($q) use ($dateFrom, $dateTo) {
                $q->whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])
                  ->where('status', '!=', 'cancelled');
            }], 'total_amount')
            ->orderBy('orders_sum_total_amount', 'desc')
            ->take(20)
            ->get();

        // Delivery Status Report
        $deliveryReport = Delivery::with(['deliveryStaff', 'order'])
            ->whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])
            ->latest()
            ->get();

        // Most Ordered Food Items
        $topDishes = OrderDetail::select(
                'item_name',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])
            ->groupBy('item_name')
            ->orderBy('total_quantity', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports.index', compact(
            'reportType',
            'dateFrom',
            'dateTo',
            'totalSales',
            'totalOrders',
            'deliveredOrders',
            'cancelledOrders',
            'dailyReport',
            'monthlyReport',
            'customerReport',
            'deliveryReport',
            'topDishes'
        ));
    }

    // ─── Sales History (Billing Log) ────────────────────────────────────────────
    public function salesHistory(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->input('date_to',   now()->toDateString());
        $status   = $request->input('status');
        $search   = $request->input('search');

        $query = Order::with(['customer', 'orderDetails'])
            ->whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo]);

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('contact_phone', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        $orders = $query->latest()->paginate(20)->appends($request->all());

        // Totals for filtered results (unpaginated)
        $totalsQuery = Order::whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])
            ->where('status', '!=', 'cancelled');
        if ($status && $status !== 'cancelled') {
            $totalsQuery->where('status', $status);
        }

        $grandTotal   = (clone $totalsQuery)->sum('total_amount');
        $grandTax     = (clone $totalsQuery)->sum('tax_amount');
        $grandDelivery= (clone $totalsQuery)->sum('delivery_fee');
        $grandSubtotal= (clone $totalsQuery)->sum('subtotal');
        $totalCount   = Order::whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])->count();

        return view('admin.reports.sales_history', compact(
            'orders', 'dateFrom', 'dateTo', 'status', 'search',
            'grandTotal', 'grandTax', 'grandDelivery', 'grandSubtotal', 'totalCount'
        ));
    }

    // ─── Print Bill / Invoice PDF ────────────────────────────────────────────────
    public function printBill(Order $order)
    {
        $order->load(['customer', 'orderDetails.menuItem', 'delivery.deliveryStaff']);
        return view('admin.reports.bill_print', compact('order'));
    }
}
