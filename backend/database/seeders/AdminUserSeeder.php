<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@indopet.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create customer user
        User::create([
            'name' => 'Customer',
            'email' => 'customer@indopet.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'email_verified_at' => now(),
            'phone' => '081234567890',
            'address' => 'Jakarta, Indonesia',
        ]);
    }
}
