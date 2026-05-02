<aside class="w-64 bg-white border-gray-100 flex flex-col hidden md:flex">
    <div class="h-20 flex items-center px-8 border-b border-gray-200">
        <div class="w-10 h-10 bg-blue-600/90 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-indigo-600/20">
            <i class="fas fa-cube text-xl text-white"></i>
        </div>
        <span class="font-bold text-xl tracking-wide">RokuStock</span>
    </div>
    
    <nav class="flex-1 px-4 py-8 space-y-2">
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->is('dashboard') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20' : 'text-slate-500 hover:text-slate-400 hover:bg-white/5' }}">
            <i class="fas fa-chart-pie w-6"></i> Dashboard
        </a>
        
        <a href="{{ route('barang') }}" class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->is('barang*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20' : 'text-slate-500 hover:text-slate-400 hover:bg-white/5' }}">
            <i class="fas fa-boxes-stacked w-6"></i> Data Barang
        </a>
        
        <a href="{{ route('peminjaman') }}" class="flex items-center px-4 py-3 rounded-xl transition-all {{ request()->is('peminjaman*') ? 'bg-indigo-600/10 text-indigo-400 font-medium border border-indigo-500/20' : 'text-slate-500 hover:text-slate-400 hover:bg-white/5' }}">
            <i class="fas fa-hand-holding-dollar w-6"></i> Peminjaman
        </a>
    </nav>
    
    <div class="px-4 py-6 border-t border-gray-300 flex items-center gap-3">
        <a class="font-semibold text-rose-600" href="{{ route('logout') }}">Logout<i class="fa-solid fa-arrow-right-from-bracket ms-2"></i></a>
    </div>
</aside>