<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Sangga Buana',
            'email' => 'admin@sanggabuana.com',
            'password' => Hash::make('password123'),
            'role' => 'Administrator',
        ]);

        User::create([
            'name' => 'User Sangga Buana 01',
            'email' => 'User@sanggabuana.com',
            'password' => Hash::make('password123'),
            'role' => 'User',
        ]);
    }
}
