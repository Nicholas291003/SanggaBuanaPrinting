<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sangga Buana - Pendaftaran Akun</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
          colors: { sanggared: '#C1121F', sanggablue: '#003049', sanggacream: '#FDF0D5' }
        }
      }
    }
  </script>
</head>
<body class="bg-slate-50 font-sans min-h-screen flex items-center justify-center p-4">

  <div class="bg-white border border-slate-100 p-8 rounded-3xl shadow-xl max-w-sm w-full space-y-6">
    <div class="text-center space-y-2">
      <div class="w-12 h-12 bg-sanggared text-white rounded-2xl flex items-center justify-center font-black text-xl shadow-md mx-auto">SB</div>
      <h2 class="text-xl font-black text-sanggablue tracking-tight">Buat Akun Kemitraan</h2>
      <p class="text-xs font-medium text-slate-400">Daftarkan diri untuk mulai menikmati fitur cetak presisi.</p>
    </div>

    @if($errors->any())
      <div class="bg-red-50 text-sanggared p-3.5 rounded-xl text-[11px] font-bold border border-red-100">
        {{ $errors->first() }}
      </div>
    @endif

    <form action="/register" method="POST" class="space-y-4">
      @csrf
      <div>
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Ahmad Wahyudi" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 font-bold text-sanggablue">
      </div>

      <div>
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Alamat Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 font-bold text-sanggablue">
      </div>

      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Password</label>
          <input type="password" name="password" required placeholder="Min 8 Karakter" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold">
        </div>
        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Konfirmasi</label>
          <input type="password" name="password_confirmation" required placeholder="Ulangi" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold">
        </div>
      </div>

      <button type="submit" class="w-full py-3 bg-sanggared hover:bg-sanggared/90 text-white font-black text-xs rounded-xl shadow-md transition-all uppercase tracking-wider">
        Daftar Mitra Baru
      </button>
    </form>

    <div class="text-center text-[11px] font-bold text-slate-400 border-t border-slate-100 pt-4">
      Sudah memiliki akun? <a href="/login" class="text-sanggablue hover:underline">Masuk di sini</a>
    </div>
  </div>

</body>
</html>