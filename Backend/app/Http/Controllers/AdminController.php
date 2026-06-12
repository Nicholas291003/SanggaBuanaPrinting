<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductTier;
use App\Models\ProductVariant; 
use App\Models\Order;
use App\Models\PaymentMethod;
use Carbon\Carbon;
use App\Models\Branch;
use App\Models\User; 
use App\Models\DesignArchive;
use App\Models\SystemLog;

class AdminController extends Controller
{
    // =========================================================================
    // 1. MENU UTAMA DASHBOARD (RINGKASAN STATISTIK KEUANGAN & PRODUKSI)
    // =========================================================================
    public function index()
    {
        $pendapatanHariIni = Order::where('status', 'Selesai')->whereDate('created_at', \Carbon\Carbon::today())->sum('total_price');
        $totalPelanggan    = User::where('role', '!=', 'Administrator')->count();
        $pesananBaru       = Order::where('status', 'Dalam Antrian')->count();
        $totalPendapatan   = Order::where('status', 'Selesai')->sum('total_price');
        $chartDates = [];
        $chartData  = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::today()->subDays($i);
            $chartDates[] = $date->translatedFormat('d M'); 
            
            $harian = Order::where('status', 'Selesai')
                           ->whereDate('created_at', $date)
                           ->sum('total_price');
                           
            $chartData[] = $harian;
        }

        $recentOrders = Order::orderBy('created_at', 'desc')->take(5)->get();
        $totalProduk  = Product::count();
        $produkAktif  = Product::where('status', 'Tersedia')->count();

