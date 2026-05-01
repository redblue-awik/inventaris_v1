<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>InventarisPro | Dashboard Manajemen Aset</title>
    
    <!-- TailwindCSS + Fonts + Icons + Chart.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f4f7fc; }
        /* transisi halus */
        .card-hover {
            transition: all 0.2s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.1);
        }
        .table-row-hover:hover {
            background-color: #f9fafb;
            transition: 0.1s;
        }
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #e2e8f0;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 10px;
        }
        .search-focus:focus {
            box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
            border-color: #3b82f6;
        }
        .sidebar-item {
            transition: all 0.2s;
        }
        .sidebar-item:hover {
            background: #eef2ff;
            color: #1e40af;
            border-radius: 12px;
        }
        .sidebar-active {
            background: #eef2ff;
            color: #1e3a8a;
            border-radius: 12px;
            font-weight: 500;
        }
    </style>
</head>
<body class="antialiased">

<div class="flex flex-col lg:flex-row min-h-screen">
    
    <!-- SIDEBAR MODERN -->
    <aside class="w-full lg:w-72 bg-white shadow-xl lg:shadow-none lg:border-r border-gray-100 px-5 py-6 flex flex-col gap-6">
        <div class="flex items-center gap-3 px-2">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center shadow-md">
                <i class="fas fa-boxes text-white text-lg"></i>
            </div>
            <div>
                <h1 class="font-bold text-xl text-gray-800 tracking-tight">Inventaris<span class="text-blue-600">Pro</span></h1>
                <p class="text-xs text-gray-400 -mt-0.5">Manajemen Aset & Inventaris</p>
            </div>
        </div>
        
        <nav class="flex-1 mt-4 space-y-1.5">
            <a href="Dashboard" class="sidebar-item sidebar-active flex items-center gap-3 px-4 py-2.5 text-gray-700 font-medium">
                <i class="fas fa-tachometer-alt w-5 text-blue-500"></i>
                <span>Dashboard</span>
            </a>
            <a href="/Barang" class="sidebar-item flex items-center gap-3 px-4 py-2.5 text-gray-500 font-medium">
                <i class="fas fa-cube w-5"></i>
                <span>Barang</span>
            </a>
            <a href="Peminjaman" class="sidebar-item flex items-center gap-3 px-4 py-2.5 text-gray-500 font-medium">
                <i class="fas fa-hand-holding w-5"></i>
                <span>Peminjaman</span>
            </a>
            <a href="/Laporan" class="sidebar-item flex items-center gap-3 px-4 py-2.5 text-gray-500 font-medium">
                <i class="fas fa-chart-line w-5"></i>
                <span>Laporan</span>
            </a>
        </nav>
        
        <div class="pt-6 border-t border-gray-100 mt-auto">
            <div class="flex items-center gap-3 px-2">
                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-blue-500 to-blue-700 flex items-center justify-center text-white font-semibold text-sm shadow-sm">AD</div>
                <div class="text-sm">
                    <p class="font-semibold text-gray-700">Admin User</p>
                    <p class="text-xs text-gray-400">admin@inventaris.com</p>
                </div>
                <i class="fas fa-chevron-down ml-auto text-gray-400 text-xs"></i>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 px-4 md:px-6 py-6 lg:py-8 overflow-x-auto">
        
        <!-- Header + pencarian -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight">Dashboard Inventaris</h2>
                <p class="text-gray-500 text-sm mt-1">Kelola stok, peminjaman, dan pantau aset perusahaan</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" id="searchBarang" placeholder="Cari barang / kategori..." class="pl-9 pr-4 py-2.5 rounded-xl border border-gray-200 w-64 md:w-72 bg-white text-sm focus:outline-none search-focus transition">
                </div>
      <a href="/barang/create" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-xl flex items-center gap-2 shadow-sm transition-all duration-200 text-sm font-medium">
    <i class="fas fa-plus"></i> 
    <span class="hidden sm:inline">Tambah Barang</span>
