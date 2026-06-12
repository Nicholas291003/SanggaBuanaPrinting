<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SanggaAdmin - Ruang Kendali</title>
  
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
          colors: {
            sanggared: '#C1121F',    // Merah Utama (Sidebar)
            sanggablue: '#003049',   // Deep Navy (Teks Utama & Header)
            sanggacream: '#FDF0D5',  // Cream Lembut (Aksen Latar)
            sanggalight: '#669BBC'   // Biru Muda (Aksen Status)
          }
        }
      }
    }
  </script>
</head>
<body class="bg-slate-50 text-sanggablue font-sans overflow-hidden h-screen flex">

  <aside id="sidebar" class="w-64 bg-sanggared text-white flex flex-col justify-between shrink-0 shadow-2xl z-20 transition-all duration-300">
    <div>
      <div class="p-7 flex flex-col items-center border-b border-white/10">
        <span class="text-2xl font-black tracking-tight block">Sangga<span class="text-sanggacream">Buana.</span></span>
        <span class="text-[9px] tracking-[0.25em] font-bold text-white/60 uppercase mt-1">RUANG KENDALI</span>
      </div>
      <div class="space-y-2 px-4 mt-6">
  
        <a href="{{ route('admin.dashboard') }}" 
          class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-200
          {{ request()->routeIs('admin.dashboard') ? 'bg-white text-red-700 shadow-sm font-extrabold' : 'text-white/90 hover:bg-white/10' }}">
          <i class="fa-solid fa-chart-pie text-sm w-5 text-center"></i>
          <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.kategori.index') }}" 
          class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-200 {{ request()->routeIs('admin.kategori.*') ? 'bg-white text-red-700 shadow-sm font-extrabold' : 'text-white/90 hover:bg-white/10' }}">
          <i class="fa-solid fa-tags text-sm w-5 text-center"></i>
          <span>Kategori Produk</span>
        </a>

        <a href="{{ route('admin.produk.index') }}" 
          class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-200
          {{ request()->routeIs('admin.produk.*') ? 'bg-white text-red-700 shadow-sm font-extrabold' : 'text-white/90 hover:bg-white/10' }}">
          <i class="fa-solid fa-box text-sm w-5 text-center"></i>
          <span>Kelola Produk</span>
        </a>

        <a href="{{ route('admin.pesanan.index') }}" 
          class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-200
          {{ request()->routeIs('admin.pesanan.*') ? 'bg-white text-red-700 shadow-sm font-extrabold' : 'text-white/90 hover:bg-white/10' }}">
          <i class="fa-solid fa-receipt text-sm w-5 text-center"></i>
          <span>Kelola Pesanan</span>
        </a>

        <a href="{{ route('admin.arsip.index') }}" 
          class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-200
          {{ request()->routeIs('admin.arsip.*') ? 'bg-white text-red-700 shadow-sm font-extrabold' : 'text-white/90 hover:bg-white/10' }}">
          <i class="fa-solid fa-folder-open text-sm w-5 text-center"></i>
          <span>Arsip Desain</span>
        </a>

        <a href="{{ route('admin.pelanggan.index') }}" 
          class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-200
          {{ request()->routeIs('admin.pelanggan.*') ? 'bg-white text-red-700 shadow-sm font-extrabold' : 'text-white/90 hover:bg-white/10' }}">
          <i class="fa-solid fa-users text-sm w-5 text-center"></i>
          <span>Data Pelanggan</span>
        </a>

        <a href="{{ route('admin.rekening.index') }}" 
          class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-200
          {{ request()->routeIs('admin.rekening.*') ? 'bg-white text-red-700 shadow-sm font-extrabold' : 'text-white/90 hover:bg-white/10' }}">
          <i class="fa-solid fa-money-check-dollar text-sm w-5 text-center"></i>
          <span>Keuangan</span>
        </a>

        <a href="{{ route('admin.cabang.index') }}" 
          class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-200
          {{ request()->routeIs('admin.cabang.*') ? 'bg-white text-red-700 shadow-sm font-extrabold' : 'text-white/90 hover:bg-white/10' }}">
          <i class="fa-solid fa-shop text-sm w-5 text-center"></i>
          <span>Manajemen Cabang</span>
        </a>

        <a href="{{ route('admin.konten.index') }}"
          class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-200
          {{ request()->routeIs('admin.konten.*') ? 'bg-white text-red-700 shadow-sm font-extrabold' : 'text-white/90 hover:bg-white/10' }}">
          <i class="fa-solid fa-newspaper text-sm w-5 text-center"></i>
          <span>Kelola Konten</span> 
        </a>

        <a href="{{ route('admin.log.index') }}" 
          class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-200
          {{ request()->routeIs('admin.log.*') ? 'bg-white text-red-700 shadow-sm font-extrabold' : 'text-white/90 hover:bg-white/10' }}">
          <i class="fa-solid fa-list text-sm w-5 text-center"></i>
          <span>Log Aktivitas</span>
        </a>

        <a href="{{ route('admin.pengaturan.index') }}" 
          class="flex items-center gap-3 px-4 py-3 text-xs font-black uppercase tracking-wider rounded-xl transition-all duration-200
          {{ request()->routeIs('admin.pengaturan.*') ? 'bg-white text-red-700 shadow-sm font-extrabold' : 'text-white/90 hover:bg-white/10' }}">
          <i class="fa-solid fa-gear text-sm w-5 text-center"></i>
          <span>Pengaturan Akun</span>
        </a>

      </div>
    </div>

    <div class="p-4 border-t border-white/10">
      <a href="{{ route('logout') }}" class="flex items-center gap-3.5 px-4 py-3.5 rounded-xl text-sanggacream hover:bg-white/10 font-bold text-sm transition-all">
        <i class="fa-solid fa-right-from-bracket w-5 text-center text-lg"></i> Keluar
      </a>
    </div>
  </aside>

  <div class="flex-1 flex flex-col overflow-hidden">
    
    <header class="h-20 bg-white border-b border-slate-100 flex items-center justify-between px-8 shrink-0 z-10">
      
      <div class="flex items-center gap-4">
        <button onclick="toggleSidebar()" class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 text-sanggablue hover:bg-slate-100 flex items-center justify-center transition-all focus:outline-none shadow-sm">
          <i class="fa-solid fa-bars text-sm"></i>
        </button>
        <h2 class="text-2xl font-extrabold text-sanggablue">@yield('page_title', 'Dashboard')</h2>
      </div>

      <div class="flex items-center gap-3.5">
        <div class="text-right">
          <span class="block text-xs font-black text-sanggablue tracking-wide uppercase">
            {{ auth()->user()->name ?? 'Administrator' }}
          </span>
          
          <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">
            {{ auth()->user()->role ?? 'Admin' }}
          </span>
        </div>
        
        <div class="w-10 h-10 rounded-full bg-sanggablue text-white flex items-center justify-center font-extrabold text-sm shadow-md uppercase">
          {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
        </div>
      </div>
    </header>

    <main class="flex-1 overflow-y-auto p-8 bg-slate-50/50">
      @yield('admin_content')
    </main>
  </div>

  @if(session('success'))
    <div id="toast-success" class="fixed bottom-6 left-6 bg-sanggablue text-white px-5 py-3.5 rounded-xl shadow-2xl flex items-center gap-3 border-l-4 border-sanggared transition-all z-50">
      <i class="fa-solid fa-circle-check text-sanggared text-base"></i>
      <span class="text-xs font-bold tracking-wide">{{ session('success') }}</span>
    </div>
    <script>
      setTimeout(() => { document.getElementById('toast-success').remove(); }, 4000);
    </script>
  @endif

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      sidebar.classList.toggle('-ml-64');
    }
  </script>

</body>
</html>