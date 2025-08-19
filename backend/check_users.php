<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "Checking existing users...\n";
$users = User::all();
echo "Total users: " . $users->count() . "\n";

foreach ($users as $user) {
    echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}\n";
}

// If no admin user exists, create one
$admin = User::where('email', 'admin@indopet.com')->first();
if (!$admin) {
    echo "Creating admin user...\n";
    $admin = User::create([
        'name' => 'Admin',
        'email' => 'admin@indopet.com',
        'password' => Hash::make('password'),
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);
    echo "Admin user created: ID {$admin->id}\n";
} else {
    echo "Admin user exists: ID {$admin->id}\n";
}

// If no customer user exists, create one
$customer = User::where('email', 'customer@indopet.com')->first();
if (!$customer) {
    echo "Creating customer user...\n";
    $customer = User::create([
        'name' => 'Customer',
        'email' => 'customer@indopet.com',
        'password' => Hash::make('password'),
        'role' => 'customer',
        'email_verified_at' => now(),
        'phone' => '081234567890',
        'address' => 'Jakarta, Indonesia',
    ]);
    echo "Customer user created: ID {$customer->id}\n";
} else {
    echo "Customer user exists: ID {$customer->id}\n";
}

echo "\nLogin credentials:\n";
echo "Admin: admin@indopet.com / password\n";
echo "Customer: customer@indopet.com / password\n";
