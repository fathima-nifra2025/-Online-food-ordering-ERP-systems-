<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    public function index(Request $request)
    {
        $pendingOrders = Order::with('orderDetails.menuItem', 'customer')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        $confirmedOrders = Order::with('orderDetails.menuItem', 'customer')
            ->where('status', 'confirmed')
            ->orderBy('created_at', 'asc')
            ->get();

        $preparingOrders = Order::with('orderDetails.menuItem', 'customer')
            ->where('status', 'preparing')
            ->orderBy('created_at', 'asc')
            ->get();

        $completedToday = Order::whereDate('updated_at', today())
            ->whereIn('status', ['out_for_delivery', 'delivered'])
            ->count();

        return view('staff.dashboard', compact(
            'pendingOrders',
            'confirmedOrders',
            'preparingOrders',
            'completedToday'
        ));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:confirmed,preparing,out_for_delivery,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', "Order #{$order->order_number} updated to " . ucfirst(str_replace('_', ' ', $request->status)) . ".");
    }
}
