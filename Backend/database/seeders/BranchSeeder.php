<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        Branch::create([
            'name' => 'Sangga Buana Pusat',
            'description' => 'Pusat produksi utama dan administrasi keuangan',
            'address' => 'Jl. Raya Sangga Buana No. 12, Kompleks Percetakan Inti, Kota Surabaya',
            'opening_time' => '08:00:00',
            'closing_time' => '21:00:00',
            'phone' => '6281234567890',
            'status' => 'Buka',
        ]);
    }
}
