<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function indexLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required|string',
            ],
            [
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'password.required' => 'Password wajib diisi.',
            ],
        );

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()
                ->route('login')
                ->with('login_success', [
                    'message' => 'Login berhasil',
                    'icon' => 'success',
                    'redirect' => route('dashboard'),
                    'delay' => 1300,
                ]);
        }

        return back()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ])
            ->withInput();
    }

    public function indexRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi input
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
            ],
            [
                'name.required' => 'Nama wajib diisi.',
                'name.string' => 'Nama harus berupa teks.',
                'name.max' => 'Nama maksimal 255 karakter.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'password.required' => 'Password wajib diisi.',
                'password.string' => 'Password harus berupa teks.',
                'password.min' => 'Password minimal 6 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ],
        );

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'petugas',
        ]);

        return redirect()
            ->route('register')
            ->with('register_success', [
                'message' => 'Registrasi berhasil. Silahkan Login.',
                'icon' => 'success',
                'redirect' => route('login'),
                'delay' => 1300,
            ]);
    }

    public function logout(Request $request)
    {
        $previousUrl = url()->previous();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect($previousUrl)
            ->with('logout_success', [
                'message' => 'Logout berhasil',
                'icon' => 'success',
                'redirect' => route('login'),
                'delay' => 1300,
            ]);
    }
}
