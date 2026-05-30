@extends('layouts.app')

@section('title', 'Dashboard')
@section('header_title', 'Ringkasan Sistem')
@section('header_subtitle', 'Pantau aktivitas inventaris Anda secara Real-time.')

@section('content')

    {{-- ===== KARTU STATISTIK ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div
            class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow flex justify-between items-start">
            <div>
                <p class="text-xs font-medium text-slate-500 mb-1">Total Aset Barang</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ number_format($totalBarang ?? 0) }}</h3>
                <p class="text-xs text-slate-400 mt-1">Total jenis barang</p>
            </div>
            <div class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl">
                <i class="fas fa-box text-lg"></i>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow flex justify-between items-start">
            <div>
                <p class="text-xs font-medium text-slate-500 mb-1">Barang Masuk</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ number_format($barangMasuk ?? 0) }}</h3>
                <p class="text-xs text-emerald-500 mt-1">Bulan ini</p>
            </div>
            <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                <i class="fas fa-arrow-down text-lg"></i>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow flex justify-between items-start">
            <div>
                <p class="text-xs font-medium text-slate-500 mb-1">Barang Keluar</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ number_format($barangKeluar ?? 0) }}</h3>
                <p class="text-xs text-red-400 mt-1">Bulan ini</p>
            </div>
            <div class="p-2.5 bg-red-50 text-red-500 rounded-xl">
                <i class="fas fa-arrow-up text-lg"></i>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition-shadow flex justify-between items-start">
            <div>
                <p class="text-xs font-medium text-slate-500 mb-1">Stok Menipis</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ $stokKritis ?? 0 }}</h3>
                <p class="text-xs text-amber-500 mt-1">Perlu restock segera</p>
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
            <div style="height: 240px; position: relative;">
                <canvas id="kategoriChart"></canvas>
            </div>
        </div>

        {{-- Peringatan Stok --}}
        <div class="bg-white rounded-2xl shadow-sm p-5 border border-slate-100 flex flex-col">
            <div class="flex items-center gap-2 mb-4">
                <i class="fas fa-bell text-blue-600"></i>
                <h3 class="font-semibold text-slate-800 text-base">Peringatan Stok</h3>
            </div>
            <ul class="space-y-3 flex-1 overflow-y-auto max-h-56" id="lowStockList">
                {{-- Diisi oleh JS berdasarkan chartData --}}
            </ul>
            <div class="mt-4 pt-3 border-t border-slate-100">
                <a href="{{ route('barang') }}"
                    class="text-blue-600 hover:text-blue-700 text-sm font-medium flex items-center gap-1 transition-colors">
                    Kelola Barang <i class="fas fa-arrow-right text-xs"></i>
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
                $badgeColor = [
                    'emerald' => 'bg-emerald-50 text-emerald-700',
                    'red' => 'bg-red-50 text-red-600',
                    'blue' => 'bg-blue-50 text-blue-700',
                    'amber' => 'bg-amber-50 text-amber-700',
                ];
            @endphp

            <div class="space-y-1">
                @forelse($activities as $act)
                    <div
                        class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-slate-50' : '' }}">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-9 h-9 rounded-full bg-{{ $act['color'] }}-100 text-{{ $act['color'] }}-600 flex items-center justify-center text-sm flex-shrink-0">
                                <i class="fas {{ $act['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-800">{{ $act['title'] }}</p>
                                <p class="text-xs text-slate-400">{{ $act['meta'] }}</p>
                            </div>
                        </div>
                        <span
                            class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $badgeColor[$act['color']] ?? 'bg-slate-100 text-slate-700' }}">
                            {{ $act['badge'] }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-6 text-slate-400 text-sm">
                        Belum ada aktivitas mutasi tercatat.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Status Stok Kategori --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex flex-col">
            <h3 class="text-base font-bold text-slate-800 mb-4">Kapasitas Kategori</h3>

            <div class="space-y-4 flex-1">
                @forelse($stocks as $s)
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-slate-600 w-24 shrink-0 truncate"
                            title="{{ $s['label'] }}">{{ $s['label'] }}</span>
                        <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $s['color'] }}" style="width: {{ $s['pct'] }}%">
                            </div>
                        </div>
                        <span class="text-xs font-medium text-slate-500 w-8 text-right">{{ $s['pct'] }}%</span>
                    </div>
                @empty
                    <div class="text-center py-4 text-slate-400 text-sm">
                        Belum ada data kategori.
                    </div>
                @endforelse
            </div>

            <div class="mt-6 pt-5 border-t border-slate-50">
                <h4 class="text-sm font-bold text-slate-700">Informasi Sistem</h4>
                <div class="space-y-2 mt-3">
                    @if ($userInfo)
                        <div class="flex justify-between items-center text-sm bg-slate-50 px-3 py-2 rounded-lg border border-slate-100">
                            <span class="text-slate-600 flex items-center gap-2">
                                <i class="fas fa-user-tie text-slate-400"></i> 
                                {{ $userInfo['nama'] }}(<span class="text-sm">{{ $userInfo['departemen'] }}</span>)
                            </span>
                            <span class="font-medium text-slate-700 bg-blue-100 px-2 py-1 rounded text-xs">{{ $userInfo['mutasi'] }}</span>
                        </div>
                    @else
                        <div class="text-center py-4 text-slate-400 text-sm">
                            Belum ada user yang login.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ===== SCRIPTS ===== --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Ambil data asli dari Controller
                const barangData = @json($chartData ?? []);

                // Render Chart Kategori
                const kategoriMap = new Map();
                barangData.forEach(item => {
                    kategoriMap.set(item.kategori, (kategoriMap.get(item.kategori) || 0) + item.jumlah);
                });

                const ctx = document.getElementById('kategoriChart');
                if (ctx && barangData.length > 0) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: Array.from(kategoriMap.keys()),
                            datasets: [{
                                label: 'Total Unit',
                                data: Array.from(kategoriMap.values()),
                                backgroundColor: ['#4f46e5', '#8b5cf6', '#0ea5e9', '#10b981', '#f59e0b',
                                    '#f43f5e'
                                ],
                                borderRadius: 6,
                                barPercentage: 0.6,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: '#1e293b',
                                    padding: 10,
                                    cornerRadius: 8
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: '#f1f5f9'
                                    },
                                    border: {
                                        dash: [4, 4]
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                }

                // Render Peringatan Stok
                const lowItems = barangData.filter(item => item.jumlah <= item.stok_min);
                const lowStockList = document.getElementById('lowStockList');

                if (lowStockList) {
                    if (lowItems.length === 0) {
                        lowStockList.innerHTML = `
                <li class="flex items-center justify-center gap-2 text-sm text-emerald-600 bg-emerald-50 p-4 rounded-xl border border-emerald-100">
                    <i class="fas fa-check-circle"></i> Semua stok dalam kondisi aman
                </li>`;
                    } else {
                        lowStockList.innerHTML = lowItems.map(item => `
                <li class="flex justify-between items-center text-sm p-3 bg-red-50/50 rounded-xl border border-red-100">
                    <span class="flex items-center gap-2 text-slate-700 font-medium">
                        <i class="fas fa-exclamation-circle text-red-500"></i> ${item.nama}
                    </span>
                    <span class="font-bold text-red-600 bg-red-100 px-2.5 py-1 rounded-md">${item.jumlah} tersisa</span>
                </li>
            `).join('');
                    }
                }
            });
        </script>
    @endpush

@endsection
