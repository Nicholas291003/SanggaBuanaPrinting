@extends('layouts.app')

@section('content')
<div class="w-full bg-white text-sanggablue font-sans overflow-x-hidden">

  <!-- ================= HERO SECTION ================= -->
  <section class="w-full bg-white px-4 py-5 md:py-4">
    <div class="max-w-6xl mx-auto space-y-8 text-center">
      <h1 class="text-3xl md:text-4xl font-black text-sanggablue leading-tight">
        Mewujudkan Karya Cetak <span class="text-sanggared">Presisi Tinggi</span> Untuk Bisnis Anda
      </h1>
      <div class="flex flex-wrap justify-center gap-10">
        <a href="/pesanan-baru" class="px-6 py-3 bg-sanggared hover:bg-sanggared/90 text-white font-bold text-sm rounded-xl shadow-md inline-flex items-center gap-2">
          <i class="fa-solid fa-print"></i> Mulai Cetak Sekarang
        </a>
        <a href="{{ route('public.katalog') }}" class="px-6 py-3 border border-slate-200 hover:bg-slate-50 text-sanggablue font-bold text-sm rounded-xl inline-flex items-center">
          Produk
        </a>
      </div>
    </div>
  </section>
  <section class="bg-slate-50/40 border-t border-slate-100 py-4 md:py-4">
    <div class="max-w-6xl mx-auto px-4 space-y-6 text-center">
      <span class="text-lg font-black text-sanggared uppercase tracking-widest">
        Tentang Kami
      </span>
      <div class="pt-4 max-w-sm mx-auto">
        <a href="{{ route('public.cabang') }}" class="block p-3 bg-white border border-slate-100 rounded-xl shadow-sm text-center space-y-1 hover:shadow-md transition-shadow">
          <i class="fa-solid fa-warehouse text-sanggared text-xl block"></i>
          <span class="block text-sm font-black text-sanggablue">Workshop Sangga</span>
          <p class="text-xs text-slate-700">Kunjungi cabang kami yang lain nya.</p>
        </a>
      </div>
    </div>
  </section>

  <!-- ================= KATEGORI PRODUK ================= -->
  <section id="katalog" class="max-w-6xl mx-auto px-4 py-6 space-y-6">
    <div class="text-center space-y-3">
      <span class="text-lg font-black text-sanggared uppercase tracking-widest">
        Produk
      </span>
      <h3 class="text-3xl md:text-4xl font-black text-sanggablue tracking-tight">
        Solusi Cetak Bisnis Terlengkap
      </h3>
      <p class="text-sm text-slate-700 max-w-md mx-auto">Pilih kategori layanan cetak berkualitas tinggi yang disesuaikan khusus untuk Anda.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
      @forelse($products as $product)
        <a href="{{ route('public.produk.detail', $product->id) }}" class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm space-y-5 flex flex-col justify-between group hover:shadow-lg hover:border-sanggared/30 transition-all cursor-pointer">
          <div class="space-y-4">
            <div class="w-full h-44 bg-slate-50 border border-slate-50 rounded-xl flex items-center justify-center text-slate-300 text-3xl overflow-hidden relative">
              @if($product->image_path)
                <img src="{{ asset('storage/' . $product->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
              @else
                <i class="fa-regular fa-image text-slate-300 group-hover:scale-105 transition-transform duration-500"></i>
              @endif
            </div>
            <div class="space-y-2">
              <span class="text-[10px] font-black text-slate-400 uppercase block tracking-wider">{{ $product->category }}</span>
              <h4 class="font-black text-sanggablue text-base capitalize truncate" title="{{ $product->name }}">{{ $product->name }}</h4>
              <p class="text-sm text-slate-500 font-medium line-clamp-2 leading-relaxed">{{ $product->description }}</p>
            </div>
          </div>
          
          <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
            <div>
              <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Mulai Dari</span>
              <span class="block font-black text-emerald-600 text-sm">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
            </div>
            <span class="px-5 py-2 bg-sanggablue group-hover:bg-sanggared text-white font-bold text-xs rounded-lg transition-all">
              Lihat Detail →
            </span>
          </div>
        </a>
      @empty
        <div class="bg-white border p-6 rounded-2xl shadow-sm text-center space-y-3 col-span-full py-14 text-slate-400 text-sm italic">
          Belum ada produk dinamis di database.
        </div>
      @endforelse
    </div>
  </section>

  <!-- ================= PERTANYAAN UMUM & FAQ ================= -->
  <section id="konsultasi" class="max-w-6xl mx-auto px-4 py-10 space-y-10">
    <div class="text-center space-y-3">
      <span class="text-sm font-black text-sanggared uppercase tracking-widest">
        Pertanyaan Umum (FAQ)
      </span>
      <h3 class="text-2xl md:text-3xl font-black text-sanggablue tracking-tight">
        Jawaban atas Pertanyaan Populer
      </h3>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start">
      <div class="lg:col-span-8 space-y-6">
        
        <!-- FAQ 1 -->
        <div class="border-b pb-4">
          <button onclick="toggleFaq(this)" class="w-full text-left font-bold text-sm md:text-base text-sanggablue flex justify-between items-center hover:text-sanggared transition-all">
            <span>Bagaimana prosedur pengiriman berkas desain saya?</span>
            <i class="fa-solid fa-plus text-xs"></i>
          </button>
          <p class="text-sm text-slate-500 leading-relaxed hidden faq-answer">
            Anda cukup masuk ke menu akun utama, klik 'Mulai Cetak Sekarang', isi detail kustom produk lalu unggah file format PDF/ZIP desain mentah Anda.
          </p>
        </div>

        <!-- FAQ 2 -->
        <div class="border-b pb-4">
          <button onclick="toggleFaq(this)" class="w-full text-left font-bold text-sm md:text-base text-sanggablue flex justify-between items-center hover:text-sanggared transition-all">
            <span>Berapa batas minimum jumlah pesanan cetak (MOQ)?</span>
            <i class="fa-solid fa-plus text-xs"></i>
          </button>
          <p class="text-sm text-slate-500 leading-relaxed hidden faq-answer">
            Untuk cetak banner/spanduk besar tidak ada batas minimum (bisa satuan). Untuk dus kemasan kustom atau brosur offset lipat, disarankan minimal 100 pcs demi menekan efisiensi harga plat dasarnya.
          </p>
        </div>

        <!-- FAQ 3 -->
        <div class="border-b pb-4">
          <button onclick="toggleFaq(this)" class="w-full text-left font-bold text-sm md:text-base text-sanggablue flex justify-between items-center hover:text-sanggared transition-all">
            <span>Apa jenis pembayaran aman yang diterima?</span>
            <i class="fa-solid fa-plus text-xs"></i>
          </button>
          <p class="text-sm text-slate-500 leading-relaxed hidden faq-answer">
            Sangga Buana menerima pembayaran resmi melalui transfer bank Mandiri, BCA, BRI, maupun metode QRIS instan otomatis.
          </p>
        </div>

      </div>
    </div>
  </section>

