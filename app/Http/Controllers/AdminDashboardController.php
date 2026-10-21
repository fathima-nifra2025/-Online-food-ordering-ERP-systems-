<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = Customer::count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $preparingOrders = Order::where('status', 'preparing')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $outForDeliveryOrders = Order::where('status', 'out_for_delivery')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        
        $totalSales = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $availableMenuItems = MenuItem::where('is_available', true)->count();
        $totalMenuItems = MenuItem::count();

        $recentOrders = Order::with('customer', 'orderDetails')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Status breakdown for statistics badges / chart
        $orderStats = [
            'Pending' => $pendingOrders,
            'Confirmed' => Order::where('status', 'confirmed')->count(),
            'Preparing' => $preparingOrders,
            'Out for Delivery' => $outForDeliveryOrders,
            'Delivered' => $deliveredOrders,
            'Cancelled' => $cancelledOrders,
        ];

        // Monthly sales summary for recent 6 months
        $monthlySales = Order::select(
                DB::raw("strftime('%Y-%m', created_at) as month"),
                DB::raw("SUM(total_amount) as total_sales"),
                DB::raw("COUNT(*) as total_orders")
            )
            ->where('status', '!=', 'cancelled')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->take(6)
            ->get()
            ->reverse();

        $deliveryStaffCount = User::where('role', 'delivery_staff')->count();
        $restaurantStaffCount = User::where('role', 'restaurant_staff')->count();

        return view('admin.dashboard', compact(
            'totalCustomers',
            'totalOrders',
            'pendingOrders',
            'preparingOrders',
            'deliveredOrders',
            'outForDeliveryOrders',
            'cancelledOrders',
            'totalSales',
            'availableMenuItems',
            'totalMenuItems',
            'recentOrders',
            'orderStats',
            'monthlySales',
            'deliveryStaffCount',
            'restaurantStaffCount'
        ));
    }
}
