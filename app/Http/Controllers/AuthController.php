<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

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
                'name' => 'required|string|max:255|unique:users,name',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'departemen' => 'required|string|max:255',
            ],
            [
                'name.required' => 'Nama wajib diisi.',
                'name.string' => 'Nama harus berupa teks.',
                'name.unique' => 'Nama sudah terdaftar, Tolong gunakan nama lain.',
                'name.max' => 'Nama maksimal 255 karakter.',
                'email.required' => 'Email wajib diisi.',
                'email.email' => 'Format email tidak valid.',
                'email.unique' => 'Email sudah terdaftar.',
                'departemen.required' => 'Departemen wajib diisi.',
                'departemen.string' => 'Departemen harus berupa teks.',
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
            'role' => 'staf',
            'departemen' => $request->departemen,
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

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                return redirect()->route('login')->with('error', 'Akun tidak terdaftar, hubungi admin');
            }

            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);

            Auth::login($user);

            return redirect('login')->with('login_success', [
                'message' => 'Login berhasil',
                'icon' => 'success',
                'redirect' => 'dashboard',
                'delay' => 1300,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Login Google gagal');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('dashboard')
            ->with('logout_success', [
                'message' => 'Logout berhasil',
                'icon' => 'success',
                'redirect' => route('login'),
                'delay' => 1300,
            ]);
    }
}
