@extends('layouts.admin')

@section('page_title', 'Tambah Produk Baru')

@section('admin_content')
<div class="space-y-6">
  <div class="text-[11px] font-bold uppercase tracking-wider text-slate-400 -mt-6 flex items-center gap-2">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-sanggared transition-all">Dashboard</a>
    <i class="fa-solid fa-chevron-right text-[9px]"></i>
    <a href="{{ route('admin.produk.index') }}" class="hover:text-sanggared transition-all">Katalog</a>
    <i class="fa-solid fa-chevron-right text-[9px]"></i>
    <span class="text-sanggablue">Tambah Produk</span>
  </div>

  @if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-sanggared p-4 rounded-xl mb-6 shadow-sm">
      <div class="flex items-center gap-2 font-black text-xs mb-2 uppercase tracking-wider">
        <i class="fa-solid fa-triangle-exclamation"></i> Gagal Menyimpan Data!
      </div>
      <ul class="list-disc list-inside text-xs font-bold space-y-1">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-8">
    @csrf

    <div class="lg:col-span-8 space-y-6">
      
      <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm space-y-6">
        <h4 class="font-extrabold text-sanggablue text-sm border-b border-slate-100 pb-3">Informasi Dasar</h4>
        
        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Nama Produk <span class="text-sanggared">*</span></label>
          <input type="text" name="name" required placeholder="Contoh: Sticker Cromo Bontak" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-semibold">
        </div>

        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Kategori <span class="text-sanggared">*</span></label>
          <select name="category" required class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-bold cursor-pointer">
            <option value="">-- Pilih Kategori --</option>
            
            @foreach($categories as $kategori)
              <option value="{{ $kategori->name }}">{{ $kategori->name }}</option>
            @endforeach
            
          </select>
        </div>

        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Spesifikasi & Deskripsi <span class="text-sanggared">*</span></label>
          <textarea name="description" rows="4" required placeholder="Detail bahan, ukuran, dan spesifikasi..." class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 text-sanggablue font-semibold resize-none"></textarea>
        </div>
      </div>

      <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm space-y-4">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
          <h4 class="font-extrabold text-sanggablue text-sm">Skema Harga per Kuantitas</h4>
          <button type="button" onclick="tambahBarisHarga()" class="text-[10px] font-black bg-emerald-50 text-emerald-600 px-3 py-1.5 rounded-lg hover:bg-emerald-100 transition-all">
            + Tambah Aturan Harga
          </button>
        </div>
        
        <div id="wadah-harga" class="space-y-3">
          <div class="flex items-center gap-2 baris-harga mt-3">
            <div class="w-1/3">
              <select name="tier_operator[]" class="w-full px-2 py-2 text-[10px] rounded-lg border bg-slate-50/50 outline-none cursor-pointer font-bold text-slate-600" onchange="ubahTampilanInputQty(this)">
                <option value="range">Rentang (Min - Max)</option>
                <option value=">=">Lebih dari/sama dengan (>=)</option>
                <option value=">">Lebih dari (>)</option>
                <option value="<=">Kurang dari/sama dengan (<=)</option>
                <option value="<">Kurang dari (<)</option>
              </select>
            </div>
            <div class="w-1/5">
              <input type="number" name="tier_qty_1[]" placeholder="Qty 1" required class="w-full px-3 py-2 text-xs rounded-lg border bg-slate-50/50 outline-none">
            </div>
            <span class="text-slate-300 font-bold separator-qty">-</span>
            <div class="w-1/5 input-qty-2">
              <input type="number" name="tier_qty_2[]" placeholder="Qty 2" class="w-full px-3 py-2 text-xs rounded-lg border bg-slate-50/50 outline-none">
            </div>
            <div class="w-1/3 relative">
              <span class="absolute left-2 top-2 text-xs font-bold text-slate-400">Rp</span>
              <input type="number" name="tier_price[]" required placeholder="Harga" class="w-full pl-7 pr-2 py-2 text-xs rounded-lg border bg-slate-50/50 font-bold text-sanggablue outline-none">
            </div>
            <button type="button" onclick="hapusBaris(this)" class="w-8 h-8 shrink-0 bg-slate-50 rounded-lg border hover:text-red-500"><i class="fa-solid fa-xmark text-xs"></i></button>
          </div>
        </div>
      </div>

      <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm space-y-4">
        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
          <h4 class="font-extrabold text-sanggablue text-sm">Opsi Tambahan (Finishing/Laminasi)</h4>
          <button type="button" onclick="tambahBarisOpsi()" class="text-[10px] font-black bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-all">
            + Tambah Opsi
          </button>
        </div>

        <div id="wadah-opsi" class="space-y-3">
          <div class="flex items-center gap-3 baris-opsi">
            <div class="w-2/3">
              <input type="text" name="variant_name[]" placeholder="Nama Opsi (Contoh: Laminasi Glossy)" class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 bg-slate-50/50 focus:border-sanggablue outline-none">
            </div>
            <div class="w-1/3 relative">
              <span class="absolute left-3 top-2 text-xs font-bold text-slate-400">+Rp</span>
              <input type="number" name="variant_price[]" placeholder="Biaya Tambahan" class="w-full pl-9 pr-3 py-2 text-xs rounded-lg border border-slate-200 bg-slate-50/50 focus:border-sanggablue outline-none font-bold text-sanggablue">
            </div>
            <button type="button" onclick="hapusBaris(this)" class="w-8 h-8 shrink-0 text-slate-400 hover:text-red-500 transition-all flex items-center justify-center bg-slate-50 rounded-lg border border-slate-200">
              <i class="fa-solid fa-xmark text-xs"></i>
            </button>
          </div>
        </div>
        <p class="text-[9px] text-slate-400 font-medium italic mt-2">*Biarkan kosong jika produk ini tidak memiliki opsi finishing tambahan.</p>
      </div>

    </div>

    <div class="lg:col-span-4 space-y-6">
      <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm space-y-6">
        <h4 class="font-extrabold text-sanggablue text-sm border-b border-slate-100 pb-3">Media & Status</h4>
        
        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Foto Produk Preview</label>
          <div class="border-2 border-dashed border-slate-200 hover:border-sanggared rounded-xl p-6 text-center cursor-pointer bg-slate-50/50 transition-all group relative">
            <input type="file" name="image" id="image-upload" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewImage(event)">
            
            <div id="upload-placeholder" class="space-y-2">
              <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto text-base group-hover:scale-110 transition-all"><i class="fa-solid fa-cloud-arrow-up"></i></div>
              <span class="block text-xs font-extrabold text-sanggablue">Pilih Foto Produk</span>
            </div>
            <div id="upload-preview" class="hidden w-full h-32 rounded-lg overflow-hidden border border-slate-200">
              <img id="img-rendered" class="w-full h-full object-cover">
            </div>
          </div>
        </div>

        <div>
          <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Status Etalase</label>
          <select name="status" class="w-full px-4 py-3 text-xs rounded-xl border border-slate-200 focus:border-sanggablue focus:outline-none bg-slate-50/50 font-bold cursor-pointer">
            <option value="Tersedia">🟢 Aktif di Toko</option>
            <option value="Kosong">🔴 Sembunyikan</option>
          </select>
        </div>
      </div>

      <div class="flex gap-3">
        <a href="{{ route('admin.produk.index') }}" class="w-1/3 py-3 bg-white border border-slate-200 hover:bg-slate-50 text-sanggablue font-black text-xs rounded-xl text-center flex items-center justify-center transition-all">Batal</a>
        <button type="submit" class="w-2/3 py-3 bg-[#032B22] hover:bg-[#021F18] text-white font-black text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
          <i class="fa-solid fa-floppy-disk"></i> Simpan Produk
        </button>
      </div>
    </div>
  </form>
