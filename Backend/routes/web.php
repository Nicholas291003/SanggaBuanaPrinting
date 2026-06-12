<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes - Percetakan Sangga Buana
|--------------------------------------------------------------------------
*/

// =========================================================================
// 1. JALUR UMUM / PUBLIK (TANPA LOGIN)
// =========================================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cabang-toko', [HomeController::class, 'cabang'])->name('public.cabang');
Route::get('/kontak', [HomeController::class, 'kontak'])->name('public.kontak');
Route::get('/produk/{id}', [HomeController::class, 'showProduct'])->name('public.produk.detail');
Route::get('/katalog', [App\Http\Controllers\HomeController::class, 'katalog'])->name('public.katalog');
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});


// =========================================================================
// 2. JALUR KHUSUS USER / PELANGGAN MITRA (WAJIB LOGIN)
// =========================================================================
Route::middleware(['auth'])->group(function () {
    
    // Formulir & Proses Kirim Pesanan Cetak Baru
    Route::get('/pesanan-baru', [UserController::class, 'createOrder'])->name('user.order.create');
    Route::post('/pesanan-baru', [UserController::class, 'storeOrder'])->name('user.order.store');
    
    // Riwayat Antrean & Detail Status Pelacakan Nota Cetak
    Route::get('/pesanan-saya', [UserController::class, 'myOrders'])->name('user.order.index');
    Route::get('/pesanan-saya/{id}', [UserController::class, 'showOrder'])->name('user.order.show');

    // Rute Keranjang Belanja
    Route::post('/keranjang/tambah', [App\Http\Controllers\UserController::class, 'tambahKeranjang'])->name('user.cart.add');
    Route::delete('/keranjang/hapus/{cartId}', [App\Http\Controllers\UserController::class, 'hapusKeranjang'])->name('user.cart.remove');

    // Rute Halaman Pembayaran
    Route::get('/pembayaran/{id}', [App\Http\Controllers\UserController::class, 'showPayment'])->name('user.payment.show');
    
    // Manajemen Pengaturan Profil Akun Pelanggan
    Route::get('/profile-saya', [UserController::class, 'editProfile'])->name('user.profile.edit');
    Route::put('/profile-saya', [UserController::class, 'updateProfile'])->name('user.profile.update');
});


// =========================================================================
// 3. JALUR UTAMA SANGGAADMIN (RUANG KENDALI ADMINISTRATOR)
// =========================================================================
Route::middleware(['auth', 'role:Administrator'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Utama (Ringkasan Statistik Keuangan & Produksi)
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Manajemen Layanan Produk (Katalog Etalase)
    Route::get('/produk', [AdminController::class, 'produk'])->name('produk.index');
    Route::get('/produk/create', [AdminController::class, 'produkCreate'])->name('produk.create');
    Route::post('/produk', [AdminController::class, 'produkStore'])->name('produk.store');
    Route::delete('/produk/{id}', [AdminController::class, 'produkDestroy'])->name('produk.destroy');
    Route::get('/produk/{id}', [AdminController::class, 'produkEdit'])->name('produk.edit');
    Route::put('/produk/{id}', [AdminController::class, 'produkUpdate'])->name('produk.update');
    // [Menu Kategori Produk]
    Route::get('/kategori', [AdminController::class, 'kategoriIndex'])->name('kategori.index');
    Route::post('/kategori', [AdminController::class, 'kategoriStore'])->name('kategori.store');
    Route::delete('/kategori/{id}', [AdminController::class, 'kategoriDestroy'])->name('kategori.destroy');

    // Manajemen Antrean Produksi Pesanan Masuk
    Route::get('/pesanan', [AdminController::class, 'pesanan'])->name('pesanan.index');
    Route::put('/pesanan/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('pesanan.updateStatus');
    Route::delete('/pesanan/{id}', [AdminController::class, 'pesananDestroy'])->name('pesanan.destroy');

    // Rute untuk menambah pesanan manual oleh Admin
    Route::get('/pesanan/tambah', [App\Http\Controllers\AdminController::class, 'pesananCreate'])->name('pesanan.create');
    Route::post('/pesanan/simpan', [App\Http\Controllers\AdminController::class, 'pesananStore'])->name('pesanan.store');

    // Daftar Unduhan Arsip Berkas Desain Mentah Pelanggan
    Route::get('/arsip', [AdminController::class, 'arsip'])->name('arsip.index');
    Route::delete('/arsip/{id}', [AdminController::class, 'arsipDestroy'])->name('arsip.destroy');

    // Informasi Data Akun Pelanggan Tetap Terdaftar
    Route::get('/pelanggan', [AdminController::class, 'pelanggan'])->name('pelanggan.index');

    // Manajemen Informasi Operasional Cabang Outlet Toko
    Route::get('/cabang', [AdminController::class, 'cabang'])->name('cabang.index');
    Route::post('/cabang', [AdminController::class, 'cabangStore'])->name('cabang.store');
    Route::get('/cabang/{id}/edit', [AdminController::class, 'cabangEdit'])->name('cabang.edit');     
    Route::put('/cabang/{id}', [AdminController::class, 'cabangUpdate'])->name('cabang.update');
    Route::delete('/cabang/{id}', [AdminController::class, 'cabangDestroy'])->name('cabang.destroy');     

    // Pengaturan Keamanan Akun Mandiri Admin
    Route::get('/pengaturan', [AdminController::class, 'pengaturan'])->name('pengaturan.index');

    // Log aktifitas
    Route::get('/log-aktifitas', [AdminController::class, 'logAktifitas'])->name('log.index');

    // Manajemen Rekening  & Pembayaran
    Route::get('/rekening', [AdminController::class, 'rekening'])->name('rekening.index');
    Route::get('/rekening/create', [AdminController::class, 'rekeningCreate'])->name('rekening.create');
    Route::post('/rekening', [AdminController::class, 'rekeningStore'])->name('rekening.store');
    Route::get('/rekening/{id}/edit', [AdminController::class, 'rekeningEdit'])->name('rekening.edit');
    Route::put('/rekening/{id}', [AdminController::class, 'rekeningUpdate'])->name('rekening.update');
    Route::delete('/rekening/{id}', [AdminController::class, 'rekeningDestroy'])->name('rekening.destroy');

    // Rute Manajemen Konten Publik (FAQ, Kontak, Sosmed)
    Route::get('/konten', [App\Http\Controllers\AdminController::class, 'kontenIndex'])->name('konten.index');
    Route::post('/konten/faq', [App\Http\Controllers\AdminController::class, 'faqStore'])->name('faq.store');
    Route::put('/konten/faq/{id}', [App\Http\Controllers\AdminController::class, 'faqUpdate'])->name('faq.update');
    Route::delete('/konten/faq/{id}', [App\Http\Controllers\AdminController::class, 'faqDestroy'])->name('faq.destroy');
    Route::put('/konten/kontak', [App\Http\Controllers\AdminController::class, 'kontakUpdate'])->name('kontak.update');
});

// =========================================================================
// 4. JALUR KELUAR SISTEM (LOGOUT GLOBAL)
// =========================================================================
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');