</a>
                </button>
            </div>
        </div>

        <!-- STATISTIK CARD (dinamis berdasarkan data) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="bg-white rounded-2xl shadow-sm p-5 card-hover border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Barang</p>
                        <p id="totalBarangCard" class="text-3xl font-extrabold text-gray-800 mt-1">--</p>
                        <p class="text-xs text-green-500 mt-2"><i class="fas fa-arrow-up"></i> +2.3% dari bulan lalu</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                        <i class="fas fa-boxes text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-5 card-hover border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Sedang Dipinjam</p>
                        <p id="dipinjamCard" class="text-3xl font-extrabold text-gray-800 mt-1">--</p>
                        <p class="text-xs text-amber-500 mt-2"><i class="fas fa-clock"></i> Aktif saat ini</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center">
                        <i class="fas fa-hand-holding text-amber-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-5 card-hover border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Tersedia</p>
                        <p id="tersediaCard" class="text-3xl font-extrabold text-gray-800 mt-1">--</p>
                        <p class="text-xs text-emerald-500 mt-2"><i class="fas fa-check-circle"></i> Siap pakai</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-emerald-600 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-2xl shadow-sm p-5 card-hover border border-gray-100">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Kategori Aktif</p>
                        <p class="text-3xl font-extrabold text-gray-800 mt-1">6</p>
                        <p class="text-xs text-indigo-500 mt-2"><i class="fas fa-chart-simple"></i> Elektronik, Peripherals</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center">
                        <i class="fas fa-layer-group text-indigo-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRAFIK + INFORMASI -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="font-semibold text-gray-800 text-lg">Distribusi Stok per Kategori</h3>
                    <span class="text-xs text-gray-400 bg-gray-50 px-2 py-1 rounded-full"><i class="far fa-chart-bar"></i> realtime</span>
                </div>
                <canvas id="kategoriChart" height="180" style="max-height: 260px; width: 100%;"></canvas>
            </div>
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl shadow-sm p-5 border border-blue-100">
                <div class="flex items-center gap-2 mb-3">
                    <i class="fas fa-bell text-blue-600"></i>
                    <h3 class="font-semibold text-gray-800">Peringatan Stok</h3>
                </div>
                <ul class="space-y-3 mt-2" id="lowStockList">
                    <!-- JS akan isi barang dengan stok <= 5 -->
                </ul>
                <div class="mt-4 pt-3 border-t border-blue-200/50">
                    <a href="#" class="text-blue-700 text-sm font-medium flex items-center gap-1">Lihat semua <i class="fas fa-arrow-right text-xs"></i></a>
                </div>
            </div>
        </div>
        
        <!-- TABEL UTAMA BARANG + PEMINJAMAN TERBARU (2 KOLOM) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Daftar Barang (colspan 7/8) -->
            <div class="lg:col-span-7 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-5 pt-5 pb-2 flex justify-between items-center flex-wrap gap-2 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2"><i class="fas fa-database text-blue-500"></i> Data Barang</h3>
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full" id="totalItemCount">0 item</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
                            <tr>
                                <th class="px-5 py-3 text-left">Nama Barang</th>
                                <th class="px-5 py-3 text-left">Kategori</th>
                                <th class="px-5 py-3 text-center">Jumlah</th>
                                <th class="px-5 py-3 text-center">Status</th>
                                <th class="px-5 py-3 text-center">Aksi</th>
                             </tr>
                        </thead>
                        <tbody id="barangTableBody">
                            <!-- data akan di inject via JS (agar dinamis stat & grafik) -->
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-3 bg-gray-50/40 border-t text-xs text-gray-500 flex justify-between">
                    <span><i class="far fa-edit"></i> Klik ikon untuk edit/hapus simulasi</span>
                    <span><i class="fas fa-filter"></i> filter pencarian realtime</span>
                </div>
            </div>
            
            <!-- Peminjaman Terbaru (colspan 5) -->
            <div class="lg:col-span-5 bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="px-5 pt-5 pb-2 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2"><i class="fas fa-exchange-alt text-amber-500"></i> Peminjaman Aktif</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Status terbaru peminjaman aset</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-medium">
                            <tr>
                                <th class="px-5 py-3 text-left">Peminjam</th>
                                <th class="px-5 py-3 text-left">Barang</th>
                                <th class="px-5 py-3 text-left">Tgl Pinjam</th>
                                <th class="px-5 py-3 text-center">Status</th>
                             </tr>
                        </thead>
                        <tbody id="peminjamanTableBody">
                            <!-- data peminjaman static + dinamis untuk hitung dipinjam -->
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-3 text-right border-t">
                    <button class="text-blue-600 text-xs font-medium hover:underline">Lihat semua transaksi →</button>
                </div>
            </div>
        </div>
        
        <footer class="mt-10 text-center text-gray-400 text-xs pb-4">
            <i class="far fa-calendar-alt"></i> Data real-time simulasi | InventarisPro Dashboard v2.0
        </footer>
    </main>
