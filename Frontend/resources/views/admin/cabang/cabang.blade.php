@extends('layouts.admin')

@section('page_title', 'Manajemen Cabang')

@section('admin_content')
<div class="space-y-6">
  <p class="text-xs font-medium text-slate-400 -mt-6">Atur informasi unit bisnis Anda.</p>

  <div class="flex justify-end">
    <button onclick="bukaModalCabang()" class="px-5 py-2.5 bg-[#032B22] hover:bg-[#021F18] text-white font-bold text-xs rounded-xl flex items-center gap-2 shadow-md transition-all cursor-pointer">
      <i class="fa-solid fa-plus text-sanggacream"></i> Tambah Cabang Baru
    </button>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white border border-slate-100 p-5 rounded-2xl shadow-sm text-center space-y-1">
      <span class="text-[9px] text-slate-400 font-black uppercase tracking-wider block">Total Cabang Registered</span>
      <span class="text-2xl font-black text-sanggablue block">{{ $branches->count() }}</span>
    </div>

    <div class="bg-[#D97706] text-white p-5 rounded-2xl shadow-sm text-center space-y-1">
      <span class="text-[9px] text-white/70 font-black uppercase tracking-wider block">Cabang Aktif Melayani</span>
      <span class="text-2xl font-black block">
        {{ $branches->where('status', 'Buka')->count() }}
      </span>
    </div>

    <div class="bg-[#032B22] text-white p-5 rounded-2xl shadow-sm text-center space-y-1 flex flex-col justify-center">
      <span class="text-[9px] text-white/60 font-black uppercase tracking-wider block">Status Operasional Sistem</span>
      <span class="text-sm font-extrabold text-sanggacream tracking-wide block mt-0.5">Berjalan Normal</span>
    </div>
  </div>

  <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="bg-slate-50 text-slate-400 uppercase font-black tracking-wider text-[10px] border-b border-slate-100">
            <th class="p-4 w-1/4">Nama Cabang & Kontak</th>
            <th class="p-4 w-1/3">Alamat Lokasi</th>
            <th class="p-4">Jam Operasional</th>
            <th class="p-4">Status</th>
            <th class="p-4 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-slate-600 font-semibold">

          @forelse($branches as $branch)
          <tr class="hover:bg-slate-50/50 transition-all">
            <td class="p-4">
              <span class="block font-black text-sanggablue text-sm capitalize tracking-tight">{{ $branch->name }}</span>
              <span class="block text-[10px] text-slate-400 font-bold mt-1">
                <i class="fa-brands fa-whatsapp text-emerald-500 mr-1"></i>+{{ $branch->phone }}
              </span>
            </td>
            
            <td class="p-4 text-slate-500 font-medium max-w-xs leading-relaxed">
              {{ $branch->address }}
            </td>

            <td class="p-4 text-xs font-bold text-sanggablue">
              @if($branch->opening_time && $branch->closing_time)
                <i class="fa-regular fa-clock text-slate-400 mr-1"></i> 
                {{ date('H:i', strtotime($branch->opening_time)) }} - {{ date('H:i', strtotime($branch->closing_time)) }} WIB
              @else
                <span class="text-slate-400 font-normal italic">Belum diatur</span>
              @endif
            </td>
            
            <td class="p-4">
              <span class="px-2.5 py-1 rounded text-[9px] font-black tracking-wider uppercase
                {{ $branch->status == 'Buka' ? 'bg-emerald-50 text-emerald-600' : '' }}
                {{ $branch->status == 'Tutup' ? 'bg-red-50 text-sanggared' : '' }}
                {{ $branch->status == 'Akan Hadir' ? 'bg-amber-50 text-amber-600' : '' }}">
                {{ $branch->status }}
              </span>
            </td>
            
           <td class="p-4 text-center space-x-1.5 whitespace-nowrap">
              <a href="{{ route('admin.cabang.edit', $branch->id) }}" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-sanggablue hover:text-white text-slate-400 inline-flex items-center justify-center transition-all">
                <i class="fa-regular fa-pen-to-square text-xs"></i>
              </a>

              <form action="{{ route('admin.cabang.destroy', $branch->id) }}" method="POST" class="inline form-delete-cabang">
                @csrf
                @method('DELETE')
                <button type="button" class="w-8 h-8 rounded-lg border border-slate-200 hover:bg-sanggared hover:text-white text-slate-400 inline-flex items-center justify-center transition-all btn-delete-cabang-trigger">
                  <i class="fa-regular fa-trash-can text-xs"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="p-16 text-center text-slate-400 font-medium">
              <div class="text-2xl mb-2 text-slate-300"><i class="fa-solid fa-shop-slash"></i></div>
              <p class="text-xs italic text-slate-400">Belum ada data cabang resmi terdaftar.</p>
            </td>
          </tr>
          @endforelse

        </tbody>
      </table>
    </div>
  </div>
