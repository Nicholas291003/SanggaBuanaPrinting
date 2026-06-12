<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::create([
            'name' => 'user test',
            'email' => 'usertest@gmail.com',
            'phone' => '082289235671',
            'total_transactions' => 2,
            'total_spend' => 400000,
        ]);
    }
}
