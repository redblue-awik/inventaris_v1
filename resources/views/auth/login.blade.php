@extends('layouts.auth')

@section('title', 'Masuk')
@section('subtitle', 'Silakan masuk ke akun Anda')

@section('content')
    <form class="space-y-6" action="{{ route('login') }}" method="POST">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700">Alamat Email</label>
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-slate-400"></i>
                </div>
                <input id="email" name="email" type="email" autocomplete="email" required
                    class="appearance-none block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all"
                    placeholder="nama@example.com">
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700">Kata Sandi</label>
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-lock text-slate-400"></i>
                </div>
                <input id="password" name="password" type="password" autocomplete="current-password" required
                    class="appearance-none block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all"
                    placeholder="••••••••">
                <button type="button" id="togglePassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none text-slate-400 hover:text-indigo-600 transition-colors">
                    <i class="fas fa-eye"></i> <!-- Ganti ke icon eye agar lebih masuk akal untuk toggle -->
                </button>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember-me" name="remember-me" type="checkbox"
                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
                <label for="remember-me" class="ml-2 block text-sm text-slate-700">
                    Ingat saya
                </label>
            </div>
            
            <!-- Tambahan Opsional: Lupa Password -->
            <div class="text-sm">
                <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
                    Lupa sandi?
                </a>
            </div>
        </div>

        <div>
            <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-indigo-600/20">
                Masuk ke Dashboard
            </button>
        </div>
    </form>

    <!-- Divider -->
    <div class="mt-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-3 bg-white text-slate-500">
                    Atau masuk dengan
                </span>
            </div>
        </div>

        <!-- Google Login Button (Diperbarui) -->
        <div class="mt-6">
            <!-- Ganti href ke route login google yang sebenarnya (sebelumnya mengarah ke /register) -->
            <a href="{{ url('/auth/google') }}" 
               class="w-full flex justify-center items-center gap-3 py-2.5 px-4 border border-slate-300 rounded-xl shadow-sm bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                <svg class="w-5 h-5" viewBox="0 0 48 48">
                    <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path>
                    <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path>
                    <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path>
                    <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>
                </svg> 
                Google
            </a>
        </div>
    </div>

    <!-- Teks Belum Punya Akun (Dibuat lebih bersih tanpa garis divider bertumpuk) -->
    <div class="mt-8 text-center">
        <p class="text-sm text-slate-600">
            Belum punya akun? 
            <a href="{{ url('/register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
                Daftar sekarang
            </a>
        </p>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ToastS = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1300,
                    timerProgressBar: true
                });

                const ToastE = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                @if (session('success'))
                    ToastS.fire({ icon: 'success', title: "{{ session('success') }}" });
                @endif

                @if (session('error'))
                    ToastE.fire({ icon: 'error', title: "{{ session('error') }}" });
                @endif

                @if ($errors->any())
                    let msgs = "";
                    @foreach ($errors->all() as $err)
                        msgs += "{{ $err }} \n";
                    @endforeach

                    ToastE.fire({ icon: 'error', title: msgs });
                @endif

                @if (session('login_success'))
                    const data = @json(session('login_success'));
                    ToastS.fire({
                        icon: data.icon || 'success',
                        title: data.message || 'Login berhasil'
                    });

                    setTimeout(() => {
                        window.location.href = data.redirect || '/';
                    }, data.delay || 1300);
                @endif

                @if (session('logout_success'))
                    const dataLogout = @json(session('logout_success'));
                    ToastS.fire({
                        icon: dataLogout.icon || 'success',
                        title: dataLogout.message || 'Logout berhasil'
                    });

                    setTimeout(() => {
                        window.location.href = dataLogout.redirect || '/login';
                    }, dataLogout.delay || 1300);
                @endif
                
                // Opsional: Script untuk fitur toggle password agar berfungsi
                const togglePassword = document.querySelector('#togglePassword');
                const password = document.querySelector('#password');
                const toggleIcon = togglePassword.querySelector('i');

                togglePassword.addEventListener('click', function (e) {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    toggleIcon.classList.toggle('fa-eye');
                    toggleIcon.classList.toggle('fa-eye-slash');
                });
            });
        </script>
    @endpush
@endsection