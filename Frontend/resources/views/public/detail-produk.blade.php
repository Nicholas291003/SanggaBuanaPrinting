@extends('layouts.app')

@section('content')
@php
    // Menarik data nomor WhatsApp dinamis hasil input admin di database
    $detailSetting = \App\Models\CompanySetting::first();
    $detailSosmed  = collect($detailSetting->social_media ?? []);
    $linkWaAktif   = $detailSosmed->firstWhere('platform', 'whatsapp')['url'] ?? 'https://wa.me/6281234567890';
@endphp

<div class="bg-slate-50 min-h-screen py-10" x-data="kalkulatorProduk()">
    <div class="max-w-7xl mx-auto px-4 md:px-6">
        
        <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-wider text-slate-400 mb-8">
            <a href="/" class="hover:text-sanggared transition-colors">Beranda</a>
            <i class="fa-solid fa-chevron-right text-[8px]"></i>
            <a href="{{ route('public.katalog') }}#kategori-{{ \Illuminate\Support\Str::slug($product->category) }}" class="hover:text-sanggared transition-colors">{{ $product->category }}</a>
            <i class="fa-solid fa-chevron-right text-[8px]"></i>
            <span class="text-sanggablue">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 relative">
            
            <div class="lg:col-span-4">
                <div class="bg-white border border-slate-200 rounded-3xl p-6 sticky top-28 shadow-sm">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-auto object-contain rounded-xl">
                    @else
                        <div class="w-full aspect-square flex flex-col items-center justify-center text-slate-300 bg-slate-50 rounded-xl">
                            <i class="fa-regular fa-image text-6xl mb-4"></i>
                            <span class="text-xs font-bold">Belum ada gambar</span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-5 space-y-8">
                
                <div class="border-b border-slate-200 pb-6">
                    <h1 class="text-2xl md:text-3xl font-black text-sanggablue tracking-tight leading-tight mb-4 capitalize">{{ $product->name }}</h1>
                    
                    <div class="flex items-end gap-2">
                        <span class="text-xs font-bold text-slate-400 uppercase">Harga per satuan</span>
                        <span class="text-3xl font-black text-sanggared">Rp <span x-text="formatHarga(hargaSaatIni)"></span></span>
                    </div>
                </div>

                @if($product->tiers && $product->tiers->count() > 0)
                <div class="space-y-3">
                    <h3 class="text-xs font-black text-sanggablue uppercase tracking-wider">Harga Berdasarkan Jumlah</h3>
                    <div class="border border-slate-200 rounded-xl overflow-hidden bg-white shadow-sm">
                        <table class="w-full text-left text-xs">
                            <tbody class="divide-y divide-slate-100 font-bold text-slate-600">
                                @foreach($product->tiers as $tier)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-3 px-4 border-r border-slate-100 w-1/2">
                                        @if($tier->max_qty)
                                            {{ $tier->min_qty }} - {{ $tier->max_qty }} pcs
                                        @else
                                            &ge; {{ $tier->min_qty }} pcs
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-sanggablue">
                                        Rp {{ number_format($tier->price, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                @if($product->variants && $product->variants->count() > 0)
                <div class="space-y-3">
                    <h3 class="text-xs font-black text-sanggablue uppercase tracking-wider">Opsi Tambahan (Finishing)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($product->variants as $variant)
                        <label class="flex items-center justify-between p-3 border border-slate-200 rounded-xl bg-white hover:border-sanggared cursor-pointer transition-all">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" value="{{ $variant->id }}" x-model="selectedVariants" class="w-4 h-4 text-sanggared border-slate-300 rounded focus:ring-sanggared">
                                <span class="text-xs font-bold text-slate-700">{{ $variant->name }}</span>
                            </div>
                            <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded">
                                + Rp {{ number_format($variant->additional_price, 0, ',', '.') }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="pt-4">
                    <div class="flex border-b border-slate-200 mb-4">
                        <span class="px-4 py-2 border-b-2 border-sanggared text-sanggared font-black text-xs uppercase tracking-wider">Rincian Produk</span>
                    </div>
                    <p class="text-sm text-slate-600 font-medium leading-relaxed">
                        {{ $product->description }}
                    </p>
                </div>
            </div>

            <div class="lg:col-span-3 hidden lg:block">
                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-xl sticky top-28">
                    <h3 class="font-black text-sanggablue mb-4 text-sm">Atur Jumlah</h3>
                    
                    <div class="flex items-center gap-3 mb-6">
                        <div class="flex items-center border border-slate-300 rounded-xl overflow-hidden bg-white">
                            <button type="button" @click="kurangiQty" class="w-10 h-10 flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-sanggared transition-colors">
                                <i class="fa-solid fa-minus text-xs"></i>
                            </button>
                            <input type="number" x-model.number="qty" min="1" class="w-14 h-10 text-center text-sm font-black border-x border-slate-200 focus:outline-none text-sanggablue" readonly>
                            <button type="button" @click="tambahQty" class="w-10 h-10 flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-emerald-600 transition-colors">
                                <i class="fa-solid fa-plus text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-6 pt-6 border-t border-slate-100">
                        <span class="text-xs font-bold text-slate-400 uppercase">Subtotal</span>
                        <span class="text-xl font-black text-sanggared">Rp <span x-text="formatHarga(subtotal)"></span></span>
                    </div>

                    <div class="space-y-3">
                        @if($product->status == 'Tersedia')
                            <button type="button" @click="beliLangsung" class="w-full bg-sanggared hover:bg-red-700 text-white font-black py-3 rounded-xl text-xs uppercase tracking-wider transition-all shadow-md">
                                Beli Langsung
                            </button>
                            
                            <button type="button" @click="tambahKeranjang" class="w-full bg-white border border-sanggared text-sanggared hover:bg-red-50 font-black py-3 rounded-xl text-xs uppercase tracking-wider transition-all flex items-center justify-center gap-2">
                                <i class="fa-solid fa-cart-plus"></i> + Keranjang
                            </button>
                        @else
                            <button type="button" disabled class="w-full bg-slate-200 text-slate-400 font-black py-3 rounded-xl text-xs uppercase tracking-wider cursor-not-allowed">
                                Stok Kosong
                            </button>
                        @endif

                        <a href="{{ $linkWaAktif }}" target="_blank" class="w-full px-4 py-3 border border-slate-200 hover:bg-slate-50 text-sanggablue font-black text-xs uppercase tracking-wider rounded-xl flex items-center justify-center gap-2 transition-all mt-2">
                            <i class="fa-brands fa-whatsapp text-emerald-500"></i> Tanya Admin
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="fixed bottom-0 left-0 w-full bg-white border-t border-slate-200 p-4 shadow-[0_-5px_15px_rgba(0,0,0,0.05)] z-50 lg:hidden flex items-center justify-between gap-3">
        
        <button type="button" @click="tambahKeranjang" class="w-12 h-11 border border-sanggared text-sanggared rounded-xl flex items-center justify-center shrink-0 hover:bg-red-50 transition-colors">
            <i class="fa-solid fa-cart-plus"></i>
        </button>

        <div class="flex items-center border border-slate-300 rounded-xl overflow-hidden bg-white shrink-0 h-11">
            <button type="button" @click="kurangiQty" class="w-8 h-full flex items-center justify-center text-slate-500"><i class="fa-solid fa-minus text-xs"></i></button>
            <input type="number" x-model.number="qty" min="1" class="w-10 h-full text-center text-sm font-black border-x border-slate-200 focus:outline-none" readonly>
            <button type="button" @click="tambahQty" class="w-8 h-full flex items-center justify-center text-slate-500"><i class="fa-solid fa-plus text-xs"></i></button>
        </div>
        
        <button type="button" @click="beliLangsung" class="w-full h-11 bg-sanggared text-white font-black rounded-xl text-[10px] uppercase tracking-wider flex flex-col items-center justify-center shadow-md">
            <span>Beli Langsung</span>
            <span class="text-[11px]">Rp <span x-text="formatHarga(subtotal)"></span></span>
        </button>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('kalkulatorProduk', () => ({
            qty: 1, 
            basePrice: {{ $product->base_price }},
            
            tiers: @json($product->tiers),
            variants: @json($product->variants),
            selectedVariants: [], 
            
            // Perhitungan otomatis harga item berdasarkan jumlah pesanan (Grosir)
            get hargaSaatIni() {
                let current = this.basePrice;
                if (this.tiers && this.tiers.length > 0) {
                    let sortedTiers = [...this.tiers].sort((a, b) => b.min_qty - a.min_qty);
                    let matchedTier = sortedTiers.find(t => this.qty >= t.min_qty);
                    if (matchedTier) current = matchedTier.price;
                }
                return current;
            },

            // Perhitungan biaya tambahan dari checkbox varian finishing yang dipilih
            get totalHargaVarian() {
                let total = 0;
                this.selectedVariants.forEach(varianId => {
                    let v = this.variants.find(item => item.id == varianId);
                    if(v) total += parseFloat(v.additional_price);
                });
                return total;
            },

            // Perhitungan subtotal akhir secara langsung/real-time
            get subtotal() {
                return (this.hargaSaatIni + this.totalHargaVarian) * this.qty;
            },

            formatHarga(angka) {
                return new Intl.NumberFormat('id-ID').format(angka);
            },

            tambahQty() { this.qty++; },
            kurangiQty() { if (this.qty > 1) this.qty--; },

            // ALUR 1: BELI SEKARANG (Membawa parameter data langsung ke form checkout baru)
            beliLangsung() {
                let url = new URL('{{ route('user.order.create') }}', window.location.origin);
                url.searchParams.append('product_id', {{ $product->id }});
                url.searchParams.append('qty', this.qty);
                url.searchParams.append('variants', JSON.stringify(this.selectedVariants));
                window.location.href = url.toString();
            },

            // ALUR 2: MASUKKAN KERANJANG (Mengirim data virtual ke session backend)
            tambahKeranjang() {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('user.cart.add') }}';
                
                let csrf = document.createElement('input');
                csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                let pid = document.createElement('input');
                pid.type = 'hidden'; pid.name = 'product_id'; pid.value = {{ $product->id }};
                form.appendChild(pid);

                let q = document.createElement('input');
                q.type = 'hidden'; q.name = 'qty'; q.value = this.qty;
                form.appendChild(q);

                let v = document.createElement('input');
                v.type = 'hidden'; v.name = 'variants'; v.value = JSON.stringify(this.selectedVariants);
                form.appendChild(v);

                document.body.appendChild(form);
                form.submit();
            }
        }))
    })
</script>
@endsection