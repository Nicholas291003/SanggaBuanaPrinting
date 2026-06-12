@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
  
  <div class="lg:col-span-7 bg-white border border-slate-100 p-6 md:p-8 rounded-3xl shadow-xl space-y-6">
    <div class="flex justify-between items-center border-b pb-4">
      <div>
        <span class="text-[9px] font-black text-sanggared uppercase tracking-wider block">Nota Transaksi</span>
        <h3 class="font-black text-sanggablue text-lg">#ORD-{{ $order->id }}</h3>
      </div>
      <a href="{{ route('user.order.index') }}" class="text-xs font-bold text-slate-400 hover:text-sanggablue">&larr; Kembali</a>
    </div>

    <div class="space-y-4 text-xs font-semibold text-slate-600">
      <div class="flex justify-between border-b border-slate-50 pb-2">
        <span class="text-slate-400">Nama Layanan Cetak</span>
        <span class="text-sanggablue font-black text-right capitalize">{{ $order->service_name }}</span>
      </div>
      <div class="flex justify-between border-b border-slate-50 pb-2">
        <span class="text-slate-400">Nama Pemesan</span>
        <span class="text-sanggablue text-right">{{ $order->customer_name }}</span>
      </div>
      <div class="flex justify-between border-b border-slate-50 pb-2">
        <span class="text-slate-400">Kontal Email</span>
        <span class="text-sanggablue text-right">{{ $order->customer_email }}</span>
      </div>
      <div class="flex justify-between border-b border-slate-50 pb-2">
        <span class="text-slate-400">Tanggal Pengajuan</span>
        <span class="text-sanggablue text-right">{{ $order->created_at->format('d F Y, H:i') }} WIB</span>
      </div>
      
      <div class="bg-slate-50 p-4 rounded-2xl flex justify-between items-center text-sm">
        <span class="font-bold text-slate-400">Total Biaya Cetak</span>
        <span class="font-black text-sanggared text-base">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
      </div>
    </div>
  </div>

  <div class="lg:col-span-5 bg-white border border-slate-100 p-6 rounded-3xl shadow-md space-y-6">
    <h4 class="font-black text-sanggablue text-sm border-b pb-3"><i class="fa-solid fa-bars-staged text-sanggared"></i> Status Pelacakan Real-time</h4>
    
    <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100">
      
      <div class="relative space-y-1">
        <div class="absolute -left-6 top-1 w-4 h-4 rounded-full border-4 border-white shadow-sm 
          {{ $order->status != 'Dibatalkan' ? 'bg-sanggared scale-110' : 'bg-slate-200' }}"></div>
        <h5 class="text-xs font-black {{ $order->status != 'Dibatalkan' ? 'text-sanggablue' : 'text-slate-300' }}">1. Masuk Kotak Antrean</h5>
        <p class="text-[10px] text-slate-400 font-medium">Berkas desain Anda berhasil diterima sistem produksi.</p>
      </div>

      <div class="relative space-y-1">
        <div class="absolute -left-6 top-1 w-4 h-4 rounded-full border-4 border-white shadow-sm
          {{ in_array($order->status, ['Sedang Diproses', 'Siap Diambil', 'Siap Dikirim', 'Selesai']) ? 'bg-sanggared scale-110' : 'bg-slate-200' }}"></div>
        <h5 class="text-xs font-black {{ in_array($order->status, ['Sedang Diproses', 'Siap Diambil', 'Siap Dikirim', 'Selesai']) ? 'text-sanggablue' : 'text-slate-300' }}">2. Proses Mesin Produksi</h5>
        <p class="text-[10px] text-slate-400 font-medium">Operator sedang melakukan kalibrasi warna dan naik cetak.</p>
      </div>

      <div class="relative space-y-1">
        <div class="absolute -left-6 top-1 w-4 h-4 rounded-full border-4 border-white shadow-sm
          {{ in_array($order->status, ['Siap Diambil', 'Siap Dikirim', 'Selesai']) ? 'bg-sanggared scale-110' : 'bg-slate-200' }}"></div>
        <h5 class="text-xs font-black {{ in_array($order->status, ['Siap Diambil', 'Siap Dikirim', 'Selesai']) ? 'text-sanggablue' : 'text-slate-300' }}">3. Finishing & QC Selesai</h5>
        <p class="text-[10px] text-slate-400 font-medium">Pesanan telah dikemas rapi dan siap didistribusikan ke tangan Anda.</p>
      </div>

      <div class="relative space-y-1">
        <div class="absolute -left-6 top-1 w-4 h-4 rounded-full border-4 border-white shadow-sm
          {{ $order->status == 'Selesai' ? 'bg-emerald-500 scale-110' : 'bg-slate-200' }}"></div>
        <h5 class="text-xs font-black {{ $order->status == 'Selesai' ? 'text-emerald-600' : 'text-slate-300' }}">4. Transaksi Selesai</h5>
        <p class="text-[10px] text-slate-400 font-medium">Serah terima cetakan sukses dilakukan.</p>
      </div>

    </div>
  </div>

</div>
@endsection