</div>

<script>
  function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
      document.getElementById('img-rendered').src = reader.result;
      document.getElementById('upload-placeholder').classList.add('hidden');
      document.getElementById('upload-preview').classList.remove('hidden');
    }
    if(event.target.files[0]) reader.readAsDataURL(event.target.files[0]);
  }

  function hapusBaris(element) {
    element.parentElement.remove();
  }

  function ubahTampilanInputQty(select) {
    const row = select.closest('.baris-harga');
    const input2 = row.querySelector('.input-qty-2');
    const separator = row.querySelector('.separator-qty');
    if (select.value === 'range') {
      input2.style.display = 'block';
      separator.style.display = 'block';
    } else {
      input2.style.display = 'none';
      separator.style.display = 'none';
    }
  }

  function tambahBarisHarga() {
    const html = `
      <div class="flex items-center gap-2 baris-harga mt-3">
        <div class="w-1/3">
          <select name="tier_operator[]" class="w-full px-2 py-2 text-[10px] rounded-lg border bg-slate-50/50 outline-none cursor-pointer font-bold text-slate-600" onchange="ubahTampilanInputQty(this)">
            <option value="range">Rentang (Min - Max)</option>
            <option value=">=">Lebih dari/sama dengan (>=)</option>
            <option value=">">Lebih dari (>)</option>
            <option value="<=">Kurang dari/sama dengan (<=)</option>
            <option value="<">Kurang dari (<)</option>
          </select>
        </div>
        <div class="w-1/5"><input type="number" name="tier_qty_1[]" placeholder="Qty" required class="w-full px-3 py-2 text-xs rounded-lg border bg-slate-50/50 outline-none"></div>
        <span class="text-slate-300 font-bold separator-qty">-</span>
        <div class="w-1/5 input-qty-2"><input type="number" name="tier_qty_2[]" placeholder="Max" class="w-full px-3 py-2 text-xs rounded-lg border bg-slate-50/50 outline-none"></div>
        <div class="w-1/3 relative">
          <span class="absolute left-2 top-2 text-xs font-bold text-slate-400">Rp</span>
          <input type="number" name="tier_price[]" required placeholder="Harga" class="w-full pl-7 pr-2 py-2 text-xs rounded-lg border bg-slate-50/50 font-bold text-sanggablue outline-none">
        </div>
        <button type="button" onclick="hapusBaris(this)" class="w-8 h-8 shrink-0 bg-slate-50 rounded-lg border hover:text-red-500"><i class="fa-solid fa-xmark text-xs"></i></button>
      </div>`;
    document.getElementById('wadah-harga').insertAdjacentHTML('beforeend', html);
  }

  function tambahBarisOpsi() {
    const html = `
      <div class="flex items-center gap-3 baris-opsi mt-3">
        <div class="w-2/3"><input type="text" name="variant_name[]" placeholder="Nama Opsi" required class="w-full px-3 py-2 text-xs rounded-lg border border-slate-200 bg-slate-50/50 outline-none"></div>
        <div class="w-1/3 relative">
          <span class="absolute left-3 top-2 text-xs font-bold text-slate-400">+Rp</span>
          <input type="number" name="variant_price[]" placeholder="Biaya" required class="w-full pl-9 pr-3 py-2 text-xs rounded-lg border border-slate-200 bg-slate-50/50 font-bold text-sanggablue outline-none">
        </div>
        <button type="button" onclick="hapusBaris(this)" class="w-8 h-8 shrink-0 text-slate-400 hover:text-red-500 bg-slate-50 rounded-lg border border-slate-200"><i class="fa-solid fa-xmark text-xs"></i></button>
      </div>`;
    document.getElementById('wadah-opsi').insertAdjacentHTML('beforeend', html);
  }
</script>
@endsection