<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Illuminate\Database\Console\Seeds\SeederMakeCommand;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CustomerSeeder::class,
            ProductSeeder::class,
            OrderSeeder::class,
            BranchSeeder::class,
            DesignArchiveSeeder::class,
        ]);
    }
}