</div>

<div id="modal-cabang" class="fixed inset-0 z-50 hidden bg-sanggablue/40 backdrop-blur-sm flex items-center justify-center p-4">
  <div class="bg-white rounded-2xl max-w-2xl w-full p-6 shadow-2xl relative space-y-6">
    
    <div class="flex justify-between items-center border-b border-slate-100 pb-3">
      <h3 class="text-sm font-black text-sanggablue uppercase tracking-wider">Registrasi Kantor Cabang Baru</h3>
      <button onclick="tutupModalCabang()" class="text-slate-400 hover:text-sanggared transition-all text-sm cursor-pointer">
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>

    <form action="{{ route('admin.cabang.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
      @csrf
      
      <div class="space-y-4">
        <div>
          <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Nama Cabang / Outlet <span class="text-sanggared">*</span></label>
          <input type="text" name="name" required placeholder="Contoh: Sangga Buana Rungkut" class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-semibold">
        </div>
        <div>
          <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Keterangan Singkat</label>
          <input type="text" name="description" placeholder="Pusat produksi, Showroom cetak, dll" class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-semibold">
        </div>
        <div>
          <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Alamat Lokasi Fisik <span class="text-sanggared">*</span></label>
          <textarea name="address" rows="4" required placeholder="Tulis jalan, nomor, kecamatan dan kota..." class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 resize-none font-semibold leading-relaxed"></textarea>
        </div>
      </div>

      <div class="space-y-4">
        <div class="grid grid-cols-2 gap-3">
          <div>
            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Jam Buka</label>
            <input type="time" name="opening_time" class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold">
          </div>
          <div>
            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Jam Tutup</label>
            <input type="time" name="closing_time" class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold">
          </div>
        </div>
        <div>
          <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5">No. Telepon / WhatsApp <span class="text-sanggared">*</span></label>
          <input type="text" name="phone" placeholder="Contoh: 62812345678" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold">
        </div>
        <div>
          <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Status Operasional <span class="text-sanggared">*</span></label>
          <select name="status" required class="w-full p-2.5 text-xs font-bold text-sanggablue rounded-xl border border-slate-200 bg-slate-50/50 outline-none cursor-pointer">
            <option value="" disabled selected>-- Pilih Status --</option>
            <option value="Buka">🟢 Buka</option> 
            <option value="Tutup">🔴 Tutup</option>
            <option value="Akan Hadir">⚫ Akan Hadir</option>
          </select>
        </div>

        <div class="flex gap-3 pt-4 justify-end border-t border-slate-100">
          <button type="button" onclick="tutupModalCabang()" class="px-5 py-2.5 border border-slate-200 text-sanggablue font-black text-xs rounded-xl hover:bg-slate-50 transition-all cursor-pointer">Batal</button>
          <button type="submit" class="px-5 py-2.5 bg-[#032B22] hover:bg-[#021F18] text-white font-black text-xs rounded-xl shadow transition-all cursor-pointer">Simpan Data</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  function bukaModalCabang() {
    document.getElementById('modal-cabang').classList.remove('hidden');
  }
  function tutupModalCabang() {
    document.getElementById('modal-cabang').classList.add('hidden');
  }
  document.querySelectorAll('.btn-delete-cabang-trigger').forEach(button => {
    button.addEventListener('click', function(e) {
      const form = this.closest('.form-delete-cabang');

      Swal.fire({
        title: 'Hapus Kantor Cabang?',
        text: "Data operasional dan alamat cabang ini akan dihapus permanen dari sistem!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus Cabang',
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