@extends('layouts.auth')

@section('title', 'Daftar')
@section('subtitle', 'Buat akun baru untuk memulai')

@section('content')
    <form class="space-y-5" action="{{ route('register') }}" method="POST">
        @csrf

        <div>
            <label for="name" class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-user text-slate-400"></i>
                </div>
                <input id="name" name="name" type="text" autocomplete="name" required
                    class="appearance-none block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all"
                    placeholder="Budi Santoso" value="{{ old('name') }}">
            </div>
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-slate-700">Alamat Email</label>
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-envelope text-slate-400"></i>
                </div>
                <input id="email" name="email" type="email" autocomplete="email" required
                    class="appearance-none block w-full pl-10 pr-3 py-2.5 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all"
                    placeholder="budi@perusahaan.com" value="{{ old('email') }}">
            </div>
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-slate-700">Kata Sandi</label>
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-key text-slate-400"></i>
                </div>
                <input id="password" name="password" type="password" required
                    class="appearance-none block w-full pl-10 pr-10 py-2.5 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all"
                    placeholder="Minimal 6 karakter">
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none text-slate-400 hover:text-indigo-600 transition-colors">
                    <i class="fas fa-lock"></i>
                </button>
            </div>
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Konfirmasi Kata Sandi</label>
            <div class="mt-1 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-check-circle text-slate-400"></i>
                </div>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="appearance-none block w-full pl-10 pr-10 py-2.5 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-all"
                    placeholder="Ulangi kata sandi">
                <button type="button" id="togglePasswordConf" class="absolute inset-y-0 right-0 pr-3 flex items-center focus:outline-none text-slate-400 hover:text-indigo-600 transition-colors">
                    <i class="fas fa-lock"></i>
                </button>
            </div>
        </div>

        <div class="pt-2">
            <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all shadow-indigo-600/20">
                Buat Akun
            </button>
        </div>
    </form>

    <div class="mt-6 text-center text-sm text-slate-600">
        Sudah memiliki akun?
        <a href="{{ url('/login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">
            Masuk di sini
        </a>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // --- KONFIGURASI SWEETALERT ---
                const ToastS = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1300,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
                
                const ToastE = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                // --- TRIGGER NOTIFIKASI DARI SESSION ---
                @if (session('success'))
                    ToastS.fire({ icon: 'success', title: "{{ session('success') }}" });
                @endif

                @if (session('error'))
                    ToastE.fire({ icon: 'error', title: "{{ session('error') }}" });
                @endif

                @if ($errors->any())
                    let msgs = "";
                    @foreach ($errors->all() as $err)
                        msgs += "{{ $err }} <br>";
                    @endforeach
                    ToastE.fire({ icon: 'error', title: msgs });
                @endif

                @if (session('register_success'))
                    const regPayload = @json(session('register_success'));
                    if (regPayload) {
                        ToastS.fire({
                            icon: regPayload.icon || 'success',
                            title: regPayload.message || 'Registrasi berhasil'
                        });
                        const delayMs = Number(regPayload.delay) || 1500;
                        setTimeout(() => {
                            window.location.href = regPayload.redirect || '/login';
                        }, delayMs);
                    }
                @endif
            });
        </script>
    @endpush
@endsection