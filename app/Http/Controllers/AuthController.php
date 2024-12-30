<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        try {
            // log buat melihat credentials yang diterima
            Log::info('Login attempt with credentials:', [
                'username' => $credentials['username']
            ]);

            if (Auth::attempt($credentials)) {
                // log ketika autentikasi berhasil
                Log::info('Auth attempt successful');
                
                $request->session()->regenerate();
                $user = Auth::user();
                
                // log aktivitas login
                Log::info('User logged in', [
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role
                ]);

                // Redirect berdasarkan role
                if ($user->role === 'admin') {
                    return redirect()->intended('/admin/dashboard');
                } else if ($user->role === 'approver') {
                    return redirect()->intended('/approver/dashboard');
                } else {
                    return redirect()->intended('/dashboard');
                }
            } else {
                // log ketika autentikasi gagal
                Log::info('Auth attempt failed');
            }

            return back()->withErrors([
                'username' => 'Kredensial yang diberikan tidak sesuai dengan data kami.',
            ])->withInput($request->except('password'));

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage(), [
                'username' => $credentials['username']
            ]);

            return back()->withErrors([
                'username' => 'Terjadi kesalahan saat login. Silakan coba lagi.',
            ])->withInput($request->except('password'));
        }
    }

    public function logout(Request $request)
    {
        try {
            if (Auth::check()) {
                // Log aktivitas logout
                Log::info('User logged out', [
                    'user_id' => Auth::id(),
                    'username' => Auth::user()->username
                ]);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login');
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return redirect('/login');
        }
    }
}