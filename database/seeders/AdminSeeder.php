<?php
// database/seeders/AdminSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@eshop.com',
            'password' => Hash::make('admin123'),
            'is_admin' => 1,
            'email_verified_at' => now(),
        ]);

        echo "Admin user created successfully!\n";
        echo "Email: admin@eshop.com\n";
        echo "Password: admin123\n";
    }
}

// Run this seeder with: php artisan db:seed --class=AdminSeeder