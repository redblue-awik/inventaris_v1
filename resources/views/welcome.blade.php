<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Inventaris</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-50 text-slate-800">

    <div class="flex h-screen overflow-hidden">
        
        <aside class="w-72 bg-indigo-950 text-white flex flex-col hidden md:flex">
            <div class="h-20 flex items-center px-8 border-b border-indigo-900/50">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-indigo-600/20">
                    <i class="fas fa-cube text-xl"></i>
                </div>
                <span class="font-bold text-xl tracking-wide">NexStock</span>
            </div>
            <nav class="flex-1 px-4 py-8 space-y-2">
                <a href="index.html" class="flex items-center px-4 py-3 bg-indigo-600/10 text-indigo-400 rounded-xl font-medium border border-indigo-500/20">
                    <i class="fas fa-chart-pie w-6"></i> Dashboard
                </a>
                <a href="barang.html" class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl transition-all">
                    <i class="fas fa-boxes-stacked w-6"></i> Data Barang
                </a>
                <a href="peminjaman.html" class="flex items-center px-4 py-3 text-slate-400 hover:text-white hover:bg-white/5 rounded-xl transition-all">
                    <i class="fas fa-hand-holding-hand w-6"></i> Peminjaman
                </a>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-y-auto">
            
            <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-8 sticky top-0 z-10">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">Ringkasan Sistem</h1>
                    <p class="text-sm text-slate-500">Pantau aktivitas inventaris Anda hari ini.</p>
                </div>
                <div class="flex items-center space-x-6">
                    <button class="relative text-slate-400 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 w-3 h-3 bg-rose-500 rounded-full border-2 border-white"></span>
                    </button>
                    <div class="flex items-center gap-3 pl-6 border-l border-slate-200">
                        <div class="text-right hidden md:block">
                            <p class="text-sm font-semibold text-slate-700">Admin Utama</p>
                            <p class="text-xs text-slate-500">admin@nexstock.com</p>
                        </div>
                        <img src="https://ui-avatars.com/api/?name=Admin+Utama&background=6366f1&color=fff" class="w-10 h-10 rounded-full shadow-sm" alt="Profile">
                    </div>
                </div>
            </header>

            <main class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-slate-500 mb-1">Total Aset Barang</p>
                                <h3 class="text-3xl font-bold text-slate-800">1,248</h3>
                            </div>
                            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                                <i class="fas fa-box text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-emerald-500 font-medium flex items-center"><i class="fas fa-arrow-up mr-1"></i> 12%</span>
                            <span class="text-slate-400 ml-2">dari bulan lalu</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-slate-500 mb-1">Barang Dipinjam</p>
                                <h3 class="text-3xl font-bold text-slate-800">56</h3>
                            </div>
                            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl">
                                <i class="fas fa-people-carry text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-amber-500 font-medium">14 Menunggu Persetujuan</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-slate-500 mb-1">Peringatan Stok</p>
                                <h3 class="text-3xl font-bold text-rose-600">8</h3>
                            </div>
                            <div class="p-3 bg-rose-50 text-rose-600 rounded-xl">
                                <i class="fas fa-triangle-exclamation text-xl"></i>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-rose-500 font-medium">Segera lakukan *restock*</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Aktivitas Terkini</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-slate-50">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="fas fa-arrow-down"></i></div>
                                <div>
                                    <p class="font-medium text-slate-800">Barang Masuk: Laptop Dell XPS</p>
                                    <p class="text-xs text-slate-500">Ditambahkan oleh Budi - 2 jam yang lalu</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-emerald-600">+10 Unit</span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center"><i class="fas fa-hand-holding"></i></div>
                                <div>
                                    <p class="font-medium text-slate-800">Peminjaman: Proyektor Epson</p>
                                    <p class="text-xs text-slate-500">Dipinjam oleh Divisi Marketing - 5 jam yang lalu</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-slate-600">-1 Unit</span>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>