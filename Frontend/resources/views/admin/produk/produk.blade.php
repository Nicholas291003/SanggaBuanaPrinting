@extends('layouts.admin')

@section('page_title', 'Katalog Produk')

@section('admin_content')
<div class="space-y-6">
  <p class="text-xs font-medium text-slate-400 -mt-6">Buat dan kelola katalog produk Anda.</p>

  <!-- ================= BAR ATAS: JUDUL & TOMBOL TAMBAH ================= -->
  <div class="flex justify-between items-center bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
    <div class="flex items-center gap-2 text-sanggablue font-bold text-sm">
      <i class="fa-solid fa-box text-sanggared"></i>
      <span>Total Ringkasan Katalog</span>
    </div>
    
    <!-- Tombol Tambah Produk Menuju Halaman Create -->
    <a href="{{ route('admin.produk.create')}}" class="px-5 py-2.5 bg-sanggablue hover:bg-sanggablue/90 text-white font-bold text-xs rounded-xl flex items-center gap-2 shadow-md transition-all">
      <i class="fa-solid fa-plus text-sanggacream"></i> Tambah Produk Baru
    </a>
  </div>

  <!-- ================= LAYOUT TABEL KATALOG PRODUK ================= -->
  <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="bg-slate-50 text-slate-400 uppercase font-black tracking-wider text-[10px] border-b border-slate-100">
            <th class="p-4 w-1/3">Produk</th>
            <th class="p-4 w-1/3">Kategori & Deskripsi</th>
            <th class="p-4">Harga Dasar</th>
            <th class="p-4 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-slate-600 font-semibold">
          
          {{-- Loop Eloquent --}}
          @forelse($products as $product)
          <tr class="hover:bg-slate-50/50 transition-all">
            <td class="p-4">
              <div class="flex items-center gap-4">
                <!-- Mini Thumbnail Foto Produk -->
                <div class="w-12 h-12 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                  @if($product->image_path)
                    <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover">
                  @else
                    <div class="text-slate-300 text-lg"><i class="fa-regular fa-image"></i></div>
                  @endif
                </div>
                <span class="font-extrabold text-sanggablue text-sm capitalize">{{ $product->name }}</span>
              </div>
            </td>
            <td class="p-4">
              <span class="px-2 py-0.5 rounded bg-sanggacream text-sanggablue text-[9px] font-black uppercase tracking-wider block w-max mb-1">
                {{ $product->category }}
              </span>
              <span class="block text-slate-400 text-[11px] font-normal max-w-xs truncate">
                {{ $product->description ?? 'Tidak ada deskripsi spesifikasi.' }}
              </span>
            </td>
            <td class="p-4 font-black text-sanggablue text-sm">
              Rp {{ number_format($product->base_price, 0, ',', '.') }}
            </td>
            <td class="p-4 text-center space-x-1.5">
              <!-- Tombol Ubah / Edit -->
              <a href="{{ route('admin.produk.edit', $product->id) }}" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-sanggablue hover:text-white text-slate-400 inline-flex items-center justify-center transition-all">
                <i class="fa-regular fa-pen-to-square text-xs"></i>
              </a>
              
              <!-- Tombol Hapus / Delete -->
              <form action="{{ route('admin.produk.destroy', $product->id) }}" method="POST" class="inline form-delete-produk">
                @csrf
                @method('DELETE')
                
                <button type="button" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-sanggared hover:text-white text-slate-400 inline-flex items-center justify-center transition-all btn-delete-trigger">
                  <i class="fa-regular fa-trash-can text-xs"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <!-- Jika Tidak Ada Data -->
          <tr>
            <td colspan="4" class="p-12 text-center text-slate-400 font-medium">
              <div class="text-3xl mb-2 text-slate-300"><i class="fa-solid fa-inbox"></i></div>
              <p class="text-xs">Belum ada data katalog produk di dalam database.</p>
            </td>
          </tr>
          @endforelse

        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  document.querySelectorAll('.btn-delete-trigger').forEach(button => {
    button.addEventListener('click', function(e) {
      const form = this.closest('.form-delete-produk');

      Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Layanan cetak ini akan dihapus secara permanen dari katalog!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
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

