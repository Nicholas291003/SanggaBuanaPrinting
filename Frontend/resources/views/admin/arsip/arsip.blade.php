@extends('layouts.admin')

@section('page_title', 'Arsip Desain Pelanggan')

@section('admin_content')
<div class="space-y-6">
  <p class="text-xs font-medium text-slate-400 -mt-6">Kelola file desain yang diunggah pelanggan.</p>

  <form method="GET" action="{{ route('admin.arsip.index') }}" class="flex flex-wrap gap-3 mb-6 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
    <div class="flex items-center gap-2 mr-2">
      <i class="fa-solid fa-filter text-slate-300"></i>
      <span class="text-[10px] font-black uppercase text-slate-400 tracking-wider">Filter:</span>
    </div>

    <select name="sort_date" onchange="this.form.submit()" class="px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold text-sanggablue outline-none cursor-pointer">
      <option value="terbaru" {{ request('sort_date') == 'terbaru' ? 'selected' : '' }}>Waktu: Terbaru</option>
      <option value="terlama" {{ request('sort_date') == 'terlama' ? 'selected' : '' }}>Waktu: Terlama</option>
    </select>
    
    <select name="type" onchange="this.form.submit()" class="px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold text-sanggablue outline-none cursor-pointer">
      <option value="">Semua Jenis File</option>
      <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Gambar (JPG/PNG)</option>
      <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Dokumen Mentah (PDF/CDR)</option>
      <option value="archive" {{ request('type') == 'archive' ? 'selected' : '' }}>Arsip (ZIP/RAR)</option>
    </select>

    <select name="sort_size" onchange="this.form.submit()" class="px-3 py-2 text-xs rounded-xl border border-slate-200 bg-slate-50 font-bold text-sanggablue outline-none cursor-pointer">
      <option value="">Ukuran: Standar</option>
      <option value="terbesar" {{ request('sort_size') == 'terbesar' ? 'selected' : '' }}>Ukuran: Terbesar</option>
      <option value="terkecil" {{ request('sort_size') == 'terkecil' ? 'selected' : '' }}>Ukuran: Terkecil</option>
    </select>
  </form>

  @php
    function formatBytes($bytes) { 
        if($bytes == 0) return '0 B';
        $units = ['B', 'KB', 'MB', 'GB']; 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow]; 
    }
  @endphp

  @forelse($groupedArchives as $tanggal => $archives)
    <div class="mb-8">
      <div class="flex items-center gap-3 mb-3">
        <h3 class="text-sm font-black text-sanggablue uppercase tracking-wider">{{ $tanggal }}</h3>
        <div class="h-px bg-slate-200 flex-grow"></div>
        <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">{{ $archives->count() }} File</span>
      </div>

      <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-xs">
          <tbody class="divide-y divide-slate-100 text-slate-600 font-semibold">
            @foreach($archives as $arsip)
            <tr class="hover:bg-slate-50/50 transition-all">
              
              <td class="p-4 w-1/3">
                <span class="block font-black text-sanggablue text-sm">{{ $arsip->user->name ?? 'Pelanggan (Akun Terhapus)' }}</span>
                <span class="block text-[10px] text-slate-400 font-bold mt-0.5"><i class="fa-regular fa-clock"></i> {{ $arsip->created_at->format('H:i') }} WIB</span>
              </td>
              
              <td class="p-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center text-lg shadow-inner">
                    @if(in_array($arsip->file_ext, ['jpg', 'jpeg', 'png'])) <i class="fa-regular fa-image text-emerald-500"></i>
                    @elseif(in_array($arsip->file_ext, ['pdf'])) <i class="fa-regular fa-file-pdf text-sanggared"></i>
                    @elseif(in_array($arsip->file_ext, ['zip', 'rar'])) <i class="fa-regular fa-file-zipper text-amber-500"></i>
                    @else <i class="fa-regular fa-file-lines"></i> @endif
                  </div>
                  <div>
                    <span class="block font-bold text-sanggablue uppercase text-[11px]">{{ $arsip->file_ext }} FORMAT</span>
                    <span class="block text-[10px] text-sanggared font-black mt-0.5">{{ formatBytes($arsip->file_size) }}</span>
                  </div>
                </div>
              </td>
              
              <td class="p-4 text-[11px] text-slate-500 font-medium italic max-w-xs truncate" title="{{ $arsip->notes }}">
                "{{ $arsip->notes ?? 'Tidak ada catatan' }}"
              </td>
              
              <td class="p-4 text-right space-x-2 whitespace-nowrap">
                <a href="{{ asset('storage/' . $arsip->file_path) }}" download class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white inline-flex items-center justify-center transition-all shadow-sm" title="Unduh File">
                  <i class="fa-solid fa-download text-xs"></i>
                </a>
                
                <form action="{{ route('admin.arsip.destroy', $arsip->id) }}" method="POST" class="inline form-delete-arsip">
                  @csrf @method('DELETE')
                  <button type="button" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-600 text-red-600 hover:text-white inline-flex items-center justify-center transition-all shadow-sm btn-delete-arsip" title="Hapus File dari Server">
                    <i class="fa-solid fa-trash-can text-xs"></i>
                  </button>
                </form>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @empty
    <div class="bg-white border border-slate-100 p-12 rounded-2xl shadow-sm text-center text-slate-400 font-medium">
      <div class="text-3xl mb-3 text-slate-200"><i class="fa-solid fa-folder-open"></i></div>
      <p class="text-xs">Belum ada file arsip yang ditemukan berdasarkan filter Anda.</p>
    </div>
  @endforelse
</div>

<script>
  document.querySelectorAll('.btn-delete-arsip').forEach(button => {
    button.addEventListener('click', function(e) {
      const form = this.closest('.form-delete-arsip');

      Swal.fire({
        title: 'Hapus File dari Server?',
        text: "File asli akan dihapus permanen untuk menghemat ruang penyimpanan. Pastikan Anda sudah mengunduhnya jika file ini masih diproses.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Bersihkan File',
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