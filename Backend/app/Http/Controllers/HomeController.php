<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    /**
     * Halaman Utama Publik (Landing Page / Welcome)
     */
    public function index()
    {
        $products = Product::where('status', 'Tersedia')->get();
        return view('welcome', compact('products'));
    }

    /**
     * Halaman Daftar Cabang Toko Operasional 
     */
    public function cabang()
    {
        // Mengambil semua baris data cabang yang terdaftar di database
        $branches = Branch::all();

        // Mengirim data ke komponen view khusus cabang publik
        return view('public.cabang', compact('branches'));
    }

    /**
     * Halaman Informasi Kontak Resmi Percetakan
     */
    public function kontak()
    {
        return view('public.kontak');
    }
    
    /**
     * Menampilkan halaman detail produk untuk pelanggan
     */
    public function showProduct($id)
    {
        // Mengambil data produk, sekaligus menarik relasi 'tiers' dan 'variants'
        $product = Product::with(['tiers', 'variants'])->findOrFail($id);
        
        return view('public.detail-produk', compact('product'));
    }

    /**
     * Menampilkan semua katalog produk yang dikelompokkan berdasarkan kategori
     */
    public function katalog()
    {
        $groupedProducts = Product::where('status', 'Tersedia')
                                  ->orderBy('created_at', 'desc')
                                  ->get()
                                  ->groupBy('category');

        return view('public.katalog', compact('groupedProducts'));
    }

    /**
     * Menampilkan daftar produk berdasarkan kategori yang dipilih dari Navbar Dropdown
     */
    public function showKategori($nama_kategori)
    {
        // Panggil model Category langsung secara dinamis
        $kategori = \App\Models\Category::where('name', $nama_kategori)->firstOrFail();

        // Ambil semua produk yang sesuai kategori dan statusnya Tersedia
        $products = Product::where('category', $kategori->name)
                           ->where('status', 'Tersedia')
                           ->orderBy('created_at', 'desc')
                           ->get();

        return view('kategori', compact('kategori', 'products'));
    }
}