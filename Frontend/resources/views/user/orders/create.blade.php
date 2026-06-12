@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen py-10" x-data="formPesanan()">
    <div class="max-w-6xl mx-auto px-4 md:px-6">
        
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-black text-sanggablue tracking-tight">Checkout Pesanan</h1>
            <p class="text-xs text-slate-500 mt-1">Lengkapi detail pesanan dan unggah desain mentah Anda.</p>
        </div>

        <form action="{{ route('user.order.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-8 relative">
            @csrf
            @if ($errors->any())
                <div class="col-span-full bg-red-50 text-red-600 p-4 rounded-xl text-xs font-bold border border-red-100 shadow-sm mb-2">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <input type="hidden" name="variants" :value="JSON.stringify(selectedVariants)">

            <div class="lg:col-span-8 space-y-6">
                
                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-5">
                    <h3 class="text-sm font-black text-sanggablue flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i class="fa-solid fa-box-open text-sanggared"></i> 1. Spesifikasi Pesanan
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Layanan Cetak</label>
                            <select name="product_id" x-model="selectedProductId" class="w-full bg-slate-50 border border-slate-200 text-sanggablue text-sm font-bold rounded-xl px-4 py-3 focus:ring-1 focus:ring-sanggared focus:border-sanggared outline-none transition-all">
                                <option value="" disabled>-- Pilih Produk --</option>
                                <template x-for="prod in products" :key="prod.id">
                                    <option :value="prod.id" x-text="prod.name"></option>
                                </template>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Kuantitas (Qty)</label>
                            <div class="flex items-center border border-slate-200 rounded-xl overflow-hidden bg-white">
                                <button type="button" @click="kurangiQty" class="w-12 h-11 flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-sanggared transition-colors">
                                    <i class="fa-solid fa-minus text-xs"></i>
                                </button>
                                <input type="number" name="qty" x-model.number="qty" min="1" class="w-full h-11 text-center text-sm font-black border-x border-slate-200 focus:outline-none text-sanggablue" readonly>
                                <button type="button" @click="tambahQty" class="w-12 h-11 flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-emerald-600 transition-colors">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div x-show="availableVariants.length > 0" x-cloak class="pt-4 border-t border-slate-100">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-3">Opsi Tambahan (Finishing)</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <template x-for="variant in availableVariants" :key="variant.id">
                                <label class="flex items-center justify-between p-3 border border-slate-200 rounded-xl bg-slate-50 hover:border-sanggared cursor-pointer transition-all">
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" :value="variant.id" x-model="selectedVariants" class="w-4 h-4 text-sanggared border-slate-300 rounded focus:ring-sanggared">
                                        <span class="text-xs font-bold text-slate-700" x-text="variant.name"></span>
                                    </div>
                                    <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded">
                                        + Rp <span x-text="formatHarga(variant.additional_price)"></span>
                                    </span>
                                </label>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-5">
                    <h3 class="text-sm font-black text-sanggablue flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i class="fa-solid fa-file-arrow-up text-sanggared"></i> 2. Berkas Desain
                    </h3>
                    
                    <div class="relative border-2 border-dashed border-slate-300 rounded-2xl hover:bg-slate-50 hover:border-sanggared transition-all text-center group cursor-pointer overflow-hidden min-h-[220px] flex flex-col items-center justify-center p-6">
                        
                        <div x-show="!fileName" class="space-y-3 pointer-events-none">
                            <i class="fa-solid fa-cloud-arrow-up text-4xl text-slate-300 group-hover:text-sanggared transition-colors"></i>
                            <p class="text-sm font-bold text-sanggablue">Klik untuk mengunggah file desain</p>
                            <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-wider">Format: PDF, JPG, PNG, ZIP, RAR (Maks 20MB)</p>
                        </div>

                        <div x-show="fileName" x-cloak class="w-full flex flex-col items-center justify-center space-y-4 pointer-events-none">
                            
                            <template x-if="filePreview">
                                <img :src="filePreview" class="max-h-32 object-contain rounded-lg shadow-sm border border-slate-200">
                            </template>
                            
                            <template x-if="!filePreview">
                                <i class="fa-solid fa-file-lines text-5xl text-sanggared"></i>
                            </template>
                            
                            <div class="bg-white border border-slate-200 shadow-sm px-4 py-2 rounded-xl max-w-full">
                                <p class="text-xs font-bold text-sanggablue truncate" x-text="fileName"></p>
                            </div>
                            
                            <p class="text-[10px] text-slate-400 font-bold bg-white/50 px-2 py-1 rounded">Klik area ini lagi untuk mengganti file</p>
                        </div>

                        <input type="file" name="file_design" class="opacity-0 absolute inset-0 w-full h-full cursor-pointer z-10" required @change="handleFileUpload($event)">
                    </div>
                </div>
            </div>

            <div class="lg:col-span-4">
                <div class="bg-sanggablue rounded-3xl p-6 shadow-xl sticky top-28 text-white">
                    <h3 class="font-black text-white mb-5 text-sm uppercase tracking-wider border-b border-white/10 pb-4">Ringkasan Pembayaran</h3>
                    
                    <div x-show="!activeProduct" class="py-8 text-center text-white/50 text-xs font-medium">
                        Silakan pilih layanan cetak terlebih dahulu untuk melihat rincian biaya.
                    </div>

                    <div x-show="activeProduct" x-cloak class="space-y-4">
                        <div class="flex justify-between items-start">
                            <div class="pr-4">
                                <span class="block font-bold text-sm" x-text="activeProduct ? activeProduct.name : ''"></span>
                                <span class="block text-[10px] text-white/50 uppercase mt-0.5"><span x-text="qty"></span> pcs x Rp <span x-text="formatHarga(hargaSatuan)"></span></span>
                            </div>
                            <span class="font-black text-sm whitespace-nowrap">Rp <span x-text="formatHarga(hargaSatuan * qty)"></span></span>
                        </div>

                        <template x-if="selectedVariants.length > 0">
                            <div class="pt-3 border-t border-white/10 space-y-2">
                                <span class="block text-[10px] font-bold text-white/50 uppercase tracking-widest">Opsi Tambahan</span>
                                <template x-for="varianId in selectedVariants" :key="varianId">
                                    <div class="flex justify-between text-xs items-center">
                                        <span class="text-white/80" x-text="getNamaVarian(varianId)"></span>
                                        <span class="font-bold">+ Rp <span x-text="formatHarga(getHargaVarian(varianId) * qty)"></span></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                        
                        <div class="pt-4 mt-4 border-t-2 border-white/20 flex justify-between items-end">
                            <span class="text-xs font-bold text-white/70 uppercase tracking-wider">Total Tagihan</span>
                            <span class="text-2xl font-black text-emerald-400">Rp <span x-text="formatHarga(subtotalAkhir)"></span></span>
                        </div>

                        <button type="submit" class="w-full bg-sanggared hover:bg-red-700 text-white font-black py-3.5 rounded-xl text-xs uppercase tracking-wider mt-6 transition-all shadow-[0_4px_14px_0_rgba(193,18,31,0.39)] flex items-center justify-center gap-2">
                            Selesaikan Pesanan & Bayar <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            
        </form>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formPesanan', () => ({
            products: @json($products),
            selectedProductId: '{{ $preselectProduct }}' || '',
            qty: parseInt('{{ $preselectQty }}') || 1,
            selectedVariants: {!! $preselectVariants !!} || [],
            fileName: '',
            filePreview: null,

            get activeProduct() {
                if(!this.selectedProductId) return null;
                return this.products.find(p => p.id == this.selectedProductId);
            },
            
            get availableVariants() {
                return this.activeProduct ? this.activeProduct.variants : [];
            },

            get hargaSatuan() {
                if(!this.activeProduct) return 0;
                let current = this.activeProduct.base_price;
                if (this.activeProduct.tiers && this.activeProduct.tiers.length > 0) {
                    let sortedTiers = [...this.activeProduct.tiers].sort((a, b) => b.min_qty - a.min_qty);
                    let matchedTier = sortedTiers.find(t => this.qty >= t.min_qty);
                    if (matchedTier) current = matchedTier.price;
                }
                return current;
            },

            getNamaVarian(id) {
                let v = this.availableVariants.find(item => item.id == id);
                return v ? v.name : '';
            },
            getHargaVarian(id) {
                let v = this.availableVariants.find(item => item.id == id);
                return v ? parseFloat(v.additional_price) : 0;
            },

            get totalHargaVarian() {
                let total = 0;
                this.selectedVariants.forEach(varianId => {
                    total += this.getHargaVarian(varianId);
                });
                return total;
            },

            get subtotalAkhir() {
                return (this.hargaSatuan + this.totalHargaVarian) * this.qty;
            },

            formatHarga(angka) {
                return new Intl.NumberFormat('id-ID').format(angka);
            },

            tambahQty() { this.qty++; },
            kurangiQty() { if (this.qty > 1) this.qty--; },
            handleFileUpload(event) {
                const file = event.target.files[0];
                if (!file) {
                    this.fileName = '';
                    this.filePreview = null;
                    return;
                }

                this.fileName = file.name;

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.filePreview = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Jika bukan gambar (misal: PDF atau ZIP), kosongkan preview agar ikon dokumen yang muncul
                    this.filePreview = null;
                }
            },

            init() {
                this.$watch('selectedProductId', (value) => {
                    if(value !== '{{ $preselectProduct }}') {
                        this.selectedVariants = [];
                    }
                });
            }
        }))
    })
</script>
@endsection