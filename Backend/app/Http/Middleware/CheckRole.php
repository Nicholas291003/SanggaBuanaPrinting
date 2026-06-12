<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Menangani permintaan yang masuk.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login atau belum
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();

        // 2. Cek apakah role user saat ini ada di dalam daftar role yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request); // Lolos validasi, lanjutkan ke halaman yang dituju
        }

        // 3. Jika tidak memiliki hak akses, kembalikan ke halaman utama sesuai role masing-masing
        if ($user->role === 'Administrator') {
            return redirect()->route('admin.dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return redirect('/')->with('error', 'Anda tidak memiliki hak akses untuk membuka halaman tersebut.');
    }
}