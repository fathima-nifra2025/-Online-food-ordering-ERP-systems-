<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $totals = $this->calculateTotals($cart);

        return view('customer.cart', compact('cart', 'totals'));
    }

    public function add(Request $request, MenuItem $menuItem)
    {
        if (!$menuItem->is_available) {
            return back()->with('error', 'Sorry, this menu item is currently unavailable.');
        }

        $quantity = max(1, (int) $request->input('quantity', 1));
        $instructions = $request->input('special_instructions', '');

        $cart = session()->get('cart', []);

        if (isset($cart[$menuItem->id])) {
            $cart[$menuItem->id]['quantity'] += $quantity;
            if ($instructions) {
                $cart[$menuItem->id]['special_instructions'] = $instructions;
            }
        } else {
            $cart[$menuItem->id] = [
                'id' => $menuItem->id,
                'name' => $menuItem->name,
                'price' => (float) $menuItem->price,
                'image' => $menuItem->image,
                'quantity' => $quantity,
                'special_instructions' => $instructions,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', "{$menuItem->name} added to cart!");
    }

    public function update(Request $request, $id)
    {
        $quantity = (int) $request->input('quantity', 1);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($quantity <= 0) {
                unset($cart[$id]);
            } else {
                $cart[$id]['quantity'] = $quantity;
            }
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $totals = $this->calculateTotals($cart);
        $user = Auth::user();
        $customer = $user ? $user->customer : null;

        return view('customer.checkout', compact('cart', 'totals', 'user', 'customer'));
    }

    public static function calculateTotals(array $cart): array
    {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $tax = round($subtotal * 0.05, 2); // 5% tax
        $deliveryFee = $subtotal > 0 ? 3.50 : 0.00;
        $grandTotal = round($subtotal + $tax + $deliveryFee, 2);

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'delivery_fee' => $deliveryFee,
            'total' => $grandTotal,
        ];
    }
}
