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
                    placeholder="admin@nexstock.com">
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
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none text-slate-400 hover:text-indigo-600 transition-colors">
                    <i class="fas fa-lock"></i>
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
        </div>

        <div>
            <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-indigo-600/20">
                Masuk ke Dashboard
            </button>
        </div>
    </form>

    <div class="mt-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-200"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-slate-500">
                    Belum punya akun?
                </span>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ url('/register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
                Daftar sekarang
            </a>
        </div>
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
                    ToastS.fire({
                        icon: 'success',
                        title: "{{ session('success') }}"
                    });
                @endif

                @if (session('error'))
                    ToastE.fire({
                        icon: 'error',
                        title: "{{ session('error') }}"
                    });
                @endif

                @if ($errors->any())
                    let msgs = "";
                    @foreach ($errors->all() as $err)
                        msgs += "{{ $err }} \n";
                    @endforeach

                    ToastE.fire({
                        icon: 'error',
                        title: msgs
                    });
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
                    const data = @json(session('logout_success'));
                    ToastS.fire({
                        icon: data.icon || 'success',
                        title: data.message || 'Logout berhasil'
                    });

                    setTimeout(() => {
                        window.location.href = data.redirect || '/login';
                    }, data.delay || 1300);
                @endif

            });
        </script>
    @endpush
@endsection
