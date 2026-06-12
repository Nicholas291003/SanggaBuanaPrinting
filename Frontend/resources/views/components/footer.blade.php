<footer class="bg-brandblue-500 text-white pt-16 pb-8" id="footer">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 pb-12 border-b border-brandblue-400">
      
      <div class="lg:col-span-4 space-y-6">
        <div class="flex items-center gap-2.5">
          <div class="w-10 h-10 rounded-xl bg-brandred-500 flex items-center justify-center text-white font-black text-xl">SB</div>
          <div class="flex flex-col">
            <span class="font-extrabold text-lg tracking-tight leading-none">Sangga Buana</span>
            <span class="text-[10px] text-brandred-400 font-extrabold uppercase tracking-widest mt-1">Percetakan Modern</span>
          </div>
        </div>
        <p class="text-brandblue-200 text-xs leading-relaxed max-w-sm">Dapatkan katalog penawaran diskon bulanan dan info tips mempersiapkan berkas cetak langsung ke email Anda.</p>
        <form onsubmit="handleNewsletterSubmit(event)" class="flex items-center max-w-sm">
          <input type="email" id="newsletter-email" required placeholder="Ketik email Anda..." class="w-full pl-4 pr-3 py-3 text-xs rounded-l-xl bg-brandblue-600 border border-transparent focus:border-brandred-500 focus:outline-none text-white">
          <button type="submit" class="px-5 py-3 bg-brandred-500 hover:bg-brandred-600 text-white font-extrabold text-xs rounded-r-xl transition-all-300">IKUTI</button>
        </form>
      </div>

      <div class="lg:col-span-2 space-y-4">
        <h4 class="font-extrabold text-sm uppercase tracking-wider text-brandred-300">Sangga Buana</h4>
        <ul class="space-y-2.5 text-xs text-brandblue-200">
          <li><a href="#cabang" class="hover:text-brandred-300 transition-all-300">Tentang Kami</a></li>
          <li><a href="#kontak" class="hover:text-brandred-300 transition-all-300">Hubungi Kami</a></li>
          <li><a href="#portfolio" class="hover:text-brandred-300 transition-all-300">Portofolio Cetak</a></li>
        </ul>
      </div>

      <div class="lg:col-span-2 space-y-4">
        <h4 class="font-extrabold text-sm uppercase tracking-wider text-brandred-300">Layanan Inti</h4>
        <ul class="space-y-2.5 text-xs text-brandblue-200">
          <li><a href="#products-grid" class="hover:text-brandred-300 transition-all-300">Spanduk Banner</a></li>
          <li><a href="#products-grid" class="hover:text-brandred-300 transition-all-300">Cetak Brosur</a></li>
          <li><a href="#products-grid" class="hover:text-brandred-300 transition-all-300">Sablon Baju</a></li>
        </ul>
      </div>

      <div class="lg:col-span-4 bg-brandred-500 rounded-3xl p-6 relative overflow-hidden shadow-lg flex flex-col justify-between">
        <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/10 rounded-full"></div>
        <div class="space-y-3 relative z-10">
          <span class="text-[9px] uppercase font-black text-brandred-500 bg-white px-2.5 py-1 rounded">PROMO MITRA BARU</span>
          <h4 class="text-xl font-extrabold text-white">Semua Kebutuhan Sukses Promosi Bisnis Anda.</h4>
          <p class="text-xs text-white/95 leading-relaxed">Hubungi marketing kami sekarang untuk klaim voucher diskon cetak pertama sebesar 15%.</p>
        </div>
        <a href="https://wa.me/6281234567890?text=Halo%20Sangga%20Buana,%20saya%20ingin%20ambil%20voucher" target="_blank" class="mt-4 px-4 py-2.5 bg-brandblue-500 hover:bg-white hover:text-brandblue-500 text-white font-extrabold text-xs rounded-xl text-center shadow transition-all-300 uppercase tracking-wider">Klaim Voucher Diskon</a>
      </div>

    </div>

    <div class="pt-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-brandblue-200">
      <span>Hak Cipta © 2026 <a href="#" class="font-bold text-white hover:underline">Sangga Buana Percetakan</a>.</span>
      <div class="flex items-center gap-6">
        <button onclick="scrollToTop()" class="w-10 h-10 rounded-full bg-brandblue-600 border border-brandblue-400 hover:border-brandred-500 flex items-center justify-center transition-all-300"><i class="fa-solid fa-arrow-up"></i></button>
      </div>
    </div>
  </div>
</footer>