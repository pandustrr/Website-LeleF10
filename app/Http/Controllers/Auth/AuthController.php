<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input tidak boleh kosong
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username tidak boleh kosong',
            'password.required' => 'Password tidak boleh kosong',
        ]);

        $credentials = $request->only('username', 'password');

        // Cek credentials tanpa hashing
        $user = \App\Models\User::where('username', $credentials['username'])
            ->where('password', $credentials['password'])
            ->first();

        if ($user) {
            Auth::login($user, $request->filled('remember'));
            return redirect()->intended('/');
        }

        return back()->withInput()->withErrors([
            'error' => 'Username atau password salah',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