        return view('admin.dashboard', compact(
            'pendapatanHariIni', 
            'totalPelanggan', 
            'pesananBaru', 
            'totalPendapatan',
            'chartDates', 
            'chartData', 
            'recentOrders',
            'totalProduk',
            'produkAktif'
        ));
    }

    // =========================================================================
    // 2. MENU KELOLA LAYANAN PRODUK (KATALOG CETAK)
    // =========================================================================
    /**
     * Menampilkan Halaman Kelola Kategori
     */
    public function kategoriIndex()
    {
        $categories = Category::latest()->get(); // Mengambil kategori dari yang terbaru
        return view('admin.kategori.index', compact('categories'));
    }

    /**
     * Menyimpan Kategori Baru ke Database
     */
    public function kategoriStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name' // Mencegah nama kategori kembar
        ]);

        Category::create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    /**
     * Menghapus Kategori
     */
    public function kategoriDestroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus!');
    }

    public function produk()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('admin.produk.produk', compact('products'));
    }

    public function produkCreate()
    {
        $categories = Category::all();
        return view('admin.produk.create', compact('categories'));
    }

    public function produkStore(Request $request)
    {
        // Validasi Data
        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'      => 'required|string',
            // Validasi Array Harga
            'tier_operator'=> 'required|array',
            'tier_qty_1'   => 'required|array',
            'tier_qty_2'   => 'nullable|array',
            'tier_price'   => 'required|array',
            // Validasi Array Opsi Tambahan
            'variant_name' => 'nullable|array',
            'variant_price'=> 'nullable|array',
        ]);
        // Simpan Foto
        $imagePath = $request->file('image')->store('products', 'public');

        // Ambil harga termurah dari array tier_price untuk dijadikan base_price
        $basePrice = min($request->tier_price);

        // Simpan Data Produk Utama
        $product = Product::create([
            'name'        => $request->name,
            'base_price'  => $basePrice,
            'category'    => $request->category,
            'description' => $request->description,
            'image_path'  => $imagePath,
            'status'      => $request->status,
        ]);

        // Looping Simpan Harga Grosir (Tiering)
        if ($request->has('tier_operator')) {
            foreach ($request->tier_operator as $index => $operator) {
                $qty1 = (int) $request->tier_qty_1[$index];
                $qty2 = isset($request->tier_qty_2[$index]) ? (int) $request->tier_qty_2[$index] : null;

                $minVal = 1;
                $maxVal = null;

                switch($operator) {
                    case 'range':
                        $minVal = $qty1; $maxVal = $qty2; break;
                    case '>=':
                        $minVal = $qty1; $maxVal = null; break;
                    case '>':
                        $minVal = $qty1 + 1; $maxVal = null; break;
                    case '<=':
                        $minVal = 1; $maxVal = $qty1; break;
                    case '<':
                        $minVal = 1; $maxVal = $qty1 - 1; break;
                }

                ProductTier::create([
                    'product_id' => $product->id,
                    'min_qty'    => $minVal,
                    'max_qty'    => $maxVal,
                    'price'      => $request->tier_price[$index],
                ]);
            }
        }

        // Looping Simpan Opsi Tambahan (Finishing/Laminasi)
        if ($request->has('variant_name')) {
            foreach ($request->variant_name as $index => $vName) {
                if (!empty($vName)) {
                    ProductVariant::create([
                        'product_id'       => $product->id,
                        'name'             => $vName,
                        'additional_price' => $request->variant_price[$index] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.produk.index')->with('success', 'Layanan cetak beserta skema harga grosir berhasil ditambahkan!');
    }

    public function produkDestroy($id)
    {
        $produk = Product::findOrFail($id);
        $produk->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari katalog.');
    }

    /**
     * Membuka halaman formulir edit produk
     */
    public function produkEdit($id)
    {
        $product = Product::with(['tiers', 'variants'])->findOrFail($id);
        $categories = Category::all();
        return view('admin.produk.edit', compact('product', 'categories'));
    }

    /**
     * Memproses simpan perubahan data produk cetak
     */
    public function produkUpdate(Request $request, $id)
    {
        // Validasi Data Update 
        $request->validate([
            'name'         => 'required|string|max:255',
            'category'     => 'required|string',
            'description'  => 'required|string',
            'image'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'       => 'required|string',
            // Validasi Array Harga
            'tier_operator'=> 'required|array',
            'tier_qty_1'   => 'required|array',
            'tier_qty_2'   => 'nullable|array',
            'tier_price'   => 'required|array',
            'variant_name' => 'nullable|array',
            'variant_price'=> 'nullable|array',
        ]);

        $product = Product::findOrFail($id);
        $imagePath = $product->image_path;
        
        // Jika Admin Mengubah Foto
        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // Kalkulasi Ulang Harga Termurah
        $basePrice = min($request->tier_price);

        // Update Tabel Utama Produk
        $product->update([
            'name'        => $request->name,
            'base_price'  => $basePrice,
            'category'    => $request->category,
            'description' => $request->description,
            'image_path'  => $imagePath,
            'status'      => $request->status,
        ]);

        // TEKNIK SINKRONISASI: Hapus data grosir & varian lama, lalu masukkan yang baru
        $product->tiers()->delete();
        $product->variants()->delete();

        // Simpan data grosir yang baru disubmit
        if ($request->has('tier_operator')) {
            foreach ($request->tier_operator as $index => $operator) {
                $qty1 = (int) $request->tier_qty_1[$index];
                $qty2 = isset($request->tier_qty_2[$index]) ? (int) $request->tier_qty_2[$index] : null;

                $minVal = 1;
                $maxVal = null;

                switch($operator) {
                    case 'range':
                        $minVal = $qty1; $maxVal = $qty2; break;
                    case '>=':
                        $minVal = $qty1; $maxVal = null; break;
                    case '>':
                        $minVal = $qty1 + 1; $maxVal = null; break;
                    case '<=':
                        $minVal = 1; $maxVal = $qty1; break;
                    case '<':
                        $minVal = 1; $maxVal = $qty1 - 1; break;
                }

                ProductTier::create([
                    'product_id' => $product->id,
                    'min_qty'    => $minVal,
                    'max_qty'    => $maxVal,
                    'price'      => $request->tier_price[$index],
                ]);
            }
        }

        // Simpan data varian/finishing yang baru disubmit
        if ($request->has('variant_name')) {
            foreach ($request->variant_name as $index => $vName) {
                if (!empty($vName)) {
                    ProductVariant::create([
                        'product_id'       => $product->id,
                        'name'             => $vName,
                        'additional_price' => $request->variant_price[$index] ?? 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.produk.index')->with('success', 'Data produk cetak beserta harga grosirnya berhasil diperbarui!');
    }

    // =========================================================================
    // 3. MENU MANAJEMEN PRODUKSI (KELOLA PESANAN MASUK)
    // =========================================================================
    public function pesanan()
    {
        $orders = Order::query();
        // Filter berdasarkan status jika ada query parameter 'status'
        if (request()->has('status') && request()->status != '') {
            $orders->where('status', request()->status);
        }
        //Fitur Pengurutan berdasarkan terbaru atau terlama
        if (request()->has('sort') && request()->sort == 'Terlama') {
            $orders->orderBy('created_at', 'asc');
        } else {
            $orders->orderBy('created_at', 'desc');
        }
        $orders = $orders->get();
        return view('admin.pesanan.pesanan', compact('orders'));
    }

    /**
     * formulir tambah pesanan manual oleh admin ke dalam antrean produksi (untuk pelanggan yang datang langsung ke toko atau via telepon)
     */
    public function pesananCreate()
    {
        $products = Product::with(['tiers', 'variants'])->where('status', 'Tersedia')->get();
        return view('admin.pesanan.create', compact('products'));
    }

    /**
     * Menyimpan data pesanan manual ke database
     */
    public function pesananStore(Request $request)
    {
        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'product_id'     => 'required|exists:products,id',
            'qty'            => 'required|integer|min:1',
            'variants'       => 'nullable|array',
            'custom_price'   => 'nullable|numeric|min:0',
            'status'         => 'required|string'
        ]);

        $product = Product::with(['tiers', 'variants'])->findOrFail($request->product_id);

        if ($request->filled('custom_price')) {
            $grandTotal = $request->custom_price;
        } else {
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

            $totalVariantPrice = 0;
            if ($request->has('variants')) {
                $selectedVariants = $product->variants->whereIn('id', $request->variants);
                foreach ($selectedVariants as $v) {
                    $totalVariantPrice += $v->additional_price;
                }
            }

            $grandTotal = ($pricePerItem + $totalVariantPrice) * $qty;
        }

        $variantNames = [];
        if ($request->has('variants')) {
            $selectedVariants = $product->variants->whereIn('id', $request->variants);
            foreach ($selectedVariants as $v) {
                $variantNames[] = $v->name;
            }
        }
        
        $serviceDetail = $product->name . ' (' . $request->qty . ' pcs)';
        if (!empty($variantNames)) {
            $serviceDetail .= ' [Finishing: ' . implode(', ', $variantNames) . ']';
        }
        $serviceDetail .= ' - (Pesanan Offline/Manual)';

        $order = Order::create([
            'customer_id'    => null, 
            'customer_name'  => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email ?? '-',
            'service_name'   => $serviceDetail,
            'total_price'    => $grandTotal,
            'status'         => $request->status,
        ]);

        SystemLog::create([
            'action'      => 'Tambah Pesanan Offline',
            'description' => "Admin mendaftarkan manual pesanan #ORD-" . str_pad($order->id, 4, '0', STR_PAD_LEFT) . " untuk pelanggan '{$request->customer_name}'",
            'user_name'   => Auth::user()->name,
        ]);

        return redirect()->route('admin.pesanan.index')->with('success', 'Pesanan berhasil didaftarkan ke sistem produksi');
    }

   public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Dalam Antrian,Sedang Diproses,Selesai,Siap Diambil,Siap Dikirim,Dibatalkan'
        ]);

        // Authorization check: hanya admin/role tertentu boleh update
        if (!Gate::allows('update-order')) {
            abort(403, 'Anda tidak memiliki izin untuk mengubah status pesanan.');
        }

        try {
            $order = Order::findOrFail($id);
            $oldStatus = $order->status;

            $order->status = $request->status;
            $order->save();

            SystemLog::create([
                'action'      => 'Update Pesanan',
                'description' => "Status pesanan #ORD-" . str_pad($order->id, 4, '0', STR_PAD_LEFT) . " diubah dari '{$oldStatus}' menjadi '{$order->status}'",
                'user_name'   => Auth::user()->name,
            ]);

            return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    public function pesananDestroy($id)
    {
        // Authorization check: hanya admin boleh hapus
        if (!Gate::allows('delete-order')) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus pesanan.');
        }

        try {
            $order = Order::findOrFail($id);
            
            // log dibuat sebelum data dihapus agar informasi pesanan masih utuh di log meskipun data aslinya sudah dihapus
            SystemLog::create([
                'action'      => 'Hapus Pesanan',
                'description' => "Pesanan #ORD-" . str_pad($order->id, 4, '0', STR_PAD_LEFT) . " telah dihapus secara permanen.",
                'user_name'   => Auth::user()->name,
            ]);

            $order->delete();

            return redirect()->back()->with('success', 'Pesanan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // 4. MENU LOG AKTIFITAS SISTEM
    // =========================================================================
    public function logAktifitas()
    {
        $logs = SystemLog::orderBy('created_at', 'desc')->get();
        return view('admin.log.index', compact('logs'));
    }

    // =========================================================================
    // 5. MENU ARSIP DESAIN (BERKAS DESAIN USER)
    // =========================================================================
    public function arsip(Request $request)
    {
        $archives = DesignArchive::with('user')->get();
        $archives->map(function($item) {
            $item->file_size = Storage::disk('public')->exists($item->file_path) ? Storage::disk('public')->size($item->file_path) : 0;
            $item->file_ext  = strtolower(pathinfo($item->file_path, PATHINFO_EXTENSION));
            return $item;
        });
        if ($request->has('type') && $request->type != '') {
            $archives = $archives->filter(function($item) use ($request) {
                if ($request->type == 'image') return in_array($item->file_ext, ['jpg', 'jpeg', 'png', 'gif']);
                if ($request->type == 'document') return in_array($item->file_ext, ['pdf', 'doc', 'docx', 'cdr', 'ai']);
                if ($request->type == 'archive') return in_array($item->file_ext, ['zip', 'rar']);
                return true;
            });
        }
        if ($request->has('sort_size') && $request->sort_size != '') {
            if ($request->sort_size == 'terbesar') {
                $archives = $archives->sortByDesc('file_size');
            } else {
                $archives = $archives->sortBy('file_size');
            }
        } 
        else {
            if ($request->has('sort_date') && $request->sort_date == 'terlama') {
                $archives = $archives->sortBy('created_at');
            } else {
                $archives = $archives->sortByDesc('created_at');
            }
        }
        $groupedArchives = $archives->groupBy(function($item) {
            if ($item->created_at->isToday()) return 'Hari Ini';
            if ($item->created_at->isYesterday()) return 'Kemarin';
            return $item->created_at->translatedFormat('d F Y'); 
        });

        return view('admin.arsip.arsip', compact('groupedArchives'));
    }

    public function arsipDestroy($id)
    {
        if (!Gate::allows('delete-order')) { 
            abort(403, 'Anda tidak memiliki izin untuk menghapus file dari server.');
        }

        $archive = DesignArchive::findOrFail($id);

        if ($archive->file_path && Storage::disk('public')->exists($archive->file_path)) {
            Storage::disk('public')->delete($archive->file_path);
        }

        $namaPemilik = $archive->user ? $archive->user->name : 'Pelanggan (Akun Terhapus)';

        SystemLog::create([
            'action'      => 'Hapus File Arsip',
            'description' => "File desain milik '{$namaPemilik}' telah dihapus permanen dari server.",
            'user_name'   => Auth::user()->name,
        ]);

        $archive->delete();

        return redirect()->back()->with('success', 'File arsip berhasil dihapus permanen dari server!');
    }

    // =========================================================================
    // 6. MENU DATA PELANGGAN TETAP
    // =========================================================================
    public function pelanggan()
    {
        // Mengambil semua user yang bukan Administrator
        $customers = User::where('role', '!=', 'Administrator')->orderBy('created_at', 'desc')->get();
        return view('admin.pelanggan.pelanggan', compact('customers'));
    }

    // =========================================================================
    // 7. MENU MANAJEMEN CABANG OUTLET TOKO
    // =========================================================================
    public function cabang()
    {
        $branches = Branch::all();
        return view('admin.cabang.cabang', compact('branches'));
    }

    public function cabangStore(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'address'      => 'required|string',
            'opening_time' => 'nullable',
            'closing_time' => 'nullable',
            'phone'        => 'required|string',
            'status'       => 'required|string' 
        ]);

        Branch::create($request->all());

        return redirect()->back()->with('success', 'Data kantor cabang baru berhasil didaftarkan!');
    }

    /**
     * Mengambil data satu cabang berupa JSON untuk dibaca oleh modal form edit
     */
    public function cabangEdit($id)
    {
        $branch = Branch::findOrFail($id);
        return view('admin.cabang.edit', compact('branch'));
    }

    /**
     * Memproses simpan perubahan data cabang 
     */
    public function cabangUpdate(Request $request, $id)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'address'      => 'required|string',
            'opening_time' => 'nullable',
            'closing_time' => 'nullable',
            'phone'        => 'required|string',
            'status'       => 'required|string'
        ]);

        $branch = Branch::findOrFail($id);
        $branch->update($request->all());

        return redirect()->route('admin.cabang.index')->with('success', 'Data informasi kantor cabang berhasil diperbarui!');
    }

    public function cabangDestroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return redirect()->back()->with('success', 'Data informasi kantor cabang berhasil dihapus!');
    }

    // =========================================================================
    // 8. MENU MANAJEMEN REKENING & PEMBAYARAN 
    // =========================================================================
    public function rekening()
    {
        $payments = PaymentMethod::orderBy('category', 'asc')->get();
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        $pendapatanBulanIni = Order::where('status', 'Selesai')
                                   ->whereMonth('created_at', $bulanIni)
                                   ->whereYear('created_at', $tahunIni)
                                   ->sum('total_price');
        $pendapatanHariIni  = Order::where('status', 'Selesai')
                                   ->whereDate('created_at', Carbon::today())
                                   ->sum('total_price');
        $menungguPembayaran = Order::where('status', 'Dalam Antrian')->count();
        $riwayatTransaksi = Order::where('status', 'Selesai')
                                 ->orderBy('updated_at', 'desc')
                                 ->take(5)
                                 ->get();
        $invoiceTerbaru = Order::orderBy('created_at', 'desc')
                               ->take(4)
                               ->get();
        return view('admin.rekening.index', compact(
            'payments', 
            'pendapatanBulanIni', 
            'pendapatanHariIni', 
            'menungguPembayaran', 
            'riwayatTransaksi', 
            'invoiceTerbaru'
        ));
    }

    public function rekeningCreate()
    {
        return view('admin.rekening.create');
    }

    public function rekeningStore(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'category'       => 'required|string',
            'account_number' => 'nullable|string',
            'account_name'   => 'nullable|string',
            'qr_image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'         => 'required|in:Aktif,Nonaktif'
        ]);

        $qrPath = null;
        if ($request->hasFile('qr_image')) {
            $qrPath = $request->file('qr_image')->store('qris_barcodes', 'public');
        }

        PaymentMethod::create([
            'name'           => $request->name,
            'category'       => $request->category,
            'account_number' => $request->account_number,
            'account_name'   => $request->account_name,
            'qr_image'       => $qrPath,
            'status'         => $request->status,
        ]);

        SystemLog::create([
            'action'      => 'Tambah Rekening',
            'description' => "Metode pembayaran baru ({$request->name}) telah ditambahkan.",
            'user_name'   => Auth::user()->name,
        ]);

        return redirect()->back()->with('success', 'Metode pembayaran baru berhasil ditambahkan!');
    }

    public function rekeningEdit($id)
    {
        $payment = PaymentMethod::findOrFail($id);
        return view('admin.rekening.edit', compact('payment'));
    }

    public function rekeningUpdate(Request $request, $id)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'category'       => 'required|string',
            'account_number' => 'nullable|string',
            'account_name'   => 'nullable|string',
            'qr_image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status'         => 'required|in:Aktif,Nonaktif'
        ]);

        $payment = PaymentMethod::findOrFail($id);
        $qrPath = $payment->qr_image;

        if ($request->hasFile('qr_image')) {
            if ($qrPath && Storage::disk('public')->exists($qrPath)) {
                Storage::disk('public')->delete($qrPath);
            }
            $qrPath = $request->file('qr_image')->store('qris_barcodes', 'public');
        }

        $payment->update([
            'name'           => $request->name,
            'category'       => $request->category,
            'account_number' => $request->account_number,
            'account_name'   => $request->account_name,
            'qr_image'       => $qrPath,
            'status'         => $request->status,
        ]);

        SystemLog::create([
            'action'      => 'Update Rekening',
            'description' => "Informasi metode pembayaran '{$request->name}' telah diperbarui.",
            'user_name'   => Auth::user()->name,
        ]);

        return redirect()->route('admin.rekening.index')->with('success', 'Metode pembayaran berhasil diperbarui!');
    }

    public function rekeningDestroy($id)
    {
        if (!Gate::allows('delete-order')) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus data rekening.');
        }

        $payment = PaymentMethod::findOrFail($id);

        if ($payment->qr_image && Storage::disk('public')->exists($payment->qr_image)) {
            Storage::disk('public')->delete($payment->qr_image);
        }

        SystemLog::create([
            'action'      => 'Hapus Rekening',
            'description' => "Metode pembayaran {$payment->name} telah dihapus.",
            'user_name'   => Auth::user()->name,
        ]);

        $payment->delete();

        return redirect()->back()->with('success', 'Metode pembayaran berhasil dihapus!');
    }

    // =========================================================================
    // 9. MENU MANAJEMEN KONTEN PUBLIK (FAQ, KONTAK & SOSMED)
    // =========================================================================
    /**
     * Menampilkan halaman pusat pengelolaan konten publik
     */
    public function kontenIndex()
    {
        $faqs = \App\Models\Faq::orderBy('created_at', 'desc')->get();
        
        // Buat data cetakan default pertama kali jika database masih kosong
        $setting = \App\Models\CompanySetting::firstOrCreate([], [
            'phone' => '+62 812-3456-7890',
            'email' => 'support@sanggabuana.com',
            'operational_hours' => [
                ['day' => 'Senin - Jumat', 'time' => '08:00 - 21:00 WIB'],
                ['day' => 'Sabtu', 'time' => '09:00 - 17:00 WIB'],
                ['day' => 'Minggu & Hari Besar', 'time' => 'Libur Produksi']
            ],
            'social_media' => [
                ['platform' => 'instagram', 'url' => 'https://instagram.com'],
                ['platform' => 'facebook', 'url' => 'https://facebook.com'],
                ['platform' => 'whatsapp', 'url' => 'https://wa.me/6281234567890']
            ]
        ]);

        return view('admin.konten.index', compact('faqs', 'setting'));
    }

    /**
     * Menyimpan data FAQ baru
     */
    public function faqStore(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string'
        ]);

        \App\Models\Faq::create($request->all());

        return redirect()->back()->with('success', 'Pertanyaan FAQ baru berhasil ditambahkan!');
    }

    /**
     * Memperbarui data FAQ yang sudah ada
     */
    public function faqUpdate(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string'
        ]);

        $faq = \App\Models\Faq::findOrFail($id);
        $faq->update($request->all());

        return redirect()->back()->with('success', 'Pertanyaan FAQ berhasil diperbarui!');
    }

    /**
     * Menghapus baris FAQ
     */
    public function faqDestroy($id)
    {
        $faq = \App\Models\Faq::findOrFail($id);
        $faq->delete();

        return redirect()->back()->with('success', 'Pertanyaan FAQ berhasil dihapus!');
    }

    /**
     * Memperbarui data Kontak, Jam Kerja, dan Tautan Media Sosial
     */
    public function kontakUpdate(Request $request)
    {
        $request->validate([
            'phone'             => 'required|string|max:50',
            'email'             => 'required|email|max:255',
            'operational_hours' => 'nullable|array',
            'social_media'      => 'nullable|array',
        ]);

        $setting = \App\Models\CompanySetting::first();
        $setting->update([
            'phone'             => $request->phone,
            'email'             => $request->email,
            'operational_hours' => $request->operational_hours ?? [],
            'social_media'      => $request->social_media ?? []
        ]);

        return redirect()->back()->with('success', 'Informasi konten operasional toko berhasil diperbarui!');
    }

    // =========================================================================
    // 10. MENU PENGATURAN AKUN MANDIRI ADMIN
    // =========================================================================
    public function pengaturan()
    {
        return view('admin.pengaturan.pengaturan');
    }
}