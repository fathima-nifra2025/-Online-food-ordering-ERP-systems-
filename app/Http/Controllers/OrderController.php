<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Delivery;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // Customer Storefront: Place Order
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'delivery_address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'payment_method' => 'required|in:cash,card,online',
            'customer_notes' => 'nullable|string',
        ]);

        $user = Auth::user();

        DB::beginTransaction();
        try {
            // Find or create customer record
            if ($user && $user->isCustomer()) {
                $customer = $user->customer;
                if (!$customer) {
                    $customer = Customer::create([
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $validated['phone'],
                        'address' => $validated['delivery_address'],
                        'city' => $validated['city'] ?? 'Main City',
                    ]);
                } else {
                    $customer->update([
                        'phone' => $validated['phone'],
                        'address' => $validated['delivery_address'],
                        'city' => $validated['city'] ?? $customer->city,
                    ]);
                }
            } else {
                // Guest or created during checkout
                $customer = Customer::create([
                    'user_id' => $user ? $user->id : null,
                    'name' => $validated['name'],
                    'email' => $user ? $user->email : null,
                    'phone' => $validated['phone'],
                    'address' => $validated['delivery_address'],
                    'city' => $validated['city'] ?? 'Main City',
                ]);
            }

            $totals = CartController::calculateTotals($cart);
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $customer->id,
                'subtotal' => $totals['subtotal'],
                'tax_amount' => $totals['tax'],
                'delivery_fee' => $totals['delivery_fee'],
                'total_amount' => $totals['total'],
                'status' => 'pending',
                'payment_status' => $validated['payment_method'] === 'cash' ? 'pending' : 'paid',
                'payment_method' => $validated['payment_method'],
                'delivery_address' => $validated['delivery_address'],
                'contact_phone' => $validated['phone'],
                'customer_notes' => $validated['customer_notes'] ?? null,
            ]);

            foreach ($cart as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item['id'],
                    'item_name' => $item['name'],
                    'unit_price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'special_instructions' => $item['special_instructions'] ?? null,
                ]);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully! Order #' . $order->order_number);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to place order. Please try again. ' . $e->getMessage());
        }
    }

    // Customer: Order History
    public function customerOrders()
    {
        $user = Auth::user();
        if (!$user->customer) {
            $orders = collect();
        } else {
            $orders = Order::where('customer_id', $user->customer->id)
                ->with('orderDetails')
                ->latest()
                ->paginate(10);
        }

        return view('customer.orders', compact('orders'));
    }

    // Common Detail View (Customer / Admin / Staff)
    public function show(Order $order)
    {
        $user = Auth::user();

        // Customer can only view their own order
        if ($user && $user->isCustomer()) {
            if ($user->customer && $order->customer_id !== $user->customer->id) {
                return redirect()->route('customer.orders')->with('error', 'Unauthorized access.');
            }
        }

        $order->load(['customer', 'orderDetails.menuItem', 'delivery.deliveryStaff']);
        $deliveryStaffList = User::where('role', 'delivery_staff')->where('status', 'active')->get();

        return view('orders.show', compact('order', 'deliveryStaffList'));
    }

    // Admin & Staff: All Orders List View with Filters
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'orderDetails', 'delivery.deliveryStaff']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('contact_phone', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(15);
        $deliveryStaffList = User::where('role', 'delivery_staff')->where('status', 'active')->get();

        return view('admin.orders.index', compact('orders', 'deliveryStaffList'));
    }

    // Status Updates from Admin / Kitchen Staff / Delivery
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,out_for_delivery,delivered,cancelled',
        ]);

        $newStatus = $request->status;
        $order->update(['status' => $newStatus]);

        if ($newStatus === 'delivered') {
            $order->update(['payment_status' => 'paid']);
        }

        // Sync associated delivery record status if exists
        if ($order->delivery) {
            $deliveryStatus = match ($newStatus) {
                'out_for_delivery' => 'out_for_delivery',
                'delivered' => 'delivered',
                default => $order->delivery->status,
            };

            $updateData = ['status' => $deliveryStatus];
            if ($newStatus === 'delivered') {
                $updateData['delivered_at'] = now();
            }

            $order->delivery->update($updateData);
        }

        return back()->with('success', "Order #{$order->order_number} status updated to " . ucfirst(str_replace('_', ' ', $newStatus)) . ".");
    }
}
