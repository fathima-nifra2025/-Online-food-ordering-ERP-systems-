<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ReportController;

// Public Front Page / Food Menu
Route::get('/', [MenuItemController::class, 'publicMenu'])->name('home');
Route::get('/menu', [MenuItemController::class, 'publicMenu'])->name('menu.index');

// Authentication Routes are provided by Auth::routes() at the bottom

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{menuItem}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

// Customer Order Operations
Route::post('/orders/place', [OrderController::class, 'store'])->name('orders.place');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

Route::middleware(['auth'])->group(function () {
    // Customer Dashboard / Order History
    Route::get('/my-orders', [OrderController::class, 'customerOrders'])->name('customer.orders');
});

// Admin Routes (Role: admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Menu Categories Management
    Route::resource('categories', CategoryController::class);

    // Food Menu Items Management
    Route::resource('menu', MenuItemController::class);
    Route::patch('/menu/{menuItem}/toggle-availability', [MenuItemController::class, 'toggleAvailability'])->name('menu.toggle');

    // Customers Management
    Route::resource('customers', CustomerController::class);

    // Order Management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

    // Delivery Management
    Route::get('/deliveries', [DeliveryController::class, 'index'])->name('deliveries.index');
    Route::post('/deliveries/assign', [DeliveryController::class, 'assign'])->name('deliveries.assign');

    // Reports Module
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/sales-history', [ReportController::class, 'salesHistory'])->name('sales.history');
    Route::get('/orders/{order}/print-bill', [ReportController::class, 'printBill'])->name('orders.print-bill');
});

// Restaurant Staff Routes (Role: restaurant_staff, admin)
Route::middleware(['auth', 'role:restaurant_staff,admin'])->prefix('staff')->as('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboardController::class, 'index'])->name('dashboard');
    Route::patch('/orders/{order}/status', [StaffDashboardController::class, 'updateOrderStatus'])->name('orders.status');
});

// Delivery Staff Routes (Role: delivery_staff, admin)
Route::middleware(['auth', 'role:delivery_staff,admin'])->prefix('delivery')->as('delivery.')->group(function () {
    Route::get('/dashboard', [DeliveryController::class, 'deliveryDashboard'])->name('dashboard');
    Route::patch('/status/{delivery}', [DeliveryController::class, 'updateDeliveryStatus'])->name('status.update');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
