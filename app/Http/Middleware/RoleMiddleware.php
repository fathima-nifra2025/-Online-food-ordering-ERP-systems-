<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this area.');
        }

        $user = Auth::user();

        if (!in_array($user->role, $roles)) {
            // Redirect user to their own designated dashboard if trying to access unauthorized area
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard')->with('error', 'Unauthorized access attempt.'),
                'restaurant_staff' => redirect()->route('staff.dashboard')->with('error', 'Unauthorized access attempt.'),
                'delivery_staff' => redirect()->route('delivery.dashboard')->with('error', 'Unauthorized access attempt.'),
                'customer' => redirect()->route('customer.orders')->with('error', 'Unauthorized access attempt.'),
                default => redirect()->route('home')->with('error', 'Unauthorized access.'),
            };
        }

        return $next($request);
    }
}
