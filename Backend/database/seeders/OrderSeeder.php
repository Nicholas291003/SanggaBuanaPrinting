<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Customer;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer = Customer::first();

        Order::create([
            'customer_id' => $user->id ?? 1,
            'customer_name' => 'user test',
            'customer_phone' => '081234567890',
            'customer_email' => 'usertest@gmail.com',
            'service_name' => 'banner',
            'total_price' => 200000,
            'status' => 'Sedang Diproses',
        ]);
    }
}
