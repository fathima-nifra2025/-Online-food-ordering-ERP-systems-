<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with('user')->withCount('orders');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:customers,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'password' => 'required|string|min:8',
            'notes' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'status' => 'active',
        ]);

        Customer::create([
            'user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'] ?? 'Main City',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'Customer account created successfully.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['user', 'orders' => function ($q) {
            $q->with('orderDetails', 'delivery.deliveryStaff')->latest();
        }]);

        $totalSpent = $customer->orders->where('status', '!=', 'cancelled')->sum('total_amount');
        $completedOrdersCount = $customer->orders->where('status', 'delivered')->count();

        return view('admin.customers.show', compact('customer', 'totalSpent', 'completedOrdersCount'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $customer->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'] ?? $customer->city,
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($customer->user) {
            $customer->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
            ]);
        }

        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->user) {
            $customer->user->delete();
        } else {
            $customer->delete();
        }

        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted successfully.');
    }
}
