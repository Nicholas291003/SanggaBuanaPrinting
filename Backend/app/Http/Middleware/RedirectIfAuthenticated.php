<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                
                // Pisahkan redirect agar tidak salah alamat dan looping
                if ($user->role === 'Administrator') {
                    return redirect()->route('admin.dashboard');
                }
                
                // Jika pelanggan biasa, kembalikan ke halaman utama publik
                return redirect('/');
            }
        }

        return $next($request);
    }
}