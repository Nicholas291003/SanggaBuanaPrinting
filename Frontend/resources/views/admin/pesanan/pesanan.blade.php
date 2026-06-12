@extends('layouts.admin')

@section('page_title', 'Daftar Pesanan Masuk')

@section('admin_content')
<div class="space-y-6">
  <p class="text-xs font-medium text-slate-400 -mt-6">Kelola dan perbarui status produksi pesanan pelanggan.</p>

  <a href="{{ route('admin.pesanan.create') }}" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs uppercase rounded-xl shadow-sm transition-all flex items-center gap-2 max-w-max">
      <i class="fa-solid fa-plus"></i> Tambah Pesanan Manual
  </a>

  <form method="GET" action="{{ route('admin.pesanan.index') }}" class="flex flex-wrap items-center gap-3 mb-6 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
    <div class="flex items-center gap-2 mr-2">
      <i class="fa-solid fa-filter text-slate-300"></i>
      <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Filter:</span>
    </div>

    <select name="status" onchange="this.form.submit()" class="px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold text-sanggablue outline-none cursor-pointer">
      <option value="">Semua Status</option>
      <option value="Dalam Antrian" {{ request('status') == 'Dalam Antrian' ? 'selected' : '' }}>🔴 Dalam Antrian</option>
      <option value="Sedang Diproses" {{ request('status') == 'Sedang Diproses' ? 'selected' : '' }}>🟡 Sedang Diproses</option>
      <option value="Siap Diambil" {{ request('status') == 'Siap Diambil' ? 'selected' : '' }}>🔵 Siap Diambil</option>
      <option value="Siap Dikirim" {{ request('status') == 'Siap Dikirim' ? 'selected' : '' }}>📦 Siap Dikirim</option>
      <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>🟢 Selesai</option>
      <option value="Dibatalkan" {{ request('status') == 'Dibatalkan' ? 'selected' : '' }}>⚫ Dibatalkan</option>
    </select>
    
    <select name="sort" onchange="this.form.submit()" class="px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold text-sanggablue outline-none cursor-pointer">
      <option value="Terbaru" {{ request('sort') == 'Terbaru' ? 'selected' : '' }}>Waktu: Terbaru</option>
      <option value="Terlama" {{ request('sort') == 'Terlama' ? 'selected' : '' }}>Waktu: Terlama</option>
    </select>
  </form>

  <!-- ================= LAYOUT TABEL UTAMA PESANAN ================= -->
  <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="bg-slate-50 text-slate-400 uppercase font-black tracking-wider text-[10px] border-b border-slate-100">
            <th class="p-4 w-1/4">Info Pesanan</th>
            <th class="p-4 w-1/4">Pelanggan</th>
            <th class="p-4 w-1/4">Total Bayar</th>
            <th class="p-4 text-left">Update Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-slate-600 font-semibold">

          {{-- Loop Data Transaksi dari database --}}
          @forelse($orders as $order)
          <tr class="hover:bg-slate-50/50 transition-all">
            <!-- Kolom 1: Info Pesanan -->
            <td class="p-4">
              <span class="block font-black text-sanggablue text-sm">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
              <span class="block text-[11px] text-slate-400 font-bold mt-0.5 capitalize">{{ $order->service_name }}</span>
            </td>
            
            <!-- Kolom 2: Pelanggan -->
            <td class="p-4">
              <span class="block text-sanggablue font-bold text-sm">{{ $order->customer_name }}</span>
              <span class="block text-[10px] text-slate-400 font-normal mt-0.5">
                {{ $order->created_at->format('d M Y, H:i') }}
              </span>
            </td>
            
            <!-- Kolom 3: Total Bayar -->
            <td class="p-4 font-black text-sanggablue text-sm">
              Rp {{ number_format($order->total_price, 0, ',', '.') }}
            </td>
            
            <!-- Kolom 4: Update Status, Tombol Simpan dan Tombol Hapus -->
            <td class="p-4 flex items-center gap-4">
              <form action="{{ route('admin.pesanan.updateStatus', $order->id) }}" method="POST" class="flex items-center gap-2">
                @csrf
                @method('PUT') <select name="status" class="px-3 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none bg-slate-50 font-bold text-sanggablue cursor-pointer">
                  <option value="Dalam Antrian" {{ $order->status == 'Dalam Antrian' ? 'selected' : '' }}>🔴 Dalam Antrian</option>
                  <option value="Sedang Diproses" {{ $order->status == 'Sedang Diproses' ? 'selected' : '' }}>🟡 Sedang Diproses</option>
                  <option value="Siap Diambil" {{ $order->status == 'Siap Diambil' ? 'selected' : '' }}>🔵 Siap Diambil</option>
                  <option value="Siap Dikirim" {{ $order->status == 'Siap Dikirim' ? 'selected' : '' }}>📦 Siap Dikirim</option>
                  <option value="Selesai" {{ $order->status == 'Selesai' ? 'selected' : '' }}>🟢 Selesai</option>
                  <option value="Dibatalkan" {{ $order->status == 'Dibatalkan' ? 'selected' : '' }}>⚫ Dibatalkan</option>
                </select>

                <button type="submit" class="w-8 h-8 rounded-lg bg-sanggablue hover:bg-sanggablue/90 text-white flex items-center justify-center transition-all shadow-sm" title="Simpan Perubahan Status">
                  <i class="fa-solid fa-floppy-disk text-xs"></i>
                </button>
              </form>

              <form action="{{ route('admin.pesanan.destroy', $order->id) }}" method="POST" class="inline form-delete-pesanan">
                @csrf @method('DELETE')
                <button type="button" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-600 text-red-600 hover:text-white flex items-center justify-center transition-all shadow-sm btn-delete-pesanan" title="Hapus Permanen">
                  <i class="fa-solid fa-trash-can text-xs"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <!-- Jika Tidak Ada Data -->
          <tr>
            <td colspan="4" class="p-12 text-center text-slate-400 font-medium">
              <div class="text-3xl mb-2 text-slate-300"><i class="fa-solid fa-receipt"></i></div>
              <p class="text-xs">Belum ada data riwayat pesanan masuk di database.</p>
            </td>
          </tr>
          @endforelse

        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  document.querySelectorAll('.btn-delete-pesanan').forEach(button => {
    button.addEventListener('click', function(e) {
      const form = this.closest('.form-delete-pesanan');

      Swal.fire({
        title: 'Hapus Pesanan Ini?',
        text: "Data pesanan akan dihapus permanen, namun rekam jejak (history) penghapusan ini akan tetap tersimpan dengan aman di menu Log Aktivitas.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus Pesanan',
        cancelButtonText: 'Batalkan',
        reverseButtons: true,
        padding: '2rem',
        customClass: {
          popup: 'rounded-3xl border border-slate-100 shadow-xl max-w-sm bg-white',
          title: 'text-base font-extrabold text-sanggablue mt-2',
          htmlContainer: 'text-xs text-slate-400 font-medium leading-relaxed mt-2',
          actions: '!mt-6 !mb-1 flex items-center justify-center gap-3 w-full',
          confirmButton: 'px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-black text-xs rounded-xl shadow-md transition-all cursor-pointer',
          cancelButton: 'px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-sanggablue font-black text-xs rounded-xl transition-all cursor-pointer',
          backdrop: 'backdrop-blur-sm bg-slate-900/40'
        },
        buttonsStyling: false
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
</script>
@endsection