<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

// Create / update Admin user
$admin = User::updateOrCreate(
    ['email' => 'admin@gmail.com'],
    [
        'name'     => 'Admin User',
        'password' => Hash::make('123'),
        'role'     => 'admin',
        'status'   => 'active',
        'phone'    => '0000000000',
    ]
);
echo "Admin: {$admin->email} | role: {$admin->role}\n";

// Create / update Customer user
$cust = User::updateOrCreate(
    ['email' => 'customer@gmail.com'],
    [
        'name'     => 'Demo Customer',
        'password' => Hash::make('1234'),
        'role'     => 'customer',
        'status'   => 'active',
        'phone'    => '1111111111',
    ]
);
echo "Customer: {$cust->email} | role: {$cust->role}\n";

// Create customer profile if missing
$cust->refresh();
if (!$cust->customer) {
    Customer::create([
        'user_id' => $cust->id,
        'name'    => $cust->name,
        'email'   => $cust->email,
        'phone'   => $cust->phone,
        'address' => '123 Demo Street',
        'city'    => 'Demo City',
    ]);
    echo "Customer profile created.\n";
} else {
    echo "Customer profile already exists.\n";
}

echo "\nDone! Both demo accounts ready.\n";
