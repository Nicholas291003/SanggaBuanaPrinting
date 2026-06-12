@php
    // Ambil data pengaturan dari database
    $globalSetting = \App\Models\CompanySetting::first();
    $socialMedias  = $globalSetting->social_media ?? [];
    
    $iconMapping = [
        'instagram' => ['icon' => 'fa-brands fa-instagram', 'color' => 'hover:text-pink-600', 'bg' => 'hover:bg-pink-600'],
        'facebook'  => ['icon' => 'fa-brands fa-facebook-f', 'color' => 'hover:text-blue-600', 'bg' => 'hover:bg-blue-600'],
        'whatsapp'  => ['icon' => 'fa-brands fa-whatsapp', 'color' => 'hover:text-emerald-500', 'bg' => 'hover:bg-emerald-500'],
        'tiktok'    => ['icon' => 'fa-brands fa-tiktok', 'color' => 'hover:text-slate-900', 'bg' => 'hover:bg-slate-900'],
        'youtube'   => ['icon' => 'fa-brands fa-youtube', 'color' => 'hover:text-red-600', 'bg' => 'hover:bg-red-600'],
        'twitter'   => ['icon' => 'fa-brands fa-x-twitter', 'color' => 'hover:text-slate-800', 'bg' => 'hover:bg-slate-800'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sangga Buana - Percetakan Modern</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="//unpkg.com/alpinejs" defer></script> 
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
<body class="bg-white text-sanggablue min-h-screen flex flex-col justify-between font-sans">

  <nav class="bg-white sticky top-0 z-50 shadow-sm px-6 py-4 flex items-center justify-between max-w-7xl w-full mx-auto">
    <a href="/" class="flex items-center gap-3">
      <div class="w-12 h-12 bg-sanggared text-white rounded-xl flex items-center justify-center font-black text-xl shadow-md">SB</div>
      <div>
        <span class="block font-black text-sanggablue text-lg leading-none">Sangga Buana</span>
        <span class="block text-[10px] font-bold text-sanggared uppercase tracking-wider mt-0.5">Percetakan Modern</span>
      </div>
    </a>

    <div class="hidden md:flex items-center gap-8 text-sm font-bold">
      <a href="{{ route('public.katalog') }}" class="hover:text-sanggared transition-colors">Produk</a>
      <a href="{{ route('public.cabang') }}" class="hover:text-sanggared transition-colors">Cabang</a>
      <a href="{{ route('public.kontak') }}" class="hover:text-sanggared transition-colors">Kontak</a>
    </div>

    <div class="hidden lg:flex items-center gap-4">
      
      <div class="flex items-center gap-2 text-slate-400 border-l pl-4 border-slate-200">
        @foreach($socialMedias as $sm)
          @if(!empty($sm['url']))
              @php 
                  $platform = $sm['platform'] ?? 'link';
                  $iconClass = $iconMapping[$platform]['icon'] ?? 'fa-solid fa-link';
                  $hoverColor = $iconMapping[$platform]['color'] ?? 'hover:text-sanggared';
              @endphp
              <a href="{{ $sm['url'] }}" target="_blank" class="{{ $hoverColor }} transition-colors">
                  <i class="{{ $iconClass }}"></i>
              </a>
          @endif
        @endforeach
      </div>

      <div class="relative w-56">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
        <input type="text" placeholder="Cari layanan..." class="w-full pl-10 pr-4 py-2 text-sm rounded-full bg-slate-100 focus:outline-none focus:ring-1 focus:ring-sanggared transition-all">
      </div>
    </div>

    <div class="flex items-center gap-4">
      @guest
        <a href="/login" class="text-sm font-bold px-5 py-2 bg-slate-100 hover:bg-sanggared hover:text-white rounded-xl transition-all">Masuk</a>
        <a href="/register" class="text-sm font-bold px-5 py-2 bg-sanggablue text-white rounded-xl shadow-md">Daftar</a>
      @endguest
      @auth
        <a href="{{ route('user.order.index') }}" class="relative w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center text-sanggablue hover:bg-slate-200 transition-all shadow-sm">
          <i class="fa-solid fa-bag-shopping text-xs"></i>
          <span class="absolute -top-1.5 -right-1.5 bg-sanggared text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-bold border border-white">
            {{ auth()->user()->orders?->count() ?? 0 }}
          </span>
        </a>
        <div x-data="{ open: false }" class="relative flex items-center gap-2">
          <span class="text-sm font-bold text-sanggablue">{{ auth()->user()->name }}</span>
          <button @click="open = !open" class="w-9 h-9 rounded-full bg-sanggacream border border-sanggared/20 flex items-center justify-center font-black text-sm uppercase shadow-sm hover:ring-2 hover:ring-sanggared/30 transition-all">
            {{ substr(auth()->user()->name, 0, 1) }}
          </button>
          <div x-show="open" x-cloak x-transition
              @click.away="open = false"
              class="absolute right-0 mt-12 w-44 bg-white border border-slate-100 rounded-xl shadow-lg py-2 z-50">
            @if(auth()->user()->role == 'Administrator')
              <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm font-bold text-sanggablue hover:bg-slate-50 hover:text-sanggared">Panel Admin</a>
            @endif
            <a href="{{ route('user.profile.edit') }}" class="block px-4 py-2 text-sm font-bold text-sanggablue hover:bg-slate-50 hover:text-sanggared">Profil</a>
            <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm font-bold text-sanggablue hover:bg-slate-50 hover:text-sanggared">Keluar</a>
          </div>
        </div>
      @endauth
    </div>
  </nav>

  <main class="flex-1 bg-white">
    @if(session('success') || session('error'))
      <div class="max-w-7xl mx-auto px-6 pt-4">
        @if(session('success'))
          <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl text-xs font-bold border border-emerald-100 shadow-sm animate-pulse">
            ✅ {{ session('success') }}
          </div>
        @endif
        @if(session('error'))
          <div class="bg-red-50 text-sanggared p-4 rounded-xl text-xs font-bold border border-red-100 shadow-sm">
            ❌ {{ session('error') }}
          </div>
        @endif
      </div>
    @endif

    @yield('content')
  </main>

  <footer class="bg-sanggablue text-white pt-12 pb-6 px-6 border-t-[6px] border-sanggared">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-12 text-xs text-white/70 border-b border-white/10 pb-8 text-center md:text-left">
      
      <div class="space-y-3">
        <h5 class="font-bold text-white uppercase tracking-wider text-sm mb-4">Sangga Buana</h5>
        <a href="/" class="block hover:text-white transition-all">Tentang Kami</a>
        <a href="{{ route('public.kontak') }}" class="block hover:text-white transition-all">Hubungi Kami</a>
        <a href="#" class="block hover:text-white transition-all">Syarat & Ketentuan</a>
      </div>

      <div class="space-y-3">
        <h5 class="font-bold text-white uppercase tracking-wider text-sm mb-4">Layanan Cepat</h5>
        <a href="/#produk" class="block hover:text-white transition-all">Semua Produk Cetak</a>
        <a href="{{ route('public.cabang') }}" class="block hover:text-white transition-all">Lokasi Workshop</a>
        <a href="/#konsultasi" class="block hover:text-white transition-all">Bantuan & FAQ</a>
      </div>

      <div class="space-y-3">
        <h5 class="font-bold text-white uppercase tracking-wider text-sm mb-4">Sosial Media</h5>
        <p class="text-white/60 mb-3">Ikuti kami untuk info promo dan pembaruan layanan cetak terbaru.</p>
        <div class="flex gap-3 mt-4">
          @foreach($socialMedias as $sm)
            @if(!empty($sm['url']))
                @php 
                    $platform = $sm['platform'] ?? 'link';
                    $iconClass = $iconMapping[$platform]['icon'] ?? 'fa-solid fa-link';
                    $hoverBg = $iconMapping[$platform]['bg'] ?? 'hover:bg-sanggared';
                @endphp
                <a href="{{ $sm['url'] }}" target="_blank" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center {{ $hoverBg }} hover:text-white transition-all text-white/70">
                    <i class="{{ $iconClass }}"></i>
                </a>
            @endif
          @endforeach
        </div>
      </div>

    </div>
    
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-2 pt-6 text-[10px] text-white/40 font-medium tracking-widest uppercase">
      <p>&copy; {{ date('Y') }} Sangga Buana. Hak Cipta Dilindungi.</p>
      <p>Dibuat dengan <i class="fa-solid fa-heart text-sanggared px-1"></i> untuk Bisnis Anda.</p>
    </div>
  </footer>

</body>
</html>