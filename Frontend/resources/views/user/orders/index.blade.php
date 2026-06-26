@extends('layouts.app')

@section('content')
@php
    // Menarik data nomor WhatsApp dinamis hasil input admin di database
    $setting = \App\Models\CompanySetting::first();
    $sosmed = collect($setting->social_media ?? []);
    $waBaseUrl = $sosmed->firstWhere('platform', 'whatsapp')['url'] ?? 'https://wa.me/6281234567890';
@endphp

<div class="bg-slate-50 min-h-screen py-10" x-data="{ tab: 'keranjang' }">
    <div class="max-w-5xl mx-auto px-4 md:px-6">
        
        <h1 class="text-2xl md:text-3xl font-black text-sanggablue tracking-tight mb-8">Aktivitas Belanja</h1>
        
        <div class="flex gap-6 border-b border-slate-200 mb-8 overflow-x-auto">
            <button @click="tab = 'keranjang'" :class="tab === 'keranjang' ? 'border-sanggared text-sanggared' : 'border-transparent text-slate-500 hover:text-sanggablue'" class="pb-3 border-b-2 font-black text-sm uppercase tracking-wider whitespace-nowrap px-2 transition-all">
                Keranjang Saya
                @if(count($cartItems) > 0)
                    <span class="ml-2 bg-sanggared text-white text-[10px] px-2 py-0.5 rounded-full">{{ count($cartItems) }}</span>
                @endif
            </button>
            
            <button @click="tab = 'pesanan'" :class="tab === 'pesanan' ? 'border-sanggared text-sanggared' : 'border-transparent text-slate-500 hover:text-sanggablue'" class="pb-3 border-b-2 font-black text-sm uppercase tracking-wider whitespace-nowrap px-2 transition-all">
                Riwayat Pesanan
            </button>
        </div>

        <div x-show="tab === 'keranjang'" x-cloak>
            @if(count($cartItems) > 0)
                <div class="space-y-4">
                    @foreach($cartItems as $cartId => $item)
                    <div class="bg-white border border-slate-200 p-5 rounded-3xl flex flex-col md:flex-row gap-5 items-center shadow-sm relative group hover:border-sanggared/30 transition-all">
                        
                        <div class="w-full md:w-32 h-32 bg-slate-50 rounded-2xl flex-shrink-0 flex items-center justify-center overflow-hidden border border-slate-100">
                            @if($item['product']->image_path)
                                <img src="{{ asset('storage/' . $item['product']->image_path) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fa-regular fa-image text-3xl text-slate-300"></i>
                            @endif
                        </div>
                        
                        <div class="flex-grow w-full text-center md:text-left">
                            <h3 class="font-black text-sanggablue text-xl">{{ $item['product']->name }}</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase mt-1 tracking-wider">{{ $item['qty'] }} Pcs</p>
                            
                            @if(!empty($item['variant_names']))
                                <div class="mt-3 flex flex-wrap gap-2 justify-center md:justify-start">
                                    @foreach($item['variant_names'] as $vName)
                                        <span class="text-[9px] font-black text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-1 rounded uppercase tracking-wider">+ {{ $vName }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        
                        <div class="w-full md:w-auto text-center md:text-right border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-6">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Subtotal</span>
                            <span class="block font-black text-sanggared text-2xl">Rp {{ number_format($item['total_price'], 0, ',', '.') }}</span>
                            
                            <div class="flex items-center justify-center md:justify-end gap-3 mt-4">
                                <form action="{{ route('user.cart.remove', $cartId) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors flex items-center justify-center shadow-inner">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                                @php $variantsJson = json_encode($item['variants']); @endphp
                                <a href="{{ route('user.order.create', ['product_id' => $item['product']->id, 'qty' => $item['qty'], 'variants' => $variantsJson]) }}" class="px-5 py-2.5 bg-sanggablue hover:bg-sanggared text-white font-black text-[10px] uppercase tracking-wider rounded-xl shadow-md transition-all flex items-center gap-2">
                                    Checkout <i class="fa-solid fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white border border-slate-200 rounded-3xl p-16 text-center shadow-sm">
                    <i class="fa-solid fa-cart-shopping text-6xl text-slate-200 mb-5"></i>
                    <h3 class="text-xl font-black text-sanggablue mb-2">Keranjang Anda Kosong</h3>
                    <p class="text-sm text-slate-500 mb-8">Belum ada layanan cetak yang Anda masukkan ke keranjang.</p>
                    <a href="{{ route('public.katalog') }}" class="inline-flex items-center gap-2 bg-sanggared hover:bg-red-700 text-white font-black px-6 py-3.5 rounded-xl text-xs uppercase tracking-wider shadow-md transition-all">
                        <i class="fa-solid fa-print"></i> Mulai Belanja
                    </a>
                </div>
            @endif
        </div>

        <div x-show="tab === 'pesanan'" x-cloak class="space-y-5">
            @forelse($orders as $order)
            <div class="bg-white border border-slate-200 p-6 rounded-3xl shadow-sm hover:shadow-md transition-shadow">
                <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-5 pb-5 border-b border-slate-100">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider"><i class="fa-regular fa-clock"></i> {{ $order->created_at->format('d M Y, H:i') }}</span>
                        <h3 class="font-black text-sanggablue text-lg leading-tight mt-1 uppercase">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</h3>
                    </div>
                    <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider shadow-inner
                        {{ $order->status == 'Selesai' ? 'bg-emerald-50 text-emerald-600' : ($order->status == 'Dalam Antrian' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600') }}">
                        {{ $order->status }}
                    </span>
                </div>
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <span class="block text-sm font-bold text-slate-600">{{ $order->service_name }}</span>
                        <span class="block font-black text-sanggared text-xl mt-1">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                        
                        @php
                            $nomorOrder = str_pad($order->id, 4, '0', STR_PAD_LEFT);
                            $pesanWa = urlencode("Halo Admin Sangga Buana, saya ingin menanyakan status pesanan saya dengan Nomor Tagihan #ORD-{$nomorOrder}.");
                            $separator = str_contains($waBaseUrl, '?') ? '&' : '?';
                            $linkWaOrder = $waBaseUrl . $separator . "text=" . $pesanWa;
                        @endphp

                        <a href="{{ $linkWaOrder }}" target="_blank" class="px-5 py-3 bg-white hover:bg-emerald-50 text-emerald-600 font-black text-[10px] uppercase tracking-wider rounded-xl transition-all border border-emerald-200 hover:border-emerald-500 flex items-center justify-center gap-2">
                            <i class="fa-brands fa-whatsapp text-sm"></i> Tanya Admin
                        </a>

                        <a href="{{ route('user.order.show', $order->id) }}" class="px-5 py-3 bg-slate-100 hover:bg-sanggared hover:text-white text-sanggablue font-black text-[10px] uppercase tracking-wider rounded-xl transition-all border border-slate-200 hover:border-sanggared text-center">
                            Rincian Pesanan
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white border border-slate-200 rounded-3xl p-16 text-center shadow-sm">
                <i class="fa-solid fa-receipt text-6xl text-slate-200 mb-5"></i>
                <h3 class="text-xl font-black text-sanggablue mb-2">Belum Ada Transaksi</h3>
                <p class="text-sm text-slate-500">Anda belum pernah melakukan pemesanan layanan cetak.</p>
            </div>
            @endforelse
        </div>

    </div>
</div>
@endsection