@extends('layouts.app')

@section('content')
@php
    // Tarik data pengaturan konten dari database
    $setting = \App\Models\CompanySetting::first();
    $sosmedList = collect($setting->social_media ?? []);
    // Cari secara cerdas link khusus platform whatsapp dari array JSON sosmed
    $waLink = $sosmedList->firstWhere('platform', 'whatsapp')['url'] ?? 'https://wa.me/6281234567890';
@endphp

<div class="bg-slate-50 min-h-screen py-16">
    <div class="max-w-5xl mx-auto px-6 space-y-12">
        
        <div class="text-center space-y-3">
            <span class="text-[10px] font-black text-sanggared uppercase tracking-widest bg-red-50 px-4 py-1.5 rounded-full border border-red-100">
                Bantuan & Dukungan
            </span>
            <h1 class="text-3xl md:text-4xl font-black text-sanggablue tracking-tight">
                Hubungi Sangga Buana
            </h1>
            <p class="text-sm text-slate-500 max-w-md mx-auto leading-relaxed">
                Punya pertanyaan mengenai spesifikasi cetak atau pesanan massal? Tim CS kami siap membantu Anda.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-stretch">
            
            <div class="md:col-span-5 flex flex-col gap-6">
                <a href="{{ $waLink }}" target="_blank" class="bg-white border border-slate-200 p-6 rounded-3xl shadow-sm hover:border-sanggared/30 hover:shadow-md transition-all block group">
                    <div class="w-10 h-10 rounded-xl bg-red-50 text-sanggared flex items-center justify-center text-lg mb-4 shadow-sm">
                        <i class="fa-brands fa-whatsapp"></i>
                    </div>
                    <h3 class="font-black text-sanggablue text-base">WhatsApp Layanan Pelanggan</h3>
                    <p class="text-xs text-slate-400 font-medium mt-1 leading-relaxed">Respons cepat untuk konsultasi bahan, harga, dan update pengiriman.</p>
                    <span class="block text-sanggared font-black text-sm mt-4 group-hover:translate-x-1 transition-transform">
                        {{ $setting->phone ?? '+62 812-3456-7890' }} &rarr;
                    </span>
                </a>

                <div class="bg-white border border-slate-200 p-6 rounded-3xl shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 text-sanggablue flex items-center justify-center text-lg mb-4 border">
                        <i class="fa-regular fa-envelope"></i>
                    </div>
                    <h3 class="font-black text-sanggablue text-base">Korespondensi Email</h3>
                    <p class="text-xs text-slate-400 font-medium mt-1 leading-relaxed">Kirimkan proposal kerja sama kemitraan bisnis atau keluhan formal.</p>
                    <span class="block text-sanggablue font-mono font-bold text-xs mt-4 select-all">
                        {{ $setting->email ?? 'support@sanggabuana.com' }}
                    </span>
                </div>
            </div>

            <div class="md:col-span-7">
                <div class="bg-sanggablue text-white p-8 rounded-3xl shadow-lg h-full flex flex-col justify-between relative overflow-hidden">
                    <div class="space-y-6 z-10 relative">
                        <div>
                            <h3 class="text-lg font-black tracking-tight">Waktu Pelayanan Operasional</h3>
                            <p class="text-xs text-white/60 mt-1">Waktu kerja utama untuk proses produksi mesin cetak.</p>
                        </div>

                        <div class="space-y-4 pt-4 border-t border-white/10">
                            @forelse($setting->operational_hours ?? [] as $hour)
                                <div class="flex justify-between items-center py-2 border-b border-white/5 border-dashed text-xs font-bold">
                                    <span class="text-white/70">{{ $hour['day'] }}</span>
                                    <span class="{{ $hour['time'] == 'Libur Produksi' ? 'text-sanggared font-black' : 'text-white' }}">{{ $hour['time'] }}</span>
                                </div>
                            @empty
                                <p class="text-xs text-white/50 italic">Jam operasional belum dikonfigurasi oleh admin.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white/5 border border-white/10 rounded-xl p-4 mt-8 text-[11px] text-white/70 leading-relaxed font-medium z-10 relative">
                        * Catatan: Pengiriman berkas desain melalui website tetap dapat dilakukan 24 jam, namun peninjauan file dan naik cetak akan diproses pada jam operasional di atas.
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection