@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto px-6 py-12">
  <div class="bg-white border border-slate-100 p-8 rounded-3xl shadow-xl space-y-6">
    
    <!-- Header Title -->
    <div class="border-b border-slate-50 pb-4 flex items-center gap-3">
      <div class="w-10 h-10 rounded-full bg-sanggacream text-sanggablue font-black text-sm flex items-center justify-center border border-sanggared/20 uppercase">
        {{ substr(auth()->user()->name, 0, 1) }}
      </div>
      <div>
        <h2 class="text-base font-black text-sanggablue tracking-tight">Pengaturan Akun Profil Saya</h2>
        <p class="text-[11px] font-medium text-slate-400">Ubah detail data kontak atau lakukan perbaruan kata sandi berkala.</p>
      </div>
    </div>

    <!-- Alert Sukses Form -->
    @if(session('success'))
      <div class="bg-emerald-50 text-emerald-600 p-3.5 rounded-xl text-xs font-bold border border-emerald-100">
        {{ session('success') }}
      </div>
    @endif

    <!-- Form Aksi Update -->
    <form action="/profile-saya" method="POST" class="space-y-4">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold">
        @error('name') <span class="text-[10px] text-sanggared font-bold mt-1 block">{{ $message }}</span> @enderror
      </div>

      <div>
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Alamat Email Aktif</label>
        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold">
        @error('email') <span class="text-[10px] text-sanggared font-bold mt-1 block">{{ $message }}</span> @enderror
      </div>

      <div class="border-t border-slate-100 pt-4 space-y-4">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Ganti Password (Kosongkan jika tidak ingin diubah)</p>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Password Baru</label>
            <input type="password" name="password" placeholder="Minimal 8 karakter" class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50">
            @error('password') <span class="text-[10px] text-sanggared font-bold mt-1 block">{{ $message }}</span> @enderror
          </div>
          <div>
            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" placeholder="Ulangi kembali" class="w-full px-4 py-2.5 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50">
          </div>
        </div>
      </div>

      <div class="pt-4 flex justify-end">
        <button type="submit" class="px-5 py-2.5 bg-sanggablue hover:bg-sanggablue/90 text-white font-black text-xs rounded-xl shadow-md transition-all uppercase tracking-wider">
          Simpan Perubahan
        </button>
      </div>

    </form>
  </div>
</div>
@endsection