</div>

<script>
    // ======================= DATA MASTER ==========================
    const barangData = [
        { nama: "Laptop ThinkPad", kategori: "Elektronik", jumlah: 8, stok_min: 2 },
        { nama: "Monitor 24 Inch", kategori: "Elektronik", jumlah: 12, stok_min: 3 },
        { nama: "Mouse Wireless", kategori: "Peripheral", jumlah: 30, stok_min: 5 },
        { nama: "Keyboard Mechanical", kategori: "Peripheral", jumlah: 25, stok_min: 5 },
        { nama: "Proyektor Epson", kategori: "Elektronik", jumlah: 4, stok_min: 2 },
        { nama: "Webcam HD", kategori: "Elektronik", jumlah: 3, stok_min: 2 }
    ];
    
    // Data peminjaman (status "Dipinjam" menentukan jumlah aktif)
    const peminjamanData = [
        { peminjam: "Andi Setiawan", barang: "Laptop ThinkPad", tgl: "12/05/2025", status: "Dipinjam" },
        { peminjam: "Budi Santoso", barang: "Proyektor Epson", tgl: "14/05/2025", status: "Dipinjam" },
        { peminjam: "Citra Kirana", barang: "Monitor 24 Inch", tgl: "10/05/2025", status: "Dikembalikan" },
        { peminjam: "Dina Fitriani", barang: "Webcam HD", tgl: "16/05/2025", status: "Dipinjam" },
        { peminjam: "Eka Pratama", barang: "Keyboard Mechanical", tgl: "09/05/2025", status: "Dipinjam" }
    ];

    // Hitung aktif dipinjam (status "Dipinjam")
    function getJumlahDipinjam() {
        return peminjamanData.filter(p => p.status === "Dipinjam").length;
    }

    // Hitung total barang (sum jumlah dari barangData)
    function getTotalBarang() {
        return barangData.reduce((acc, item) => acc + item.jumlah, 0);
    }

    // Perbarui semua statistik card & peringatan stok
    function updateStatistics() {
        const total = getTotalBarang();
        const dipinjam = getJumlahDipinjam();
        const tersedia = total - dipinjam;
        document.getElementById('totalBarangCard').innerText = total;
        document.getElementById('dipinjamCard').innerText = dipinjam;
        document.getElementById('tersediaCard').innerText = tersedia;
    }

    // Render tabel barang + low stock alert (berdasarkan jumlah stok <= stok_min atau <=5)
    function renderBarangTable(filterText = "") {
        const tbody = document.getElementById('barangTableBody');
        const filterLower = filterText.toLowerCase();
        const filtered = barangData.filter(item => 
            item.nama.toLowerCase().includes(filterLower) || 
            item.kategori.toLowerCase().includes(filterLower)
        );
        
        document.getElementById('totalItemCount').innerText = filtered.length + " item";
        
        if(filtered.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-8 text-gray-400">Tidak ada barang ditemukan</td></tr>`;
            return;
        }
        
        let html = "";
        filtered.forEach((item, idx) => {
            let statusBadge = "";
            let statusText = "";
            if(item.jumlah <= 2) {
                statusBadge = "bg-red-100 text-red-700";
                statusText = "Stok Kritis";
            } else if(item.jumlah <= 5) {
                statusBadge = "bg-amber-100 text-amber-700";
                statusText = "Stok Menipis";
            } else {
                statusBadge = "bg-emerald-100 text-emerald-700";
                statusText = "Tersedia";
            }
            html += `<tr class="table-row-hover border-b border-gray-50">
                        <td class="px-5 py-3 font-medium text-gray-800">${item.nama}</td>
                        <td class="px-5 py-3 text-gray-500"><span class="bg-gray-100 px-2 py-0.5 rounded-full text-xs">${item.kategori}</span></td>
                        <td class="px-5 py-3 text-center font-semibold">${item.jumlah}</td>
                        <td class="px-5 py-3 text-center"><span class="text-xs px-2 py-1 rounded-full ${statusBadge}">${statusText}</span></td>
                        <td class="px-5 py-3 text-center">
                            <div class="flex justify-center gap-2 text-gray-500">
                                <i class="fas fa-edit hover:text-blue-600 cursor-pointer transition" title="Edit (simulasi)"></i>
                                <i class="fas fa-trash-alt hover:text-red-500 cursor-pointer transition" title="Hapus"></i>
                            </div>
                        </td>
                     </tr>`;
        });
        tbody.innerHTML = html;
        
        // Low Stock Peringatan (semua barang dengan jumlah <= stok_min atau <=5)
        const lowStockItems = barangData.filter(item => item.jumlah <= (item.stok_min || 5));
        const lowStockContainer = document.getElementById('lowStockList');
        if(lowStockItems.length === 0) {
            lowStockContainer.innerHTML = '<li class="text-sm text-gray-500"><i class="fas fa-check-circle text-green-500"></i> Semua stok aman</li>';
        } else {
            lowStockContainer.innerHTML = lowStockItems.map(item => 
                `<li class="flex justify-between items-center text-sm"><span><i class="fas fa-exclamation-triangle text-amber-500 mr-2"></i>${item.nama}</span><span class="font-bold text-amber-600">${item.jumlah} tersisa</span></li>`
            ).join('');
        }
    }

    // Render tabel peminjaman
    function renderPeminjamanTable() {
        const tbody = document.getElementById('peminjamanTableBody');
        const activeLoans = peminjamanData.filter(p => p.status === "Dipinjam");
        // tampilkan semua untuk transparansi, tetapi badge status
        let html = "";
        peminjamanData.forEach(p => {
            let badgeClass = p.status === "Dipinjam" ? "bg-amber-100 text-amber-700" : "bg-gray-100 text-gray-600";
            html += `<tr class="table-row-hover border-b border-gray-50">
                        <td class="px-5 py-3 font-medium text-gray-700">${p.peminjam}</td>
                        <td class="px-5 py-3 text-gray-500">${p.barang}</td>
                        <td class="px-5 py-3 text-gray-500 text-xs">${p.tgl}</td>
                        <td class="px-5 py-3 text-center"><span class="text-xs px-2 py-1 rounded-full ${badgeClass}">${p.status}</span></td>
                     </tr>`;
        });
        tbody.innerHTML = html;
    }

    // Chart kategori menggunakan Chart.js (aggregate)
    let chartInstance = null;
    function renderChart() {
        const ctx = document.getElementById('kategoriChart').getContext('2d');
        // agregat jumlah berdasarkan kategori
        const kategoriMap = new Map();
        barangData.forEach(item => {
            const kat = item.kategori;
            const jumlah = item.jumlah;
            kategoriMap.set(kat, (kategoriMap.get(kat) || 0) + jumlah);
        });
        const labels = Array.from(kategoriMap.keys());
        const dataValues = Array.from(kategoriMap.values());
        
        if(chartInstance) chartInstance.destroy();
        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Stok',
                    data: dataValues,
                    backgroundColor: ['#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444', '#06b6d4'],
                    borderRadius: 8,
                    barPercentage: 0.7,
                    categoryPercentage: 0.8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } },
                    tooltip: { backgroundColor: '#1f2937' }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#e5e7eb' }, title: { display: true, text: 'Jumlah Unit', font: { size: 11 } } },
                    x: { grid: { display: false }, ticks: { font: { size: 11 } } }
                }
            }
        });
    }
    
    // Fungsi sinkronisasi semua komponen data & UI setelah filter
    function refreshAllData() {
        updateStatistics();      // card total, dipinjam, tersedia
        renderPeminjamanTable(); // tabel peminjaman
        renderChart();           // chart kategori
        // lowStockList dan barang table akan diupdate oleh filter saat ini (ambil nilai input)
        const currentSearch = document.getElementById('searchBarang').value;
        renderBarangTable(currentSearch);
    }
    
    // Event listener search live
    const searchInput = document.getElementById('searchBarang');
    searchInput.addEventListener('input', (e) => {
        renderBarangTable(e.target.value);
    });
    
 
    
    // Untuk aksi edit/hapus via event delegation (simulasi)
    document.getElementById('barangTableBody')?.addEventListener('click', (e) => {
        const target = e.target;
        if(target.classList.contains('fa-edit')) {
            alert("✏️ Demo: Edit barang (fungsionalitas siap untuk backend).");
        } else if(target.classList.contains('fa-trash-alt')) {
            alert("⚠️ Demo: Hapus barang hanya simulasi, data tidak dihapus.");
        }
    });
    
    // Inisialisasi semua tampilan
    function init() {
        refreshAllData();
    }
    
    init();
</script>
</body>
</html>