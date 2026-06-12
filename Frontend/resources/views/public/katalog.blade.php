@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen pb-16">
    
    <!-- HEADER KATALOG -->
    <section class="bg-sanggablue pt-12 pb-16 px-6">
        <div class="max-w-7xl mx-auto space-y-3 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight">
                Katalog Layanan <span class="text-sanggared">Cetak.</span>
            </h1>
            <p class="text-sm text-white/70 max-w-lg leading-relaxed">
                Jelajahi seluruh layanan percetakan profesional kami. Kualitas terbaik, presisi tinggi, dan harga yang bersahabat untuk bisnis Anda.
            </p>
        </div>
    </section>

    <!-- KATEGORI, KANAN: PRODUK -->
    <section class="max-w-7xl mx-auto px-6 mt-8">
        <div class="flex flex-col md:flex-row gap-8">
            
            <!-- KOLOM KIRI: SIDEBAR KATEGORI -->
            <aside class="w-full md:w-1/4 shrink-0">
                <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm sticky top-28">
                    <h3 class="text-sm font-black text-sanggablue uppercase tracking-wider mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-list-ul text-sanggared"></i> Kategori Produk
                    </h3>
                    
                    <ul class="space-y-1.5">
                        @php $allCategories = \App\Models\Category::orderBy('name', 'asc')->get(); @endphp
                        
                        @forelse($allCategories as $kategori)
                            <li>
                                <a href="#kategori-{{ \Illuminate\Support\Str::slug($kategori->name) }}" class="flex items-center justify-between px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-sanggared transition-all group">
                                    {{ $kategori->name }}
                                    <i class="fa-solid fa-chevron-right text-[9px] text-slate-300 group-hover:text-sanggared transition-colors"></i>
                                </a>
                            </li>
                        @empty
                            <li class="text-xs text-slate-400 italic px-4 py-2">Belum ada kategori</li>
                        @endforelse
                    </ul>
                </div>
            </aside>

            <!-- KOLOM KANAN: DAFTAR PRODUK -->
            <main class="w-full md:w-3/4 space-y-16">
                
                @forelse($groupedProducts as $namaKategori => $products)
                    <div id="kategori-{{ \Illuminate\Support\Str::slug($namaKategori) }}" class="scroll-mt-32">
                        
                        <!-- Judul Pembatas Kategori -->
                        <div class="flex items-center gap-4 mb-6">
                            <h2 class="text-2xl font-black text-sanggablue capitalize">{{ $namaKategori }}</h2>
                            <div class="flex-grow h-px bg-slate-200"></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-100 px-3 py-1 rounded-lg">
                                {{ $products->count() }} Produk
                            </span>
                        </div>

                        <!-- Grid Produk di dalam Kategori tersebut -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($products as $product)
                                <a href="{{ route('public.produk.detail', $product->id) }}" class="bg-white border border-slate-100 p-5 rounded-3xl shadow-sm space-y-4 flex flex-col justify-between group hover:-translate-y-1 hover:shadow-xl hover:border-sanggared/30 transition-all duration-300">
                                    <div class="space-y-4">
                                        <!-- Gambar -->
                                        <div class="w-full h-40 bg-slate-50 rounded-2xl flex items-center justify-center overflow-hidden relative border border-slate-50 group-hover:border-slate-100 transition-colors">
                                            @if($product->image_path)
                                                <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                            @else
                                                <i class="fa-regular fa-image text-4xl text-slate-300 group-hover:scale-110 transition-transform duration-500"></i>
                                            @endif
                                            <!-- Label Harga -->
                                            <div class="absolute top-3 left-3 bg-white/90 backdrop-blur-sm px-3 py-1.5 rounded-lg shadow-sm border border-white/50">
                                                <span class="block text-[8px] font-black text-slate-400 uppercase tracking-wider">Mulai Dari</span>
                                                <span class="block font-black text-emerald-600 text-[11px]">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Teks Produk -->
                                        <div class="space-y-1.5">
                                            <h4 class="font-black text-sanggablue text-sm capitalize leading-tight group-hover:text-sanggared transition-colors line-clamp-2" title="{{ $product->name }}">
                                                {{ $product->name }}
                                            </h4>
                                            <p class="text-[10px] text-slate-500 font-medium line-clamp-2 leading-relaxed mt-1">
                                                {{ $product->description }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Tombol Detail -->
                                    <div class="pt-4 border-t border-slate-50 flex items-center justify-between">
                                        <span class="px-4 py-2 bg-slate-50 group-hover:bg-sanggared group-hover:text-white text-sanggablue font-bold text-[10px] uppercase tracking-wider rounded-lg transition-all">
                                            Lihat Detail
                                        </span>
                                        <i class="fa-solid fa-arrow-right text-slate-300 group-hover:text-sanggared transition-colors text-xs"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <!-- Pesan Jika Tidak Ada Produk -->
                    <div class="bg-white p-12 rounded-3xl shadow-sm text-center border border-slate-100">
                        <i class="fa-solid fa-box-open text-5xl text-slate-300 mb-4"></i>
                        <h3 class="text-xl font-black text-sanggablue">Katalog Masih Kosong</h3>
                        <p class="text-sm text-slate-500 mt-2">Belum ada layanan cetak yang tersedia saat ini.</p>
                    </div>
                @endempty
                
            </main>
        </div>
    </section>
</div>
@endsection