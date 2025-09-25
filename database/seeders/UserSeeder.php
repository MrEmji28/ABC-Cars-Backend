<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'John Seller',
            'email' => 'seller@test.com',
            'password' => Hash::make('password'),
            'phone' => '+1234567890',
            'address' => '123 Main St, New York, NY',
        ]);

        User::create([
            'name' => 'Jane Buyer',
            'email' => 'buyer@test.com',
            'password' => Hash::make('password'),
            'phone' => '+0987654321',
            'address' => '456 Oak Ave, Los Angeles, CA',
        ]);
    }
}
