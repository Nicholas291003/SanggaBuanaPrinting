<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Atau App\Models\Customer tergantung struktur tabel user Anda
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            // Skema Pengalihan Sesuai Role
            if (Auth::user()->role === 'Administrator') {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Admin!');
            }

            return redirect('/')->with('success', 'Selamat datang kembali di Sangga Buana!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Pelanggan', // Default pendaftaran otomatis sebagai pelanggan mitra
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Pendaftaran berhasil! Selamat bergabung di Sangga Buana.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}