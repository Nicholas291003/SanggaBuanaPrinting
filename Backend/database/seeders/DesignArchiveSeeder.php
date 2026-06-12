<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DesignArchive;
use App\Models\Customer;

class DesignArchiveSeeder extends Seeder
{
    public function run(): void
    {
        $customer = Customer::first();

        DesignArchive::create([
            'customer_id' => $customer->id,
            'file_name' => 'Desain_Banner_4x4_UserTest.pdf',
            'file_size' => '14.5 MB',
            'file_path' => 'archives/Desain_Banner_4x4_UserTest.pdf',
        ]);
    }
}
