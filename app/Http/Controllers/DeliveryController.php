<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    // Admin: List all deliveries & assignment board
    public function index(Request $request)
    {
        $query = Delivery::with(['order.customer', 'deliveryStaff']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('staff_id')) {
            $query->where('delivery_staff_id', $request->staff_id);
        }

        $deliveries = $query->latest()->paginate(15);
        $deliveryStaffList = User::where('role', 'delivery_staff')->where('status', 'active')->get();

        // Orders waiting for delivery staff assignment
        $unassignedOrders = Order::doesntHave('delivery')
            ->whereIn('status', ['confirmed', 'preparing', 'out_for_delivery'])
            ->with('customer')
            ->get();

        return view('admin.deliveries.index', compact('deliveries', 'deliveryStaffList', 'unassignedOrders'));
    }

    // Admin: Assign staff to order
    public function assign(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'delivery_staff_id' => 'required|exists:users,id',
            'delivery_notes' => 'nullable|string',
        ]);

        $order = Order::findOrFail($validated['order_id']);

        $delivery = Delivery::updateOrCreate(
            ['order_id' => $order->id],
            [
                'delivery_staff_id' => $validated['delivery_staff_id'],
                'status' => 'assigned',
                'assigned_at' => now(),
                'delivery_notes' => $validated['delivery_notes'] ?? null,
            ]
        );

        if ($order->status === 'pending' || $order->status === 'confirmed') {
            $order->update(['status' => 'preparing']);
        }

        return back()->with('success', "Order #{$order->order_number} successfully assigned to delivery staff.");
    }

    // Delivery Staff Portal Dashboard
    public function deliveryDashboard(Request $request)
    {
        $user = Auth::user();

        $activeDeliveries = Delivery::with(['order.customer', 'order.orderDetails'])
            ->where('delivery_staff_id', $user->id)
            ->whereIn('status', ['assigned', 'picked_up', 'out_for_delivery'])
            ->latest()
            ->get();

        $completedDeliveries = Delivery::with(['order.customer', 'order.orderDetails'])
            ->where('delivery_staff_id', $user->id)
            ->where('status', 'delivered')
            ->latest()
            ->take(10)
            ->get();

        $todayDeliveredCount = Delivery::where('delivery_staff_id', $user->id)
            ->where('status', 'delivered')
            ->whereDate('delivered_at', today())
            ->count();

        return view('delivery.dashboard', compact('activeDeliveries', 'completedDeliveries', 'todayDeliveredCount'));
    }

    // Delivery Staff: Update status
    public function updateDeliveryStatus(Request $request, Delivery $delivery)
    {
        $user = Auth::user();

        // Security check: must be assigned staff or admin
        if ($user->isDeliveryStaff() && $delivery->delivery_staff_id !== $user->id) {
            return back()->with('error', 'Unauthorized access.');
        }

        $validated = $request->validate([
            'status' => 'required|in:assigned,picked_up,out_for_delivery,delivered',
            'delivery_notes' => 'nullable|string',
        ]);

        $status = $validated['status'];
        $updateData = ['status' => $status];

        if ($status === 'picked_up' && !$delivery->picked_up_at) {
            $updateData['picked_up_at'] = now();
        } elseif ($status === 'delivered' && !$delivery->delivered_at) {
            $updateData['delivered_at'] = now();
        }

        if (isset($validated['delivery_notes'])) {
            $updateData['delivery_notes'] = $validated['delivery_notes'];
        }

        $delivery->update($updateData);

        // Synchronize main Order status
        if ($status === 'out_for_delivery') {
            $delivery->order->update(['status' => 'out_for_delivery']);
        } elseif ($status === 'delivered') {
            $delivery->order->update([
                'status' => 'delivered',
                'payment_status' => 'paid',
            ]);
        }

        return back()->with('success', "Delivery status updated to " . ucfirst(str_replace('_', ' ', $status)) . ".");
    }
}
