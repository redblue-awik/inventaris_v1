<header
    class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-8 py-4 sticky top-0 z-10">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">@yield('header_title', 'Dashboard')</h1>
        <p class="text-sm text-slate-500">@yield('header_subtitle', 'Pantau aktivitas inventaris Anda hari ini.')</p>
    </div>
    <div class="flex items-center space-x-6">
        <div class="flex items-center gap-3 pl-6 border-l border-slate-200">
            <div class="text-right hidden md:block">
                <p class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
            </div>
            @php
                $avatarColors = [
                    'admin' => '#dc3545',
                    'petugas' => '#0d6efd',
                ];
            @endphp
            <div class="w-10 h-10 rounded-full shadow-sm text-white flex items-center justify-center text-[21px]"
                style="background: {{ $avatarColors[Auth::user()->role] ?? '#ccc' }}">
                {{ substr(Auth::user()->name, 0, 1) }}</div>
        </div>
    </div>
</header>
