@extends('layouts.admin')

@section('page_title', 'Input Pesanan Manual ')

@section('admin_content')
<div class="space-y-6">
    <p class="text-xs font-medium text-slate-400 -mt-6">Gunakan formulir ini untuk mencatat transaksi pesanan masuk dari WhatsApp atau Walk-In Toko.</p>

    <form action="{{ route('admin.pesanan.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        @csrf
        
        <div class="lg:col-span-8 bg-white border border-slate-100 p-6 rounded-3xl shadow-sm space-y-5">
            
            <div class="space-y-4">
                <h3 class="text-xs font-black text-sanggablue uppercase tracking-wider border-b pb-2 flex items-center gap-2">
                    <i class="fa-solid fa-user-tag text-sanggared"></i> 1. Informasi Kontak Pelanggan
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black text-slate-400 uppercase">Nama Pelanggan</label>
                        <input type="text" name="customer_name" required placeholder="Contoh: Budi Santoso" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-sanggablue focus:outline-none focus:border-sanggared transition-colors">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black text-slate-400 uppercase">No. WhatsApp/Telepon</label>
                        <input type="text" name="customer_phone" required placeholder="Contoh: 0812xxxxxxxx" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-sanggablue focus:outline-none focus:border-sanggared transition-colors">
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="text-[11px] font-black text-slate-400 uppercase">Email (Opsional)</label>
                    <input type="email" name="customer_email" placeholder="Contoh: pelanggan@gmail.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-sanggablue focus:outline-none focus:border-sanggared transition-colors">
                </div>
            </div>

            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-black text-sanggablue uppercase tracking-wider border-b pb-2 flex items-center gap-2">
                    <i class="fa-solid fa-sliders text-sanggared"></i> 2. Spesifikasi Cetak & Kuantitas
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black text-slate-400 uppercase">Pilih Layanan Produk</label>
                        <select name="product_id" id="product_select" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-sanggablue focus:outline-none focus:border-sanggared transition-colors">
                            <option value="" disabled selected>-- Pilih Layanan Katalog --</option>
                            @foreach($products as $prod)
                                <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black text-slate-400 uppercase">Jumlah Cetak (Qty)</label>
                        <input type="number" name="qty" id="qty_input" value="1" min="1" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-sanggablue focus:outline-none focus:border-sanggared transition-colors">
                    </div>
                </div>

                <div id="variant_container" class="space-y-2 pt-2"></div>
            </div>

            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-black text-sanggablue uppercase tracking-wider border-b pb-2 flex items-center gap-2">
                    <i class="fa-solid fa-money-bill-wave text-sanggared"></i> 3. Harga Spesial & Alur Kerja
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black text-slate-400 uppercase">Harga Kustom Total (Kosongkan jika ingin Otomatis)</label>
                        <input type="number" name="custom_price" id="custom_price_input" placeholder="Misal: 150000 (Tanpa Titik)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-emerald-600 font-mono focus:outline-none focus:border-sanggared transition-colors">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-black text-slate-400 uppercase">Status Awal Pesanan</label>
                        <select name="status" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-sanggablue focus:outline-none focus:border-sanggared transition-colors">
                            <option value="Dalam Antrian">Dalam Antrian</option>
                            <option value="Sedang Diproses">Sedang Diproses</option>
                            <option value="Selesai">Lunas & Selesai</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 bg-sanggablue text-white p-6 rounded-3xl shadow-lg sticky top-24 space-y-6">
            <h3 class="text-xs font-black uppercase tracking-widest border-b border-white/10 pb-3">Estimasi Biaya Otomatis</h3>
            
            <div class="flex justify-between items-end">
                <span class="text-xs text-white/60 font-bold uppercase">Total Hitung Sistem</span>
                <span id="auto_price_text" class="text-2xl font-black text-emerald-400 font-mono">Rp 0</span>
            </div>
            
            <p class="text-[10px] text-white/50 leading-relaxed font-medium">
                *Sistem otomatis menghitung harga satuan berdasarkan skema grosir (Tiering Qty) dan tambahan finishing produk yang dipilih. Jika mengisi 'Harga Kustom Total', maka perhitungan otomatis ini akan diabaikan.
            </p>

            <div class="pt-4 border-t border-white/10 flex gap-3">
                <a href="{{ route('admin.pesanan.index') }}" class="w-1/3 bg-white/10 hover:bg-white/20 font-black text-center py-3 rounded-xl text-xs uppercase tracking-wider transition-all">Batal</a>
                <button type="submit" class="w-2/3 bg-sanggared hover:bg-red-700 font-black py-3 rounded-xl text-xs uppercase tracking-wider transition-all shadow-md">Simpan Nota</button>
            </div>
        </div>
    </form>
</div>

<script>
    const products = @json($products);
    const productSelect = document.getElementById('product_select');
    const qtyInput = document.getElementById('qty_input');
    const variantContainer = document.getElementById('variant_container');
    const autoPriceText = document.getElementById('auto_price_text');

    // Listener saat produk diubah: Render opsi varian/finishing pendukung
    productSelect.addEventListener('change', function() {
        const product = products.find(p => p.id == this.value);
        variantContainer.innerHTML = '';
        
        if (product && product.variants && product.variants.length > 0) {
            let html = '<label class="text-[11px] font-black text-slate-400 uppercase block mb-2">Pilih Tambahan Finishing</label><div class="grid grid-cols-1 sm:grid-cols-2 gap-2">';
            product.variants.forEach(v => {
                html += `
                    <label class="flex items-center justify-between p-2.5 border border-slate-200 rounded-xl bg-slate-50 cursor-pointer hover:border-sanggared transition-all">
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="variants[]" value="${v.id}" class="variant-checkbox w-3.5 h-3.5 text-sanggared border-slate-300 rounded focus:ring-sanggared" onchange="calculatePrice()">
                            <span class="text-[11px] font-bold text-slate-700">${v.name}</span>
                        </div>
                        <span class="text-[9px] font-black text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded">+Rp ${formatHarga(v.additional_price)}</span>
                    </label>
                `;
            });
            html += '</div>';
            variantContainer.innerHTML = html;
        }
        calculatePrice();
    });

    // Listener saat kuantitas diubah
    qtyInput.addEventListener('input', calculatePrice);

    // Fungsi menghitung harga otomatis secara real-time di halaman admin
    function calculatePrice() {
        const productId = productSelect.value;
        const qty = parseInt(qtyInput.value) || 1;
        
        if (!productId) {
            autoPriceText.textContent = 'Rp 0';
            return;
        }
        
        const product = products.find(p => p.id == productId);
        if (!product) return;
        
        let pricePerItem = product.base_price;
        
        // Cek skema tiering grosir
        if (product.tiers && product.tiers.length > 0) {
            let sortedTiers = [...product.tiers].sort((a, b) => b.min_qty - a.min_qty);
            let matchedTier = sortedTiers.find(t => qty >= t.min_qty);
            if (matchedTier) pricePerItem = matchedTier.price;
        }
        
        // Cek varian finishing yang sedang dicentang
        let totalVariantPrice = 0;
        const checkboxes = document.querySelectorAll('.variant-checkbox:checked');
        checkboxes.forEach(cb => {
            let v = product.variants.find(item => item.id == cb.value);
            if (v) totalVariantPrice += parseFloat(v.additional_price);
        });
        
        const grandTotal = (parseFloat(pricePerItem) + totalVariantPrice) * qty;
        autoPriceText.textContent = 'Rp ' + formatHarga(grandTotal);
    }

    function formatHarga(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }
</script>
@endsection