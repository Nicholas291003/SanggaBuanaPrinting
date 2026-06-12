@extends('layouts.admin')
@section('page_title', 'Log Aktivitas Sistem')

@section('admin_content')
<div class="space-y-6">
  <p class="text-xs font-medium text-slate-400 -mt-6">Aktivitas yang dilakukan oleh Administrator.</p>

  <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-left text-xs">
      <thead>
        <tr class="bg-slate-50 text-slate-400 uppercase font-black tracking-wider text-[10px] border-b border-slate-100">
          <th class="p-4 w-1/5">Waktu Eksekusi</th>
          <th class="p-4 w-1/6">Tindakan</th>
          <th class="p-4">Deskripsi Perubahan Data</th>
          <th class="p-4 w-1/6">Eksekutor</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
        @forelse($logs as $log)
        <tr class="hover:bg-slate-50/50 transition-all">
          <td class="p-4 text-[10px] font-bold text-slate-400 uppercase">{{ $log->created_at->format('d M Y - H:i') }} WIB</td>
          <td class="p-4">
            <span class="px-2.5 py-1 rounded text-[9px] font-black uppercase tracking-wider 
              {{ str_contains($log->action, 'Hapus') ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600' }}">
              {{ $log->action }}
            </span>
          </td>
          <td class="p-4 leading-relaxed">{{ $log->description }}</td>
          <td class="p-4 font-black text-sanggablue">{{ $log->user_name }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="4" class="p-16 text-center text-slate-400 font-medium italic">Belum ada aktivitas yang direkam oleh sistem.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection