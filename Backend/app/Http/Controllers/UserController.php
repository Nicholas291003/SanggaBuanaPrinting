<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\DesignArchive;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * 1. Halaman Form Buat Pesanan Cetak Baru
     */
    public function createOrder(Request $request)
    {
        // Ambil data produk beserta relasi harga grosir (tiers) dan opsi tambahan (variants)
        $products = Product::with(['tiers', 'variants'])->where('status', 'Tersedia')->get();
        $preselectProduct  = $request->query('product_id', '');
        $preselectQty      = $request->query('qty', 1);
        $preselectVariants = $request->query('variants', '[]');

        $paymentMethods = PaymentMethod::all();

        return view('user.orders.create', compact('products', 'preselectProduct', 'preselectQty', 'preselectVariants', 'paymentMethods'));
    }

    /**
     * 2. Simpan Transaksi Pesanan & Unggah Berkas Mentah Desain
     */
    public function storeOrder(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'product_id'  => 'required|exists:products,id',
            'qty'         => 'required|integer|min:1',
            'variants'    => 'nullable|string',
            'file_design' => 'required|file|mimes:pdf,jpg,jpeg,png,zip,rar|max:20480', 
        ]);

        $product = Product::with(['tiers', 'variants'])->findOrFail($request->product_id);
        $user = Auth::user();

        // 2. Kalkulasi Harga Dasar (Berdasarkan Kuantitas / Tiering Grosir)
        $qty = $request->qty;
        $pricePerItem = $product->base_price;
        
        if ($product->tiers && $product->tiers->count() > 0) {
            $sortedTiers = $product->tiers->sortByDesc('min_qty');
            foreach ($sortedTiers as $tier) {
                if ($qty >= $tier->min_qty) {
                    $pricePerItem = $tier->price;
                    break;
                }
            }
        }

        // 3. Kalkulasi Harga Opsi Tambahan (Finishing/Variants)
        $variantIds = json_decode($request->variants, true) ?? [];
        $totalVariantPrice = 0;
        $variantNames = [];
        
        if (!empty($variantIds)) {
            $selectedVariants = $product->variants->whereIn('id', $variantIds);
            foreach ($selectedVariants as $v) {
                $totalVariantPrice += $v->additional_price;
                $variantNames[] = $v->name; // Simpan nama finishing untuk nota
            }
        }

        // 4. Kalkulasi Total Akhir (Harga Item + Harga Finishing) x Kuantitas
        $finalPricePerItem = $pricePerItem + $totalVariantPrice;
        $grandTotal = $finalPricePerItem * $qty;

        // 5. Rangkai Detail Layanan untuk ditampilkan di Nota/Invoice
        $serviceDetail = $product->name . ' (' . $qty . ' pcs)';
        if (!empty($variantNames)) {
            $serviceDetail .= ' + ' . implode(', ', $variantNames);
        }

        // 6. Simpan Pesanan ke Database
        $order = Order::create([
            'customer_id'    => $user->id,
            'customer_name'  => $user->name,
            'customer_phone' => $user->phone ?? '081234567890',
            'customer_email' => $user->email,
            'service_name'   => $serviceDetail,
            'total_price'    => $grandTotal,
            'status'         => 'Dalam Antrian',
        ]);

        // 7. Simpan Fisik File Desain
        if ($request->hasFile('file_design')) {
            $file = $request->file('file_design');
            $fileName = $file->getClientOriginalName();
            $fileSize = round($file->getSize() / 1024 / 1024, 1) . ' MB'; 
            $filePath = $file->store('archives', 'public'); 

            DesignArchive::create([
                'customer_id' => $user->id,
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'file_path' => $filePath,
            ]);
        }

        // 8. Arahkan ke halaman Pembayaran 
        return redirect()->route('user.payment.show', $order->id)
                        ->with('success', 'Pesanan berhasil dikirim! Silakan selesaikan pembayaran Anda.');
    }

    /**
     * 3. Menampilkan Halaman Instruksi Pembayaran (Invoice)
    */
    public function showPayment($id)
    {
        $order = Order::where('customer_id', Auth::id())->findOrFail($id);

        // Tarik metode pembayaran yang aktif dan kelompokkan berdasarkan kategorinya (Bank, E-Wallet, QRIS)
        $paymentMethods = PaymentMethod::where('status', 'Aktif')->get()->groupBy('category');

        return view('user.orders.payment', compact('order', 'paymentMethods'));
    }

    /**
     * 4. Menyimpan produk ke Keranjang (Session)
     */
    public function tambahKeranjang(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'required|integer|min:1',
            'variants'   => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);
        $cartId = uniqid(); // Buat ID unik untuk setiap item di keranjang
        
        $cart[$cartId] = [
            'product_id' => $request->product_id,
            'qty'        => $request->qty,
            'variants'   => json_decode($request->variants, true) ?? [],
        ];

        session()->put('cart', $cart);

        return redirect()->route('user.order.index')->with('success', 'Layanan berhasil dimasukkan ke keranjang!');
    }

    /**
     * 5. Menghapus produk dari Keranjang (Session)
     */
    public function hapusKeranjang($cartId)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$cartId])) {
            unset($cart[$cartId]);
            session()->put('cart', $cart);
        }
        return redirect()->back()->with('success', 'Layanan dihapus dari keranjang.');
    }

    /**
     * 6. Riwayat Transaksi & Pelacakan Status Antrean Cetak Milik User
     */
    public function myOrders()
    {
        // Ambil Riwayat Pesanan dari Database
        $orders = Order::where('customer_id', Auth::id())->orderBy('created_at', 'desc')->get();
        
        // Ambil Data Keranjang dari Session & Kalkulasi Total Harganya
        $cartSession = session()->get('cart', []);
        $cartItems = [];
        
        foreach ($cartSession as $cartId => $item) {
            $product = Product::with(['tiers', 'variants'])->find($item['product_id']);
            if ($product) {
                // Kalkulasi Harga Satuan Grosir
                $pricePerItem = $product->base_price;
                if ($product->tiers && $product->tiers->count() > 0) {
                    $sortedTiers = $product->tiers->sortByDesc('min_qty');
                    foreach ($sortedTiers as $tier) {
                        if ($item['qty'] >= $tier->min_qty) {
                            $pricePerItem = $tier->price; break;
                        }
                    }
                }
                
                // Kalkulasi Harga Varian
                $totalVariantPrice = 0;
                $variantNames = [];
                if (!empty($item['variants'])) {
                    $selectedVariants = $product->variants->whereIn('id', $item['variants']);
                    foreach ($selectedVariants as $v) {
                        $totalVariantPrice += $v->additional_price;
                        $variantNames[] = $v->name;
                    }
                }

                $cartItems[$cartId] = [
                    'product'       => $product,
                    'qty'           => $item['qty'],
                    'variants'      => $item['variants'], // Array ID mentah
                    'variant_names' => $variantNames,     // Array Nama
                    'total_price'   => ($pricePerItem + $totalVariantPrice) * $item['qty']
                ];
            }
        }

        return view('user.orders.index', compact('orders', 'cartItems'));
    }

    /**
     * 7. Halaman Formulir Edit Profil Akun Mandiri Pengguna
     */
    public function editProfile()
    {
        return view('user.profile.edit');
    }

    /**
     * 8. Proses Pembaruan Data Identitas Kontak & Kata Sandi Pengguna
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Validasi pengisian perubahan password baru jika kolom diisi oleh user
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Data informasi profil Anda berhasil diperbarui!');
    }

    /**
     * 9. Halaman Detail Informasi Pesanan & Linimasa Produksi
     */
    public function showOrder($id)
    {
        // Mengunci pencarian agar user hanya bisa melihat detail pesanan miliknya sendiri
        $order = Order::where('customer_id', Auth::id())->findOrFail($id);
        
        return view('user.orders.show', compact('order'));
    }
}