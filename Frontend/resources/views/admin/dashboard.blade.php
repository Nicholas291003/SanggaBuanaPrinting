@extends('layouts.admin')

@section('page_title', 'Dashboard')

@section('admin_content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6">
  <p class="text-xs font-medium text-slate-400 -mt-6">Ringkasan performa bisnis percetakan Sangga Buana.</p>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white border border-slate-100 p-5 rounded-2xl shadow-sm flex justify-between items-center">
      <div>
        <span class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Pendapatan Hari Ini</span>
        <h3 class="text-lg font-black text-sanggablue mt-1">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
      </div>
      <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl shadow-inner">
        <i class="fa-solid fa-coins"></i>
      </div>
    </div>

    <div class="bg-white border border-slate-100 p-5 rounded-2xl shadow-sm flex justify-between items-center">
      <div>
        <span class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Total Penjualan</span>
        <h3 class="text-lg font-black text-sanggablue mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
      </div>
      <div class="w-12 h-12 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center text-xl shadow-inner">
        <i class="fa-solid fa-chart-pie"></i>
      </div>
    </div>

    <div class="bg-white border border-slate-100 p-5 rounded-2xl shadow-sm flex justify-between items-center">
      <div>
        <span class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Total Pelanggan</span>
        <h3 class="text-lg font-black text-sanggablue mt-1">{{ number_format($totalPelanggan) }} <span class="text-xs text-slate-400">Akun</span></h3>
      </div>
      <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-xl shadow-inner">
        <i class="fa-solid fa-users"></i>
      </div>
    </div>

    <div class="bg-white border border-slate-100 p-5 rounded-2xl shadow-sm flex justify-between items-center">
      <div>
        <span class="block text-[10px] font-black uppercase text-slate-400 tracking-wider">Antrean Cetak</span>
        <h3 class="text-lg font-black text-sanggablue mt-1">{{ number_format($pesananBaru) }} <span class="text-xs text-slate-400">Pesanan</span></h3>
      </div>
      <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center text-xl shadow-inner">
        <i class="fa-solid fa-print"></i>
      </div>
    </div>

    
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2 bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
      <div class="mb-4">
        <h3 class="text-sm font-black text-sanggablue tracking-wider">Tinjauan Penjualan (7 Hari Terakhir)</h3>
        <p class="text-[10px] font-bold text-emerald-500 mt-1"><i class="fa-solid fa-arrow-trend-up"></i> Grafik arus kas masuk</p>
      </div>
      <div class="relative h-72 w-full">
        <canvas id="salesChart"></canvas>
      </div>
    </div>

    <div class="bg-[#032B22] rounded-3xl p-8 shadow-lg text-white relative overflow-hidden flex flex-col justify-between">
      <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
      <div class="absolute -left-10 -bottom-10 w-48 h-48 bg-emerald-500/20 rounded-full blur-3xl"></div>
      
      <div class="relative z-10">
        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-2xl mb-4">
          <i class="fa-solid fa-rocket"></i>
        </div>
        <h3 class="text-lg font-black tracking-wide mb-2">Halo, {{ Auth::user()->name }}!</h3>
        <p class="text-xs text-white/70 leading-relaxed font-medium">Selamat datang di Ruang Kendali Sangga Buana. Pantau terus pesanan cetak dan pastikan pelanggan mendapatkan pelayanan terbaik hari ini.</p>
      </div>
      
      <div class="relative z-10 mt-6 pt-6 border-t border-white/20">
        <div class="flex justify-between items-center">
          <div>
            <span class="block text-[10px] uppercase tracking-wider text-white/60 mb-1">Katalog Layanan</span>
            <span class="font-black text-sm">{{ $produkAktif }} / {{ $totalProduk }} Aktif</span>
          </div>
          <a href="{{ route('admin.produk.index') }}" class="w-8 h-8 rounded-full bg-white text-[#032B22] flex items-center justify-center hover:bg-slate-100 transition-all">
            <i class="fa-solid fa-arrow-right text-[10px]"></i>
          </a>
        </div>
      </div>
    </div>

  </div>

  <div class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm">
    <div class="flex justify-between items-center mb-5">
      <h3 class="text-sm font-black text-sanggablue tracking-wider">Pesanan Masuk Terbaru</h3>
      <a href="{{ route('admin.pesanan.index') }}" class="text-[10px] font-bold text-blue-500 hover:text-blue-600 uppercase tracking-wider">Lihat Semua</a>
    </div>
    
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs">
        <thead>
          <tr class="text-slate-400 uppercase font-black tracking-wider text-[10px] border-b border-slate-100">
            <th class="pb-3 w-1/4">Pelanggan</th>
            <th class="pb-3 w-1/3">Layanan Cetak</th>
            <th class="pb-3 w-1/4">Harga</th>
            <th class="pb-3 text-right">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-slate-600 font-semibold">
          @forelse($recentOrders as $order)
          <tr class="hover:bg-slate-50/50 transition-all">
            <td class="py-3">
              <span class="block font-black text-sanggablue text-xs">{{ $order->customer_name }}</span>
            </td>
            <td class="py-3">
              <span class="block text-[11px] font-bold">{{ $order->service_name }}</span>
              <span class="block text-[9px] text-slate-400">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
            </td>
            <td class="py-3 font-black text-sanggablue text-xs">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            <td class="py-3 text-right">
              <span class="px-2 py-1 rounded text-[9px] font-black uppercase tracking-wider 
                {{ $order->status == 'Selesai' ? 'bg-emerald-50 text-emerald-600' : ($order->status == 'Dalam Antrian' ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600') }}">
                {{ $order->status }}
              </span>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="4" class="py-6 text-center text-slate-400 text-[10px] font-bold">Belum ada pesanan masuk.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('salesChart').getContext('2d');

    const labels = {!! json_encode($chartDates) !!};
    const dataPoints = {!! json_encode($chartData) !!};

    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(239, 68, 68, 0.2)'); 
    gradient.addColorStop(1, 'rgba(239, 68, 68, 0)');

    new Chart(ctx, {
      type: 'line',
      data: {
        labels: labels,
        datasets: [{
          label: 'Pendapatan (Rp)',
          data: dataPoints,
          borderColor: '#EF4444', 
          backgroundColor: gradient,
          borderWidth: 3,
          pointBackgroundColor: '#ffffff',
          pointBorderColor: '#EF4444',
          pointBorderWidth: 2,
          pointRadius: 4,
          pointHoverRadius: 6,
          fill: true,
          tension: 0.4 
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: '#0F172A',
            titleFont: { size: 11, family: 'Inter' },
            bodyFont: { size: 12, weight: 'bold', family: 'Inter' },
            padding: 10,
            cornerRadius: 8,
            displayColors: false,
            callbacks: {
              label: function(context) {
                let value = context.raw || 0;
                return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: '#F1F5F9', drawBorder: false },
            ticks: {
              font: { size: 10, family: 'Inter', weight: 'bold' },
              color: '#94A3B8',
              callback: function(value) {
                if(value >= 1000000) return (value / 1000000) + 'Jt';
                if(value >= 1000) return (value / 1000) + 'Rb';
                return value;
              }
            }
          },
          x: {
            grid: { display: false, drawBorder: false },
            ticks: { font: { size: 10, family: 'Inter', weight: 'bold' }, color: '#94A3B8' }
          }
        }
      }
    });
  });
</script>
@endsection