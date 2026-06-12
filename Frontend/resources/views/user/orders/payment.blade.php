@extends('layouts.app')

@section('content')
<div class="bg-slate-50 min-h-screen py-10" x-data="{ openCategory: null }">
    <div class="max-w-5xl mx-auto px-4 md:px-6">
        
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-6 rounded-3xl mb-8 flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm text-center md:text-left">
            <div>
                <h2 class="text-lg font-black tracking-tight mb-1">Pesanan Anda Berhasil Diterima! <i class="fa-solid fa-party-horn ml-1"></i></h2>
                <p class="text-xs font-medium">Berkas desain telah masuk ke sistem. Silakan lakukan pembayaran agar pesanan segera diproses.</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-check-double"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-5">
                <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-2 bg-sanggared"></div>
                    
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6">Informasi Tagihan</h3>
                    
                    <div class="space-y-4 mb-8">
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Nomor Pesanan</span>
                            <span class="block text-base font-black text-sanggablue uppercase">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Layanan Cetak</span>
                            <span class="block text-sm font-bold text-slate-700 leading-snug">{{ $order->service_name }}</span>
                        </div>
                        <div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">Waktu Pemesanan</span>
                            <span class="block text-sm font-bold text-slate-700">{{ $order->created_at->translatedFormat('d F Y, H:i') }}</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 border-dashed">
                        <span class="block text-xs font-bold text-slate-400 uppercase mb-1">Total Pembayaran</span>
                        <h2 class="text-4xl font-black text-sanggared tracking-tight">Rp {{ number_format($order->total_price, 0, ',', '.') }}</h2>
                        <div class="bg-blue-50 text-blue-600 text-[10px] font-bold px-3 py-2 rounded-lg mt-3 flex gap-2">
                            <i class="fa-solid fa-circle-info mt-0.5"></i>
                            <p>Pastikan Anda mentransfer sesuai dengan nominal di atas hingga 3 digit terakhir.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 space-y-6">
                <h3 class="text-xl font-black text-sanggablue tracking-tight">Pilih Metode Pembayaran</h3>
                
                <div class="space-y-4">
                    @forelse($paymentMethods as $kategori => $metodeList)
                        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm transition-all duration-300">
                            <button @click="openCategory !== '{{ $kategori }}' ? openCategory = '{{ $kategori }}' : openCategory = null" class="w-full p-5 flex items-center justify-between bg-slate-50 hover:bg-slate-100 transition-colors focus:outline-none">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl bg-white shadow-sm
                                        {{ $kategori == 'QRIS' ? 'text-blue-500' : ($kategori == 'E-Wallet' ? 'text-emerald-500' : 'text-slate-700') }}">
                                        @if($kategori == 'QRIS') <i class="fa-solid fa-qrcode"></i>
                                        @elseif($kategori == 'E-Wallet') <i class="fa-solid fa-wallet"></i>
                                        @else <i class="fa-solid fa-building-columns"></i> @endif
                                    </div>
                                    <div class="text-left">
                                        <h4 class="font-black text-sanggablue text-sm uppercase tracking-wider">{{ $kategori }}</h4>
                                        <p class="text-[10px] text-slate-400 font-bold mt-0.5">{{ count($metodeList) }} Opsi Tersedia</p>
                                    </div>
                                </div>
                                <i class="fa-solid fa-chevron-down text-slate-400 transition-transform duration-300" :class="openCategory === '{{ $kategori }}' ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="openCategory === '{{ $kategori }}'" x-collapse x-cloak>
                                <div class="p-5 border-t border-slate-100 space-y-4 bg-white">
                                    @foreach($metodeList as $metode)
                                        <div class="p-4 border border-slate-100 rounded-xl bg-slate-50 flex flex-col sm:flex-row items-center sm:justify-between gap-4 text-center sm:text-left">
                                            
                                            <div class="flex-grow">
                                                <span class="block text-xs font-black text-sanggablue uppercase">{{ $metode->name }}</span>
                                                @if($metode->account_number)
                                                    <span class="block font-mono text-lg font-bold text-slate-600 mt-1 tracking-wider select-all">{{ $metode->account_number }}</span>
                                                @endif
                                                @if($metode->account_name)
                                                    <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">A.N: {{ $metode->account_name }}</span>
                                                @endif
                                            </div>

                                            @if($metode->qr_image)
                                                <div class="w-32 h-32 bg-white p-2 rounded-xl shadow-sm border border-slate-200 shrink-0">
                                                    <img src="{{ asset('storage/' . $metode->qr_image) }}" class="w-full h-full object-cover rounded-lg">
                                                </div>
                                            @endif
                                            
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center shadow-sm">
                            <i class="fa-solid fa-triangle-exclamation text-3xl text-amber-500 mb-3"></i>
                            <h4 class="font-black text-sanggablue mb-1">Metode Pembayaran Belum Tersedia</h4>
                            <p class="text-xs text-slate-500">Silakan hubungi admin untuk informasi pembayaran lebih lanjut.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-10 pt-6 flex flex-col sm:flex-row gap-4 items-center justify-between">
                    <p class="text-[10px] text-slate-400 font-bold max-w-xs text-center sm:text-left">
                        *Jika Anda sudah mentransfer, sistem atau admin kami akan segera memverifikasinya.
                    </p>
                    <a href="{{ route('user.order.index') }}" class="w-full sm:w-auto px-8 py-3.5 bg-sanggablue hover:bg-sanggared text-white font-black text-xs uppercase tracking-wider rounded-xl shadow-md transition-all text-center">
                        Selesai & Cek Pesanan Saya
                    </a>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection