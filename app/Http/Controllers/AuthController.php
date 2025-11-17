<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role === 'guru') {
                return redirect()->route('guru.dashboard');
            }

            if ($user->role === 'siswa') {
                return redirect()->route('siswa.dashboard');
            }

            Auth::logout();
            return back()->withErrors([
                'email' => 'Role tidak dikenali.'
            ]);
        }

        return back()->with('loginError', 'Login gagal. Email atau password salah.');
    }
}
