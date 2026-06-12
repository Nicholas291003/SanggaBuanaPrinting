<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'banner',
            'base_price' => 200000,
            'category' => 'Signage',
            'description' => 'Cetak banner luar ruangan (outdoor) ukuran kustom bahan flexy cina super tebal.',
            'image_path' => null,
            'status' => 'Tersedia',
        ]);

        Product::create([
            'name' => 'Kartu Nama Eksklusif',
            'base_price' => 35000,
            'category' => 'Stationery',
            'description' => 'Cetak kartu nama isi 100 pcs per box, kertas Art Carton 260gr dengan laminasi doff/glossy.',
            'image_path' => null,
            'status' => 'Tersedia',
        ]);

        Product::create([
            'name' => 'Brosur Marketing A4',
            'base_price' => 120000,
            'category' => 'Marketing',
            'description' => 'Cetak brosur lipat 3, bahan Art Paper 150gr, cetak full color 2 sisi resolusi tinggi.',
            'image_path' => null,
            'status' => 'Tersedia',
        ]);

        Product::create([
            'name' => 'Kemasan Box Katering',
            'base_price' => 2500,
            'category' => 'Kemasan & Packaging',
            'description' => 'Packaging box makanan ukuran kustom dengan laminasi foodgrade anti minyak sirkulasi.',
            'image_path' => null,
            'status' => 'Tersedia',
        ]);
    }
}
