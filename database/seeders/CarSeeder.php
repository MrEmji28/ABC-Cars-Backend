<?php

namespace Database\Seeders;

use App\Models\Car;
use Illuminate\Database\Seeder;

class CarSeeder extends Seeder
{
    public function run(): void
    {
        Car::create([
            'user_id' => 1,
            'title' => '2020 Toyota Camry',
            'description' => 'Well maintained sedan, perfect for daily commute',
            'brand' => 'Toyota',
            'model' => 'Camry',
            'year' => 2020,
            'price' => 25000,
            'rental_price_per_day' => 50,
            'type' => 'both',
        ]);

        Car::create([
            'user_id' => 1,
            'title' => '2019 Honda Civic',
            'description' => 'Fuel efficient compact car',
            'brand' => 'Honda',
            'model' => 'Civic',
            'year' => 2019,
            'price' => 22000,
            'type' => 'sale',
        ]);

        Car::create([
            'user_id' => 1,
            'title' => '2021 BMW X5',
            'description' => 'Luxury SUV available for rental',
            'brand' => 'BMW',
            'model' => 'X5',
            'year' => 2021,
            'price' => 65000,
            'rental_price_per_day' => 120,
            'type' => 'rental',
        ]);
    }
}
