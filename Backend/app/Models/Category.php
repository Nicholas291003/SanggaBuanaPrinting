<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];
    
    public function produkCreate()
    {
        // Tarik semua data kategori dari database
        $categories = Category::all(); 

        // Kirim ke halaman view
        return view('admin.produk.create', compact('categories'));
    }
}
