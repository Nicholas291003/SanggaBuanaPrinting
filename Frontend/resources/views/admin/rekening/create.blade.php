@extends('layouts.admin')

@section('page_title', 'Tambah Metode Pembayaran Baru')

@section('admin_content')
<div class="space-y-6">
  <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 -mt-6 flex items-center gap-2">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-sanggared transition-all">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[9px]"></i>
    <a href="{{ route('admin.rekening.index') }}" class="hover:text-sanggared transition-all">Rekening & Pembayaran</a>
    <i class="fa-solid fa-chevron-right text-[9px]"></i>
    <span class="text-sanggablue">Tambah Baru</span>
  </div>

  @if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-sanggared p-4 rounded-xl mb-6 shadow-sm">
      <div class="flex items-center gap-2 font-black text-xs mb-2 uppercase tracking-wider">
        <i class="fa-solid fa-triangle-exclamation"></i> Gagal Menyimpan Data!
      </div>
      <ul class="list-disc list-inside text-xs font-bold space-y-1">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="max-w-2xl bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
    <form action="{{ route('admin.rekening.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
      @csrf

      <div class="grid grid-cols-2 gap-5">
        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Nama Bank / QRIS <span class="text-sanggared">*</span></label>
          <input type="text" name="name" required placeholder="Cth: BCA, QRIS Toko" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold">
        </div>
        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Kategori <span class="text-sanggared">*</span></label>
          <select name="category" required class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold cursor-pointer" onchange="toggleQR(this)">
            <option value="Bank Transfer">Bank Transfer</option>
            <option value="QRIS">QRIS</option>
            <option value="E-Wallet">E-Wallet (OVO/Dana)</option>
            <option value="Retail">Retail (Alfamart)</option>
          </select>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-5 field-non-qris">
        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">No Rekening / HP</label>
          <input type="text" name="account_number" placeholder="Cth: 123456789" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold">
        </div>
        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Atas Nama</label>
          <input type="text" name="account_name" placeholder="Cth: PT Sangga Buana" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold">
        </div>
      </div>

      <div id="field-qris" class="hidden bg-slate-50 border border-slate-100 rounded-2xl p-5 space-y-4">
        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Upload Barcode QRIS</label>
          <input type="file" name="qr_image" accept="image/*" class="w-full p-2 text-xs rounded-xl border border-slate-200 bg-white outline-none cursor-pointer">
          <p class="text-[9px] text-slate-400 mt-1.5 italic">*Hanya wajib diisi jika memilih kategori QRIS.</p>
        </div>
      </div>

      <div>
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Status Operasional <span class="text-sanggared">*</span></label>
        <select name="status" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold cursor-pointer">
          <option value="Aktif">🟢 Aktif Tersedia</option>
          <option value="Nonaktif">🔴 Nonaktif Sementara</option>
        </select>
      </div>

      <div class="flex gap-3 pt-4 border-t border-slate-100">
        <a href="{{ route('admin.rekening.index') }}" class="w-1/3 py-3 border border-slate-200 text-sanggablue font-black text-xs text-center rounded-xl hover:bg-slate-50 transition-all">Batal</a>
        <button type="submit" class="w-2/3 py-3 bg-[#032B22] text-white font-black text-xs rounded-xl shadow-md hover:bg-[#021F18] transition-all">Simpan Rekening</button>
      </div>
    </form>
  </div>
</div>

<script>
  function toggleQR(select) {
    const qrisField = document.getElementById('field-qris');
    if(select.value === 'QRIS') {
      qrisField.classList.remove('hidden');
    } else {
      qrisField.classList.add('hidden');
    }
  }
</script>
@endsection