@extends('layouts.admin')

@section('page_title', 'Keuangan & Pembayaran')

@section('admin_content')
<div class="space-y-6">
  <p class="text-xs font-medium text-slate-400 -mt-6">Pantau arus kas pendapatan dan kelola metode pembayaran bisnis Anda.</p>

  <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    
    <div class="xl:col-span-2 space-y-6">
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-[#032B22] rounded-3xl p-6 text-white shadow-lg relative overflow-hidden flex flex-col justify-between h-40">
          <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
          
          <div>
            <span class="block text-[10px] font-black uppercase tracking-wider text-white/60 mb-1">Total Pendapatan (Bulan Ini)</span>
            <h3 class="text-2xl font-black tracking-tight">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3>
          </div>
          <div class="flex justify-between items-end">
            <div>
              <span class="block text-[9px] uppercase tracking-wider text-white/60">Sangga Buana</span>
              <span class="block text-xs font-bold">{{ \Carbon\Carbon::now()->translatedFormat('F Y') }}</span>
            </div>
            <i class="fa-solid fa-wallet text-3xl text-white/20"></i>
          </div>
        </div>

        <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm flex flex-col justify-center items-center text-center h-40">
          <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-lg mb-3 shadow-inner">
            <i class="fa-solid fa-money-bill-trend-up"></i>
          </div>
          <span class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Masuk Hari Ini</span>
          <h4 class="text-base font-black text-sanggablue mt-1">+Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h4>
        </div>

        <div class="bg-white border border-slate-100 rounded-3xl p-5 shadow-sm flex flex-col justify-center items-center text-center h-40">
          <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-lg mb-3 shadow-inner">
            <i class="fa-solid fa-hourglass-half"></i>
          </div>
          <span class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Antrean Pesanan</span>
          <h4 class="text-base font-black text-sanggablue mt-1">{{ $menungguPembayaran }} Pesanan</h4>
        </div>
      </div>

      <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
        <div class="flex justify-between items-center mb-5">
          <h3 class="text-sm font-black text-sanggablue tracking-wider">Metode Pembayaran</h3>
          <a href="{{ route('admin.rekening.create') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-[10px] uppercase rounded-lg shadow-sm transition-all flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Baru
          </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          @forelse($payments as $payment)
          <div class="p-4 border border-slate-100 rounded-2xl flex items-center justify-between group hover:border-sanggablue/30 transition-all bg-slate-50/50">
            <div class="flex items-center gap-4">
              <div class="text-2xl {{ $payment->category == 'QRIS' ? 'text-blue-500' : 'text-slate-700' }}">
                @if($payment->category == 'QRIS') <i class="fa-solid fa-qrcode"></i>
                @elseif($payment->category == 'E-Wallet') <i class="fa-solid fa-wallet"></i>
                @else <i class="fa-brands fa-cc-mastercard"></i> @endif
              </div>
              <div>
                <h4 class="font-black text-sanggablue text-xs uppercase">{{ $payment->name }}</h4>
                <p class="text-[10px] font-bold text-slate-400 font-mono tracking-widest mt-0.5">
                  {{ $payment->account_number ?? '**** **** QRIS' }}
                </p>
              </div>
            </div>
            
            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
              <a href="{{ route('admin.rekening.edit', $payment->id) }}" class="w-7 h-7 rounded bg-white border text-blue-500 hover:bg-blue-50 flex items-center justify-center transition-all shadow-sm">
                <i class="fa-solid fa-pen text-[9px]"></i>
              </a>
              <form action="{{ route('admin.rekening.destroy', $payment->id) }}" method="POST" class="inline form-delete-rekening">
                @csrf @method('DELETE')
                <button type="button" class="w-7 h-7 rounded bg-white border text-red-500 hover:bg-red-50 flex items-center justify-center transition-all shadow-sm btn-delete-rekening">
                  <i class="fa-solid fa-trash-can text-[9px]"></i>
                </button>
              </form>
            </div>
          </div>
          @empty
          <div class="col-span-2 text-center py-6 text-slate-400 text-xs font-bold">Belum ada metode pembayaran yang ditambahkan.</div>
          @endforelse
        </div>
      </div>
    </div>

    <div class="space-y-6">
      
      <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
        <h3 class="text-sm font-black text-sanggablue tracking-wider mb-5">Invoices Terbaru</h3>
        <div class="space-y-4">
          @forelse($invoiceTerbaru as $invoice)
          <div class="flex items-center justify-between">
            <div>
              <span class="block text-xs font-bold text-sanggablue">{{ $invoice->created_at->format('d M Y') }}</span>
              <span class="block text-[9px] font-black text-slate-400 uppercase">#ORD-{{ str_pad($invoice->id, 4, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="flex items-center gap-3">
              <span class="text-xs font-black text-slate-600">Rp {{ number_format($invoice->total_price, 0, ',', '.') }}</span>
              <button class="px-3 py-1.5 rounded-lg border border-slate-200 text-[9px] font-black uppercase text-slate-600 hover:border-sanggared hover:text-sanggared transition-all">
                PDF
              </button>
            </div>
          </div>
          @empty
          <div class="text-center text-slate-400 text-xs">Belum ada tagihan masuk.</div>
          @endforelse
        </div>
      </div>

      <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
        <div class="flex justify-between items-center mb-5">
          <h3 class="text-sm font-black text-sanggablue tracking-wider">Riwayat Uang Masuk</h3>
          <span class="text-[9px] font-black text-slate-400 uppercase">Status: Selesai</span>
        </div>
        <div class="space-y-4">
          @forelse($riwayatTransaksi as $transaksi)
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-full border-2 border-emerald-500 flex items-center justify-center text-emerald-500 bg-emerald-50">
                <i class="fa-solid fa-arrow-down text-[10px]"></i>
              </div>
              <div>
                <span class="block text-xs font-bold text-sanggablue">{{ $transaksi->customer_name }}</span>
                <span class="block text-[9px] text-slate-400">{{ $transaksi->updated_at->format('d M Y, H:i') }}</span>
              </div>
            </div>
            <span class="text-xs font-black text-emerald-500">+ Rp {{ number_format($transaksi->total_price, 0, ',', '.') }}</span>
          </div>
          @empty
          <div class="text-center py-4 text-slate-400 text-[10px] font-bold">Belum ada transaksi yang berstatus selesai.</div>
          @endforelse
        </div>
      </div>

    </div>
  </div>
</div>

<script>
  document.querySelectorAll('.btn-delete-rekening').forEach(button => {
    button.addEventListener('click', function(e) {
      const form = this.closest('.form-delete-rekening');
      Swal.fire({
        title: 'Hapus Metode Pembayaran?',
        text: "Data ini akan dihapus. Pelanggan tidak bisa lagi memilih metode ini saat checkout.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true,
        padding: '2rem',
        customClass: {
          popup: 'rounded-3xl border shadow-xl bg-white',
          title: 'text-base font-extrabold text-sanggablue',
          actions: 'w-full flex gap-3',
          confirmButton: 'px-5 py-2.5 bg-red-600 text-white font-black text-xs rounded-xl',
          cancelButton: 'px-5 py-2.5 bg-slate-100 text-sanggablue font-black text-xs rounded-xl',
          backdrop: 'backdrop-blur-sm'
        },
        buttonsStyling: false
      }).then((result) => {
        if (result.isConfirmed) form.submit();
      });
    });
  });
</script>
@endsection