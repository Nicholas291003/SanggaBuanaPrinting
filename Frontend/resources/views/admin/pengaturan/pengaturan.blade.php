@extends('layouts.admin')

@section('page_title', 'Pengaturan Akun')

@section('admin_content')
<div class="space-y-6">
  <p class="text-xs font-medium text-slate-400 -mt-6">Perbarui data profil dan konfigurasi kata sandi ruang kendali utama Anda.</p>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <div class="lg:col-span-4 bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden text-center pb-6">
      <div class="h-24 bg-[#032B22] w-full"></div>
      
      <div class="w-20 h-20 rounded-full bg-white text-sanggablue flex items-center justify-center font-black text-xl border-4 border-white shadow-md mx-auto -mt-10 uppercase">
        {{ substr(auth()->user()->name ?? 'N', 0, 1) }}
      </div>

      <div class="mt-4 px-4 space-y-1">
        <h4 class="font-black text-sanggablue text-sm uppercase tracking-wide">
          {{ auth()->user()->name ?? 'NICHOLAS ADITYA RAMADHANI' }}
        </h4>
        <span class="text-[9px] font-black tracking-widest text-amber-600 uppercase block">
          Administrator
        </span>
      </div>
    </div>

    <div class="lg:col-span-8 space-y-6">
      
      <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm space-y-5">
        <div class="flex items-center gap-2 text-sanggablue font-black text-xs uppercase tracking-wider border-b border-slate-50 pb-3">
          <i class="fa-regular fa-id-card text-sanggared text-sm"></i>
          <span>Data Pribadi</span>
        </div>

        <form action="/admin/pengaturan/profil" method="POST" class="space-y-4">
          @csrf
          @method('PUT')
          
          <div>
            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
            <input type="text" name="name" required value="{{ auth()->user()->name ?? 'NICHOLAS ADITYA RAMADHANI' }}" class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/30 text-sanggablue font-bold">
          </div>

          <div>
            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Alamat Email</label>
            <input type="email" name="email" required value="{{ auth()->user()->email ?? 'niko@gmail.com' }}" class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/30 text-sanggablue font-bold">
          </div>

          <div class="flex justify-end pt-2">
            <button type="submit" class="px-5 py-2.5 bg-[#032B22] hover:bg-[#021F18] text-white font-black text-xs rounded-xl flex items-center gap-2 shadow-sm transition-all">
              <i class="fa-solid fa-floppy-disk text-sanggacream"></i> Simpan Perubahan
            </button>
          </div>
        </form>
      </div>

      <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm space-y-5">
        <div class="flex items-center gap-2 text-sanggablue font-black text-xs uppercase tracking-wider border-b border-slate-50 pb-3">
          <i class="fa-solid fa-shield-halved text-sanggared text-sm"></i>
          <span>Keamanan Akun</span>
        </div>

        <form action="/admin/pengaturan/password" method="POST" class="space-y-4">
          @csrf
          @method('PUT')
          
          <div>
            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Password Saat Ini</label>
            <input type="password" name="current_password" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/30">
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Password Baru</label>
              <input type="password" name="password" required placeholder="Minimal 8 karakter" class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/30">
            </div>
            <div>
              <label class="block text-[9px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Konfirmasi Password Baru</label>
              <input type="password" name="password_confirmation" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/30">
            </div>
          </div>

          <div class="flex justify-end pt-2">
            <button type="submit" class="px-5 py-2.5 border-2 border-[#032B22] hover:bg-slate-50 text-[#032B22] font-black text-xs rounded-xl transition-all">
              Perbarui Password
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>
@endsection