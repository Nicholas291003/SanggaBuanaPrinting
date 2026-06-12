@extends('layouts.admin')

@section('page_title', 'Kelola Kategori')

@section('admin_content')
<div class="space-y-6">
  <p class="text-xs font-medium text-slate-400 -mt-6">Atur daftar kategori untuk pengelompokan etalase layanan cetak Anda.</p>

  <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
    
    <div class="md:col-span-4">
      <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
        <h3 class="font-extrabold text-sanggablue text-sm border-b border-slate-100 pb-3 mb-4">Tambah Kategori Baru</h3>
        
        @if ($errors->any())
          <div class="text-xs font-bold text-sanggared bg-red-50 p-3 rounded-lg mb-4">
            {{ $errors->first() }}
          </div>
        @endif

        <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-4">
          @csrf
          <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Nama Kategori</label>
            <input type="text" name="name" required placeholder="" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold">
          </div>
          <button type="submit" class="w-full py-3 bg-[#032B22] hover:bg-[#021F18] text-white font-black text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus"></i> Simpan Kategori
          </button>
        </form>
      </div>
    </div>

    <div class="md:col-span-8">
      <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead>
              <tr class="bg-slate-50 text-slate-400 uppercase font-black tracking-wider text-[10px] border-b border-slate-100">
                <th class="p-4 w-16 text-center">No</th>
                <th class="p-4">Nama Kategori</th>
                <th class="p-4 text-center w-24">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-600 font-semibold">
              @forelse($categories as $index => $kategori)
              <tr class="hover:bg-slate-50/50 transition-all">
                <td class="p-4 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                <td class="p-4 font-black text-sanggablue text-sm">{{ $kategori->name }}</td>
                <td class="p-4 text-center">
                  <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" class="inline form-delete-kategori">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-sanggared hover:text-white text-slate-400 inline-flex items-center justify-center transition-all btn-delete-kategori">
                      <i class="fa-regular fa-trash-can text-xs"></i>
                    </button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="3" class="p-16 text-center text-slate-400 font-medium">
                  <i class="fa-solid fa-layer-group text-3xl text-slate-200 mb-3 block"></i>
                  <p class="text-xs italic">Belum ada data kategori yang ditambahkan.</p>
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
  document.querySelectorAll('.btn-delete-kategori').forEach(button => {
    button.addEventListener('click', function(e) {
      const form = this.closest('.form-delete-kategori');
      Swal.fire({
        title: 'Hapus Kategori?',
        text: "Kategori ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
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