</div>

<!-- ================= MICRO INTERACTIONS JAVASCRIPT ENGINE ================= -->
<script>
  // Mengatur Buka-Tutup Akordion Tentang Kami
  function toggleAboutAccordion(index) {
    const root = document.getElementById('accordion-about');
    const contents = root.getElementsByClassName('accordion-content');
    const icons = root.getElementsByClassName('accordion-icon');
    
    for (let i = 0; i < contents.length; i++) {
      if (i === index) {
        if (contents[i].style.maxHeight === '0px' || !contents[i].style.maxHeight) {
          contents[i].style.maxHeight = contents[i].scrollHeight + 'px';
          icons[i].style.transform = 'rotate(180deg)';
        } else {
          contents[i].style.maxHeight = '0px';
          icons[i].style.transform = 'rotate(0deg)';
        }
      } else {
        contents[i].style.maxHeight = '0px';
        icons[i].style.transform = 'rotate(0deg)';
      }
    }
  }

  // Mengatur Buka-Tutup Menu FAQ
  function toggleFaq(btn) {
    const answer = btn.nextElementSibling;
    const icon = btn.querySelector('i');
    
    if (answer.classList.contains('hidden')) {
      answer.classList.remove('hidden');
      icon.className = 'fa-solid fa-minus text-[10px] text-sanggared';
    } else {
      answer.classList.add('hidden');
      icon.className = 'fa-solid fa-plus text-[10px]';
    }
  }
</script>
@endsection