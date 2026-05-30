<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-gray-200 flex flex-col transform -translate-x-full transition-transform duration-300 ease-in-out md:relative md:translate-x-0 shadow-2xl md:shadow-none">
    
    <div class="h-20 flex items-center px-8 border-b border-gray-200">
        <div class="w-10 h-10 bg-blue-600/90 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-indigo-600/20">
            <i class="fas fa-cube text-xl text-white"></i>
        </div>
        <span class="font-bold text-xl tracking-wide">My-Inventory</span>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-8 space-y-2">
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->is('dashboard') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20' : 'text-slate-500 hover:text-slate-400 hover:bg-white/5' }}">
            <i class="fas fa-chart-pie w-6"></i> Dashboard
        </a>

        <a href="{{ route('barang') }}"
            class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->is('barang*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20' : 'text-slate-500 hover:text-slate-400 hover:bg-white/5' }}">
            <i class="fas fa-boxes-stacked w-6"></i> Data Barang
        </a>

        <a href="{{ route('kategori') }}"
            class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->is('kategori*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20' : 'text-slate-500 hover:text-slate-400 hover:bg-white/5' }}">
            <i class="fas fa-layer-group w-6"></i> Data Kategori
        </a>

        <a href="{{ route('supplier') }}"
            class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->is('supplier*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20' : 'text-slate-500 hover:text-slate-400 hover:bg-white/5' }}">
            <i class="fas fa-truck w-6"></i> Data Supplier
        </a>

        <a href="{{ route('permintaan') }}"
            class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->is('permintaan*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20' : 'text-slate-500 hover:text-slate-400 hover:bg-white/5' }}">
            <i class="fas fa-hand-holding-dollar w-6"></i> Permintaan
        </a>

        <a href="{{ route('mutasi_barang') }}"
            class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->is('mutasi_barang*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20' : 'text-slate-500 hover:text-slate-400 hover:bg-white/5' }}">
            <i class="fas fa-box-archive w-6"></i> Mutasi Barang
        </a>

        @if (Auth::user()->role === 'admin')
            <a href="{{ route('user') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->is('user*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20' : 'text-slate-500 hover:text-slate-400 hover:bg-white/5' }}">
                <i class="fas fa-users w-6"></i> Data User
            </a>

            <a href="{{ route('laporan') }}"
                class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->is('laporan*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20' : 'text-slate-500 hover:text-slate-400 hover:bg-white/5' }}">
                <i class="fas fa-chart-line w-6"></i> Laporan
            </a>
        @endif
    </nav>

    <div class="px-4 py-6 border-t border-gray-200 bg-slate-50 flex items-center justify-between">
        <a class="font-semibold text-rose-600 hover:text-rose-700 transition-colors w-full text-center md:text-left flex items-center justify-center md:justify-start" href="{{ route('logout') }}">
            Logout <i class="fa-solid fa-arrow-right-from-bracket ms-2"></i>
        </a>
    </div>
</aside>