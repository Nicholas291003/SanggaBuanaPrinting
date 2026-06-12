<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sangga Buana - Masuk Akun</title>
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
      <h2 class="text-xl font-black text-sanggablue tracking-tight">Masuk ke Akun Anda</h2>
      <p class="text-xs font-medium text-slate-400">Silakan masukkan email dan kata sandi terdaftar.</p>
    </div>

    @if($errors->any())
      <div class="bg-red-50 text-sanggared p-3.5 rounded-xl text-[11px] font-bold border border-red-100">
        {{ $errors->first() }}
      </div>
    @endif

    <form action="/login" method="POST" class="space-y-4">
      @csrf
      <div>
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Alamat Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 font-bold text-sanggablue">
      </div>

      <div>
        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Password</label>
        <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold">
      </div>

      <div class="flex items-center justify-between text-[11px] font-bold">
        <label class="flex items-center gap-2 text-slate-400 cursor-pointer">
          <input type="checkbox" name="remember" class="accent-sanggared rounded"> Ingat Saya
        </label>
        <a href="#" class="text-sanggared hover:underline">Lupa Password?</a>
      </div>

      <button type="submit" class="w-full py-3 bg-sanggablue hover:bg-sanggablue/90 text-white font-black text-xs rounded-xl shadow-md transition-all uppercase tracking-wider">
        Masuk Aplikasi
      </button>
    </form>

    <div class="text-center text-[11px] font-bold text-slate-400 border-t border-slate-100 pt-4">
      Belum punya akun? <a href="/register" class="text-sanggared hover:underline">Daftar Sekarang</a>
    </div>
  </div>

</body>
</html>