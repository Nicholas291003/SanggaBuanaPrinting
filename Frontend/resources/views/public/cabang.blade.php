@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-12 space-y-8">
  
  <!-- Header Title -->
  <div class="border-b border-slate-50 pb-4 space-y-1 text-center">
    <span class="text-[9px] font-black text-sanggared uppercase tracking-widest block">Lokasi Operasional</span>
    <h2 class="text-2xl font-black text-sanggablue tracking-tight">Cabang Percetakan Sangga Buana</h2>
    <p class="text-xs font-medium text-slate-400 max-w-md mx-auto">Temukan outlet produksi terdekat kami untuk konsultasi cetak fisik secara langsung atau pengambilan berkas order.</p>
  </div>

  <!-- Grid Card Daftar Cabang Toko -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($branches as $branch)
      <div class="bg-white border border-slate-100 p-6 rounded-3xl shadow-md space-y-4 hover:scale-[1.01] transition-all">
        <div class="flex justify-between items-start gap-2">
          <h4 class="font-black text-sanggablue text-base capitalize tracking-tight">{{ $branch->name }}</h4>
          <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100">
            {{ $branch->status }}
          </span>
        </div>
        
        <p class="text-xs text-slate-400 font-medium leading-relaxed">
          <i class="fa-solid fa-location-dot text-sanggared mr-1"></i> {{ $branch->address }}
        </p>

        <div class="text-[11px] font-bold text-slate-500 flex justify-between pt-3 border-t border-slate-50 items-center">
          <span>
            <i class="fa-regular fa-clock text-sanggablue mr-1"></i> 
            {{ substr($branch->opening_time, 0, 5) }} - {{ substr($branch->closing_time, 0, 5) }}
          </span>
          <a href="https://wa.me/{{ $branch->phone }}" target="_blank" class="px-3 py-1 bg-slate-50 hover:bg-emerald-50 text-slate-600 hover:text-emerald-600 rounded-lg transition-all flex items-center gap-1">
            <i class="fa-brands fa-whatsapp text-emerald-500"></i> Hubungi
          </a>
        </div>
      </div>
    @empty
      <div class="col-span-full text-center text-slate-400 text-xs py-12 font-medium">
        <div class="text-xl mb-2"><i class="fa-solid fa-store-slash"></i></div>
        Belum ada informasi data cabang toko yang didaftarkan dalam sistem.
      </div>
    @endforelse
  </div>

</div>
@endsection