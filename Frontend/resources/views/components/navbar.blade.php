<div class="bg-white border-b border-brandblue-100 py-2.5 px-4 sm:px-6 lg:px-8 text-xs">
  <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-2">
    <div class="flex items-center gap-4 text-brandblue-400">
      <button onclick="bukaModalUnggah()" class="hover:text-brandred-500 flex items-center gap-1.5 font-bold transition-all-300">
        <i class="fa-solid fa-cloud-arrow-up text-brandred-500 animate-bounce"></i> Unggah Desain Anda
      </button>
      <span class="text-brandblue-200">|</span>
      <a href="#quote-form" class="hover:text-brandred-500 flex items-center gap-1.5 font-bold transition-all-300">
        <i class="fa-solid fa-circle-info text-brandblue-500"></i> Minta Sampel Percetakan Gratis
      </a>
    </div>
    <div class="flex flex-wrap items-center justify-center gap-4 text-brandblue-400">
      <span class="flex items-center gap-1"><i class="fa-solid fa-phone text-brandred-500"></i> Hubungi Kami: 0812-3456-7890</span>
      <span class="hidden md:inline text-brandblue-200">|</span>
      <span class="text-brandblue-200">|</span>
      <div class="flex items-center gap-2">
        <a href="#" class="hover:text-brandred-500 transition-all-300"><i class="fa-brands fa-instagram text-sm"></i></a>
        <a href="https://wa.me/6281234567890" target="_blank" class="hover:text-green-500 transition-all-300"><i class="fa-brands fa-whatsapp text-sm"></i></a>
      </div>
    </div>
  </div>
</div>

<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md shadow-sm border-b border-brandblue-100 transition-all-300">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
    <div class="flex items-center gap-8">
      <a href="#" class="flex items-center gap-3 group">
        <div class="w-11 h-11 rounded-xl bg-brandred-500 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-brandred-200 group-hover:rotate-3 transition-all-300">SB</div>
        <div class="flex flex-col">
          <span class="font-extrabold text-xl text-brandblue-500 tracking-tight leading-none">Sangga Buana</span>
          <span class="text-[10px] text-brandred-500 font-extrabold uppercase tracking-widest mt-1">Percetakan Modern</span>
        </div>
      </a>
    </div>

    <nav class="hidden lg:flex items-center gap-8 text-sm font-extrabold text-brandblue-500">
      <a href="#products-grid" class="hover:text-brandred-500 transition-all-300 flex items-center gap-1 py-2">Produk <i class="fa-solid fa-chevron-down text-[10px]"></i></a>
      <a href="#cabang" class="hover:text-brandred-500 transition-all-300 py-2">Cabang</a>
      <a href="#kontak" class="hover:text-brandred-500 transition-all-300 py-2">Kontak</a>
      <span class="text-brandblue-200 text-lg font-light">|</span>
      <div class="flex items-center gap-4 text-base">
        <a href="#" class="hover:text-brandred-500 text-brandblue-400 transition-all-300"><i class="fa-brands fa-instagram"></i></a>
        <a href="https://wa.me/6281234567890" target="_blank" class="hover:text-brandred-500 text-brandblue-400 transition-all-300"><i class="fa-brands fa-whatsapp"></i></a>
      </div>
    </nav>

    <div class="flex items-center gap-5">
      <div class="relative hidden sm:block">
        <input type="text" placeholder="Cari layanan cetak..." class="w-48 xl:w-60 pl-9 pr-4 py-2 text-xs rounded-full bg-brandblue-50 border border-transparent focus:border-brandred-400 focus:bg-white focus:outline-none transition-all-300 text-brandblue-500 font-medium">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-brandblue-300 text-xs"></i>
      </div>
      <a href="#" class="text-brandblue-500 hover:text-brandred-500 text-lg transition-all-300"><i class="fa-regular fa-user"></i></a>
      
      <button onclick="bukaKeranjang()" class="relative flex items-center gap-2 px-4 py-2.5 bg-brandblue-500 hover:bg-brandred-500 text-white rounded-full shadow-md hover:shadow-lg transition-all-300 group">
        <i class="fa-solid fa-bag-shopping text-sm group-hover:scale-110 transition-all-300"></i>
        <span class="text-xs font-black bg-brandred-500 text-white px-2 py-0.5 rounded-full border border-white" id="cart-counter">0</span>
      </button>

      <button onclick="toggleMobileMenu()" class="lg:hidden text-brandblue-500 hover:text-brandred-500 text-xl transition-all-300"><i class="fa-solid fa-bars" id="menu-icon"></i></button>
    </div>
  </div>

  <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-brandblue-100 px-4 py-4 space-y-3 shadow-inner">
    <a href="#products-grid" class="block font-bold py-2 text-brandblue-500 hover:text-brandred-500">Produk</a>
    <a href="#cabang" class="block font-bold py-2 text-brandblue-500 hover:text-brandred-500">Cabang</a>
    <a href="#kontak" class="block font-bold py-2 text-brandblue-500 hover:text-brandred-500">Kontak</a>
  </div>
</header>