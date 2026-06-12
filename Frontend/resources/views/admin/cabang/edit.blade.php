@extends('layouts.admin')

@section('page_title', 'Ubah Informasi Cabang')

@section('admin_content')
<div class="space-y-6">
  <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 -mt-6 flex items-center gap-2">
    <a href="/admin" class="hover:text-sanggared transition-all">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[9px]"></i>
    <a href="/admin/cabang" class="hover:text-sanggared transition-all">Manajemen Cabang</a>
    <i class="fa-solid fa-chevron-right text-[9px]"></i>
    <span class="text-sanggablue">Ubah Cabang</span>
  </div>

  <div class="max-w-3xl bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
    <form action="{{ route('admin.cabang.update', $branch->id) }}" method="POST" class="space-y-5">
      @csrf
      @method('PUT')

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Nama Cabang/Outlet <span class="text-sanggared">*</span></label>
          <input type="text" name="name" value="{{ $branch->name }}" required class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-semibold">
        </div>
        
        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">No. Telepon / WhatsApp <span class="text-sanggared">*</span></label>
          <input type="text" name="phone" value="{{ $branch->phone }}" required class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold">
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Jam Buka</label>
          <input type="time" name="opening_time" value="{{ $branch->opening_time ? date('H:i', strtotime($branch->opening_time)) : '' }}" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold">
        </div>
        
        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Jam Tutup</label>
          <input type="time" name="closing_time" value="{{ $branch->closing_time ? date('H:i', strtotime($branch->closing_time)) : '' }}" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold">
        </div>
      </div>

      <div>
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Keterangan / Deskripsi Cabang</label>
        <input type="text" name="description" value="{{ $branch->description }}" placeholder="Contoh: Pusat produksi utama" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-semibold">
      </div>

      <div>
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Alamat Lengkap <span class="text-sanggared">*</span></label>
        <textarea name="address" rows="3" required class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-semibold resize-none leading-relaxed">{{ $branch->address }}</textarea>
      </div>

      <div>
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Status Operasional Cabang</label>
        <select name="status" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold cursor-pointer">
          <option value="Buka" {{ $branch->status == 'Buka' ? 'selected' : '' }}>🟢 Aktif Melayani (Buka)</option>
          <option value="Tutup" {{ $branch->status == 'Tutup' ? 'selected' : '' }}>🔴 Nonaktif Sementara (Tutup)</option>
          <option value="Akan Hadir" {{ $branch->status == 'Akan Hadir' ? 'selected' : ''}}>⚫ Akan Segera Hdir (Akan Hadir)</option>
        </select>
      </div>

      <div class="flex justify-end gap-3 pt-3 border-t border-slate-100">
        <a href="{{ route('admin.cabang.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-sanggablue font-black text-xs rounded-xl transition-all">
          Batalkan
        </a>
        <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs rounded-xl shadow-md transition-all flex items-center gap-2">
          <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan Cabang
        </button>
      </div>
    </form>
  </div>
</div>
@endsection