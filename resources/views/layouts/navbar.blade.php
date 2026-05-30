<header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-4 md:px-8 py-4 sticky top-0 z-10">
    <div class="flex items-center gap-4">
        <button id="mobileMenuBtn" class="md:hidden text-slate-500 hover:text-indigo-600 focus:outline-none transition-colors">
            <i class="fas fa-bars text-2xl"></i>
        </button>

        <div>
            <h1 class="text-xl md:text-2xl font-bold text-slate-800">@yield('header_title', 'Dashboard')</h1>
            <p class="text-xs md:text-sm text-slate-500 hidden sm:block">@yield('header_subtitle', 'Pantau aktivitas inventaris Anda hari ini.')</p>
        </div>
    </div>

    <div class="flex items-center space-x-4 md:space-x-6">
        <div class="flex items-center gap-3 pl-4 md:pl-6 border-l border-slate-200">
            <div class="text-right hidden md:block">
                <p class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
            </div>
            
            @php
                $avatarColors = [
                    'admin' => '#dc3545',
                    'staf' => '#0d6efd',
                    'gudang' => '#99781c',
                ];
            @endphp
            
            <div class="flex items-center space-x-3">
                <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=' . urlencode(str_replace('#', '', $avatarColors[Auth::user()->role] ?? 'ccc')) . '&color=fff' }}"
                    alt="Foto Profil"
                    class="w-10 h-10 rounded-full object-cover border-2 border-slate-200 shadow-sm"
                    referrerpolicy="no-referrer">
            </div>
        </div>
    </div>
</header>