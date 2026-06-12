@extends('layouts.admin')

@section('page_title', 'Manajemen Konten Publik')

@section('admin_content')

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<div class="space-y-6" x-data="manajemenKonten" x-cloak>
    <p class="text-xs font-medium text-slate-400 -mt-6">Kelola data Pertanyaan Populer (FAQ), Informasi Kontak Utama, Jam Kerja Mesin Produksi, dan Link Akun Sosial Media resmi Toko.</p>

    <!-- HUB NAVIGASI TAB KONTEN -->
    <div class="flex gap-4 border-b border-slate-200 overflow-x-auto">
        <button type="button" @click="currentTab = 'faq'" :class="currentTab === 'faq' ? 'border-sanggared text-sanggared font-black' : 'border-transparent text-slate-500 font-bold'" class="pb-3 border-b-2 text-xs uppercase tracking-wider transition-all px-1">Kelola FAQ</button>
        <button type="button" @click="currentTab = 'kontak'" :class="currentTab === 'kontak' ? 'border-sanggared text-sanggared font-black' : 'border-transparent text-slate-500 font-bold'" class="pb-3 border-b-2 text-xs uppercase tracking-wider transition-all px-1">Kontak & Jam Kerja</button>
        <button type="button" @click="currentTab = 'sosmed'" :class="currentTab === 'sosmed' ? 'border-sanggared text-sanggared font-black' : 'border-transparent text-slate-500 font-bold'" class="pb-3 border-b-2 text-xs uppercase tracking-wider transition-all px-1">Tautan Sosial Media</button>
    </div>

    <!-- ==================== TAB 1: KELOLA DATA FAQ ==================== -->
    <div x-show="currentTab === 'faq'" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <!-- Form Tambah/Edit FAQ (4 Kolom) -->
        <div class="lg:col-span-4 bg-white border border-slate-100 p-5 rounded-3xl shadow-sm space-y-4">
            <h3 class="text-xs font-black text-sanggablue uppercase tracking-wider border-b pb-2" x-text="editFaqId ? 'Edit Pertanyaan FAQ' : 'Tambah FAQ Baru'"></h3>
            <form :action="editFaqId ? '/admin/konten/faq/' + editFaqId : '{{ route('admin.faq.store') }}'" method="POST" class="space-y-4">
                @csrf
                <template x-if="editFaqId"><input type="hidden" name="_method" value="PUT"></template>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase">Pertanyaan (Question)</label>
                    <input type="text" name="question" x-model="editQuestion" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-sanggablue focus:outline-none focus:border-sanggared">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase">Jawaban (Answer)</label>
                    <textarea name="answer" x-model="editAnswer" required rows="4" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-sanggablue focus:outline-none focus:border-sanggared"></textarea>
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="flex-grow bg-sanggared text-white font-black py-2.5 rounded-xl text-xs uppercase tracking-wider shadow-sm flex items-center justify-center gap-1"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                    <button type="button" x-show="editFaqId" @click="batalEdit" class="bg-slate-100 text-slate-500 font-bold px-3 rounded-xl text-xs uppercase">Batal</button>
                </div>
            </form>
        </div>

        <!-- Daftar List FAQ (8 Kolom) -->
        <div class="lg:col-span-8 bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-400 font-black border-b border-slate-100 uppercase text-[9px] tracking-wider">
                        <th class="py-3.5 px-5 w-5/12">Pertanyaan</th>
                        <th class="py-3.5 px-5 w-5/12">Jawaban Singkat</th>
                        <th class="py-3.5 px-5 w-2/12 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-bold text-slate-700">
                    @forelse($faqs as $faq)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="py-3 px-5 text-sanggablue font-black">{{ $faq->question }}</td>
                        <td class="py-3 px-5 text-slate-500 font-medium line-clamp-2 mt-1.5">{{ $faq->answer }}</td>
                        <td class="py-3 px-5 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" @click="pilihEdit('{{ $faq->id }}', '{{ $faq->question }}', '{{ $faq->answer }}')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 flex items-center justify-center"><i class="fa-solid fa-pen-to-square text-xs"></i></button>
                                <form action="{{ route('admin.faq.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Hapus FAQ ini?')">@csrf @method('DELETE')<button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center"><i class="fa-solid fa-trash-can text-xs"></i></button></form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-8 text-slate-400 italic">Belum ada data FAQ yang diinput.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MAIN FORM UNTUK TAB 2 DAN TAB 3 -->
    <form action="{{ route('admin.kontak.update') }}" method="POST">
        @csrf @method('PUT')

        <!-- ==================== TAB 2: KONTAK & JAM OPERASIONAL DINAMIS ==================== -->
        <div x-show="currentTab === 'kontak'" class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-6">
            <h3 class="text-xs font-black text-sanggablue uppercase tracking-wider border-b pb-3 flex items-center gap-2">
                <i class="fa-solid fa-business-time text-sanggared"></i> Hubungi Toko & Jam Operasional Kerja
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Nomor WhatsApp CS Utama</label>
                    <input type="text" name="phone" value="{{ $setting->phone }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-sanggablue focus:outline-none focus:border-sanggared">
                </div>
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Email Korespondensi Resmi</label>
                    <input type="email" name="email" value="{{ $setting->email }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-sanggablue focus:outline-none focus:border-sanggared">
                </div>
            </div>

            <!-- BAGIAN REPEATER LIST JAM OPERASIONAL -->
            <div class="space-y-3 pt-4 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Pengaturan Baris Hari & Jam Kerja</label>
                    <button type="button" @click="hours.push({day: '', time: ''})" class="px-3 py-1 bg-slate-100 hover:bg-sanggared hover:text-white text-sanggablue font-black text-[10px] uppercase tracking-wider rounded-lg transition-colors flex items-center gap-1">
                        <i class="fa-solid fa-plus text-[8px]"></i> Tambah Jam Kerja
                    </button>
                </div>

                <div class="space-y-3">
                    <div x-show="hours.length === 0" class="text-center py-6 bg-slate-50 rounded-2xl text-xs text-slate-400 italic font-bold border border-dashed border-slate-200">
                        Belum ada baris jam kerja. Klik tombol "+ Tambah Jam Kerja" di atas.
                    </div>

                    <template x-for="(item, index) in hours" :key="index">
                        <div class="flex items-center gap-3 bg-slate-50 border border-slate-100 p-3 rounded-2xl">
                            <div class="w-1/2 space-y-1">
                                <span class="text-[9px] font-bold text-slate-400 uppercase">Hari / Kategori</span>
                                <input type="text" :name="'operational_hours[' + index + '][day]'" x-model="item.day" placeholder="Misal: Senin - Jumat" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-sanggablue focus:outline-none focus:border-sanggared">
                            </div>
                            <div class="w-1/2 space-y-1">
                                <span class="text-[9px] font-bold text-slate-400 uppercase">Jam Kerja / Keterangan</span>
                                <input type="text" :name="'operational_hours[' + index + '][time]'" x-model="item.time" placeholder="Misal: 08:00 - 21:00 WIB" required class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-600 focus:outline-none focus:border-sanggared">
                            </div>
                            <button type="button" @click="hours.splice(index, 1)" class="w-9 h-9 mt-4 shrink-0 rounded-xl bg-white text-slate-300 hover:bg-red-50 hover:text-red-500 border border-slate-200 transition-colors flex items-center justify-center">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-sanggablue hover:bg-sanggared text-white font-black px-6 py-3.5 rounded-xl text-xs uppercase tracking-wider transition-all shadow-md">Simpan Perubahan Kontak</button>
            </div>
        </div>

        <!-- ==================== TAB 3: KELOLA SOSIAL MEDIA DINAMIS ==================== -->
        <div x-show="currentTab === 'sosmed'" class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="text-xs font-black text-sanggablue uppercase tracking-wider flex items-center gap-2">
                    <i class="fa-solid fa-share-nodes text-sanggared"></i> Tautan Akun Sosial Media Jejaring Toko
                </h3>
                <button type="button" @click="sosmeds.push({platform: 'instagram', url: ''})" class="px-3 py-1 bg-slate-100 hover:bg-sanggared hover:text-white text-sanggablue font-black text-[10px] uppercase tracking-wider rounded-lg transition-colors flex items-center gap-1">
                    <i class="fa-solid fa-plus text-[8px]"></i> Tambah Akun Sosmed
                </button>
            </div>

            <div class="space-y-4">
                <div x-show="sosmeds.length === 0" class="text-center py-6 bg-slate-50 rounded-2xl text-xs text-slate-400 italic font-bold border border-dashed border-slate-200">
                    Belum ada akun sosial media. Klik tombol "+ Tambah Akun Sosmed" di atas.
                </div>

                <template x-for="(item, index) in sosmeds" :key="index">
                    <div class="flex items-center gap-4 bg-slate-50 border border-slate-100 p-4 rounded-2xl group relative">
                        <div class="w-12 h-12 rounded-xl bg-white shadow-sm border border-slate-200 flex items-center justify-center text-lg shrink-0"
                            :class="item.platform === 'instagram' ? 'text-pink-600' : (item.platform === 'facebook' ? 'text-blue-600' : (item.platform === 'whatsapp' ? 'text-emerald-500' : 'text-slate-600'))">
                            <i :class="'fa-brands fa-' + item.platform"></i>
                        </div>

                        <div class="w-1/4 space-y-1">
                            <span class="text-[9px] font-bold text-slate-400 uppercase">Platform</span>
                            <select :name="'social_media[' + index + '][platform]'" x-model="item.platform" class="w-full bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-sanggablue focus:outline-none">
                                <option value="instagram">Instagram</option>
                                <option value="facebook">Facebook</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="tiktok">TikTok</option>
                                <option value="youtube">YouTube</option>
                                <option value="twitter">X / Twitter</option>
                            </select>
                        </div>

                        <div class="w-3/4 space-y-1">
                            <span class="text-[9px] font-bold text-slate-400 uppercase">Tautan URL Lengkap</span>
                            <input type="url" :name="'social_media[' + index + '][url]'" x-model="item.url" placeholder="https://example.com/akun-anda" required class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 text-xs font-medium text-slate-600 focus:outline-none focus:border-sanggared">
                        </div>

                        <button type="button" @click="sosmeds.splice(index, 1)" class="w-9 h-9 mt-4 shrink-0 rounded-xl bg-white text-slate-300 hover:bg-red-50 hover:text-red-500 border border-slate-200 transition-colors flex items-center justify-center">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                </template>
            </div>

            <div class="pt-4 flex justify-end">
                <button type="submit" class="bg-sanggablue hover:bg-sanggared text-white font-black px-6 py-3.5 rounded-xl text-xs uppercase tracking-wider transition-all shadow-md">Simpan Perubahan Tautan</button>
            </div>
        </div>
    </form>
</div>

<!-- ==================== DETEKSI DATA AMAN BERBASIS JAVASCRIPT ENGINE ==================== -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('manajemenKonten', () => ({
            currentTab: 'faq',
            editFaqId: null,
            editQuestion: '',
            editAnswer: '',
            hours: @json($setting->operational_hours ?? []),
            sosmeds: @json($setting->social_media ?? []),

            pilihEdit(id, q, a) {
                this.editFaqId = id;
                this.editQuestion = q;
                this.editAnswer = a;
            },
            batalEdit() {
                this.editFaqId = null;
                this.editQuestion = '';
                this.editAnswer = '';
            }
        }));
    });
</script>
@endsection