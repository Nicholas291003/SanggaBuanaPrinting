@extends('layouts.admin')

@section('page_title', 'Data Pelanggan')

@section('admin_content')
<div class="space-y-6">
  <p class="text-xs font-medium text-slate-400 -mt-6">Kelola informasi dan pantau aktivitas belanja pelanggan Anda.</p>

  <div class="flex justify-end">
    <button onclick="window.print()" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-50 text-sanggablue font-bold text-xs rounded-xl flex items-center gap-2 shadow-sm transition-all">
      <i class="fa-solid fa-file-export text-slate-400"></i> Export CSV
    </button>
  </div>

  <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="bg-slate-50 text-slate-400 uppercase font-black tracking-wider text-[10px] border-b border-slate-100">
            <th class="p-4 w-1/3">Profil Pelanggan</th>
            <th class="p-4">Bergabung Pada</th>
            <th class="p-4">Total Pesanan</th>
            <th class="p-4">Total Belanja</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-slate-600 font-semibold">

          {{-- Loop Data Pelanggan / Costumer dari Database --}}
          @forelse($customers as $customer)
          <tr class="hover:bg-slate-50/50 transition-all">
            <td class="p-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-100 text-sanggablue flex items-center justify-center font-extrabold text-xs border border-slate-200 shrink-0 uppercase">
                  {{ substr($customer->name, 0, 1) }}
                </div>
                <div>
                  <span class="block font-black text-sanggablue text-sm capitalize tracking-tight">{{ $customer->name }}</span>
                  <span class="block text-slate-400 text-[11px] font-normal mt-0.5">{{ $customer->email }}</span>
                </div>
              </div>
            </td>
            
            <td class="p-4 text-slate-700 font-medium">
              {{ $customer->created_at ? $customer->created_at->format('d M Y') : 'Error' }}
            </td>
            
            <td class="p-4">
              <span class="px-2.5 py-1 rounded-full bg-blue-50 text-blue-600 text-[10px] font-black tracking-wide uppercase">
                {{ $customer->orders->count() }} Pesanan
              </span>
            </td>

            <td class="p-4 font-black text-emerald-600 text-sm">
              Rp {{ number_format($customer->orders->sum('total_price'), 0, ',', '.') }}
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="p-16 text-center text-slate-400 font-medium">
              <div class="text-3xl mb-3 text-slate-300"><i class="fa-solid fa-users-viewfinder"></i></div>
              <p class="text-xs">Belum ada pelanggan terdaftar di dalam database.</p>
            </td>
          </tr>
          @endforelse

        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection