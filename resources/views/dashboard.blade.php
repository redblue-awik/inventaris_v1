@extends('layouts.app')

@section('title', 'Dashboard')
@section('header_title', 'Ringkasan Sistem')
@section('header_subtitle', 'Pantau aktivitas inventaris Anda hari ini.')

@section('content')

{{-- ===== KARTU STATISTIK ===== --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex justify-between items-start">
        <div>
            <p class="text-xs font-medium text-slate-500 mb-1">Total Aset Barang</p>
            <h3 class="text-3xl font-bold text-slate-800">{{ $totalBarang ?? '1,248' }}</h3>
            <p class="text-xs text-emerald-500 mt-1">+12 bulan ini</p>
        </div>
        <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
            <i class="fas fa-box text-lg"></i>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex justify-between items-start">
        <div>
            <p class="text-xs font-medium text-slate-500 mb-1">Barang Masuk</p>
            <h3 class="text-3xl font-bold text-slate-800">{{ $barangMasuk ?? '84' }}</h3>
            <p class="text-xs text-emerald-500 mt-1">+5 hari ini</p>
        </div>
        <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
            <i class="fas fa-arrow-down text-lg"></i>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex justify-between items-start">
        <div>
            <p class="text-xs font-medium text-slate-500 mb-1">Barang Keluar</p>
            <h3 class="text-3xl font-bold text-slate-800">{{ $barangKeluar ?? '37' }}</h3>
            <p class="text-xs text-red-400 mt-1">−2 dari kemarin</p>
        </div>
        <div class="p-2.5 bg-red-50 text-red-500 rounded-xl">
            <i class="fas fa-arrow-up text-lg"></i>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex justify-between items-start">
        <div>
            <p class="text-xs font-medium text-slate-500 mb-1">Stok Menipis</p>
            <h3 class="text-3xl font-bold text-slate-800">{{ $stokKritis ?? '6' }}</h3>
            <p class="text-xs text-amber-500 mt-1">Perlu restock</p>
        </div>
        <div class="p-2.5 bg-amber-50 text-amber-500 rounded-xl">
            <i class="fas fa-triangle-exclamation text-lg"></i>
        </div>
    </div>

</div>

{{-- ===== GRAFIK DISTRIBUSI + PERINGATAN STOK ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Grafik Bar Kategori --}}
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-5 border border-slate-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-slate-800 text-base">Distribusi Stok per Kategori</h3>
            <span class="text-xs text-slate-400 bg-slate-50 px-2.5 py-1 rounded-full">
                <i class="far fa-chart-bar mr-1"></i>realtime
            </span>
        </div>
        <div style="height: 240px;">
            <canvas id="kategoriChart"></canvas>
        </div>
    </div>

    {{-- Peringatan Stok --}}
    <div class="bg-white rounded-2xl shadow-sm p-5 border border-slate-100 flex flex-col">
        <div class="flex items-center gap-2 mb-4">
            <i class="fas fa-bell text-blue-600"></i>
            <h3 class="font-semibold text-slate-800 text-base">Peringatan Stok</h3>
        </div>
        <ul class="space-y-3 flex-1" id="lowStockList">
            {{-- Diisi oleh JS --}}
        </ul>
        <div class="mt-4 pt-3 border-t border-blue-200/50">
            <a href="{{ route('barang') }}" class="text-blue-700 text-sm font-medium flex items-center gap-1 hover:underline">
                Lihat semua <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>

</div>

{{-- ===== AKTIVITAS TERKINI + STATUS STOK KATEGORI ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

    {{-- Aktivitas Terkini --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h3 class="text-base font-bold text-slate-800 mb-4">Aktivitas Terkini</h3>

        @php
        $activities = $activities ?? [
            ['type' => 'in',   'icon' => 'fa-arrow-down',           'color' => 'emerald', 'title' => 'Laptop Dell XPS masuk',     'meta' => 'Ditambahkan oleh Budi · 2 jam lalu',  'badge' => '+10 Unit'],
            ['type' => 'out',  'icon' => 'fa-arrow-up',             'color' => 'red',     'title' => 'Mouse Logitech MX keluar',   'meta' => 'Diambil oleh Siti · 4 jam lalu',      'badge' => '−5 Unit'],
            ['type' => 'edit', 'icon' => 'fa-pen',                  'color' => 'blue',    'title' => 'Monitor LG 27" diperbarui', 'meta' => 'Diedit oleh Admin · 6 jam lalu',      'badge' => 'Edit'],
            ['type' => 'warn', 'icon' => 'fa-triangle-exclamation', 'color' => 'amber',   'title' => 'Kertas A4 hampir habis',     'meta' => 'Stok tersisa 12 rim · 8 jam lalu',   'badge' => 'Kritis'],
            ['type' => 'in',   'icon' => 'fa-arrow-down',           'color' => 'emerald', 'title' => 'Kursi ergonomis masuk',      'meta' => 'Ditambahkan oleh Andi · kemarin',     'badge' => '+3 Unit'],
        ];

        $badgeColor = [
            'emerald' => 'bg-emerald-50 text-emerald-700',
            'red'     => 'bg-red-50 text-red-600',
            'blue'    => 'bg-blue-50 text-blue-700',
            'amber'   => 'bg-amber-50 text-amber-700',
        ];
        @endphp

        <div class="space-y-1">
            @foreach($activities as $act)
            <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-slate-50' : '' }}">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-{{ $act['color'] }}-100 text-{{ $act['color'] }}-600 flex items-center justify-center text-sm flex-shrink-0">
                        <i class="fas {{ $act['icon'] }}"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-800">{{ $act['title'] }}</p>
                        <p class="text-xs text-slate-400">{{ $act['meta'] }}</p>
                    </div>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $badgeColor[$act['color']] }}">
                    {{ $act['badge'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Status Stok Kategori --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h3 class="text-base font-bold text-slate-800 mb-4">Status Stok Kategori</h3>

        @php
        $stocks = $stocks ?? [
            ['label' => 'Elektronik', 'pct' => 82, 'color' => 'bg-blue-500'],
            ['label' => 'Furniture',  'pct' => 67, 'color' => 'bg-emerald-500'],
            ['label' => 'ATK',        'pct' => 24, 'color' => 'bg-red-500'],
            ['label' => 'Peralatan',  'pct' => 55, 'color' => 'bg-amber-500'],
            ['label' => 'Kendaraan',  'pct' => 90, 'color' => 'bg-teal-500'],
        ];
        @endphp

        <div class="space-y-4">
            @foreach($stocks as $s)
            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-600 w-24 shrink-0">{{ $s['label'] }}</span>
                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full {{ $s['color'] }}" style="width: {{ $s['pct'] }}%"></div>
                </div>
                <span class="text-xs text-slate-500 w-8 text-right">{{ $s['pct'] }}%</span>
            </div>
            @endforeach
        </div>

        <div class="mt-6 pt-5 border-t border-slate-50">
            <h4 class="text-sm font-bold text-slate-700 mb-3">Gudang Aktif</h4>
            <div class="space-y-2">
                @php
                $gudang = $gudang ?? [
                    ['Gudang Utama',    'Jakarta'],
                    ['Gudang Cabang 1', 'Bandung'],
                    ['Gudang Cabang 2', 'Surabaya'],
                ];
                @endphp
                @foreach($gudang as [$name, $city])
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">{{ $name }}</span>
                    <span class="font-medium text-slate-700">{{ $city }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

{{-- ===== PERSIAPAN DATA CHART (fallback jika controller tidak kirim $chartData) ===== --}}
@php
$chartData = $chartData ?? [
    ['nama' => 'Laptop ThinkPad',     'kategori' => 'Elektronik', 'jumlah' => 8,  'stok_min' => 2],
    ['nama' => 'Monitor 24 Inch',     'kategori' => 'Elektronik', 'jumlah' => 12, 'stok_min' => 3],
    ['nama' => 'Mouse Wireless',      'kategori' => 'Peripheral', 'jumlah' => 30, 'stok_min' => 5],
    ['nama' => 'Keyboard Mechanical', 'kategori' => 'Peripheral', 'jumlah' => 25, 'stok_min' => 5],
    ['nama' => 'Proyektor Epson',     'kategori' => 'Elektronik', 'jumlah' => 4,  'stok_min' => 2],
    ['nama' => 'Webcam HD',           'kategori' => 'Elektronik', 'jumlah' => 3,  'stok_min' => 2],
];
@endphp

{{-- ===== SCRIPTS ===== --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    {{-- ---- Data Barang (sudah disiapkan di @php block di atas) ---- --}}
    const barangData = @json($chartData);

    {{-- ---- Chart Distribusi Stok per Kategori ---- --}}
    const kategoriMap = new Map();
    barangData.forEach(item => {
        kategoriMap.set(item.kategori, (kategoriMap.get(item.kategori) || 0) + item.jumlah);
    });

    new Chart(document.getElementById('kategoriChart'), {
        type: 'bar',
        data: {
            labels: Array.from(kategoriMap.keys()),
            datasets: [{
                label: 'Jumlah Stok',
                data: Array.from(kategoriMap.values()),
                backgroundColor: ['#3b82f6', '#8b5cf6', '#10b981', '#f59e0b', '#ef4444', '#06b6d4'],
                borderRadius: 8,
                barPercentage: 0.65,
                categoryPercentage: 0.8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } },
                tooltip: { backgroundColor: '#1f2937' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    title: { display: true, text: 'Jumlah Unit', font: { size: 11 } }
                },
                x: { grid: { display: false }, ticks: { font: { size: 11 } } }
            }
        }
    });

    {{-- ---- Peringatan Stok Menipis ---- --}}
    const lowItems = barangData.filter(item => item.jumlah <= (item.stok_min ?? 5));
    const lowStockList = document.getElementById('lowStockList');

    if (lowItems.length === 0) {
        lowStockList.innerHTML = `
            <li class="flex items-center gap-2 text-sm text-slate-500">
                <i class="fas fa-check-circle text-emerald-500"></i> Semua stok aman
            </li>`;
    } else {
        lowStockList.innerHTML = lowItems.map(item => `
            <li class="flex justify-between items-center text-sm">
                <span class="flex items-center gap-2 text-slate-700">
                    <i class="fas fa-exclamation-triangle text-amber-500"></i>${item.nama}
                </span>
                <span class="font-bold text-amber-600">${item.jumlah} tersisa</span>
            </li>
        `).join('');
    }

});
</script>
@endpush

@endsection
