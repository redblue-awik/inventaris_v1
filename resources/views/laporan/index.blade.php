@extends('layouts.app')

@section('title', 'Laporan')
@section('header_title', 'Laporan Inventaris')
@section('header_subtitle', 'Analisis lengkap data inventaris, mutasi, dan permintaan barang.')

@push('styles')
<style>
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    }
    .badge-status-menunggu   { background: #fef3c7; color: #92400e; }
    .badge-status-disetujui  { background: #d1fae5; color: #065f46; }
    .badge-status-ditolak    { background: #fee2e2; color: #991b1b; }
    .badge-status-diserahkan { background: #dbeafe; color: #1e40af; }
    .badge-tipe-masuk        { background: #d1fae5; color: #065f46; }
    .badge-tipe-keluar       { background: #fee2e2; color: #991b1b; }
    .badge-tipe-opname       { background: #e0e7ff; color: #3730a3; }
    .badge-tipe-transfer     { background: #fef3c7; color: #92400e; }
    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        border-left: 4px solid #4f46e5;
        padding-left: 0.75rem;
        margin-bottom: 1rem;
    }
</style>
@endpush

@section('content')

{{-- ===================== FILTER PERIODE ===================== --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mb-6">
    <form method="GET" action="{{ route('laporan') }}" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-[180px]">
            <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-wide">Periode</label>
            <select name="periode" id="periodeSelect"
                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                @foreach([
                    'hari_ini'   => 'Hari Ini',
                    'minggu_ini' => 'Minggu Ini',
                    'bulan_ini'  => 'Bulan Ini',
                    'bulan_lalu' => 'Bulan Lalu',
                    'tahun_ini'  => 'Tahun Ini',
                    'custom'     => 'Rentang Custom',
                ] as $val => $label)
                    <option value="{{ $val }}" {{ $periode === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- Custom range (hidden by default) --}}
        <div id="customRange" class="flex gap-3 {{ $periode === 'custom' ? '' : 'hidden' }}">
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-wide">Dari</label>
                <input type="date" name="start" value="{{ request('start', now()->startOfMonth()->toDateString()) }}"
                    class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-wide">Sampai</label>
                <input type="date" name="end" value="{{ request('end', now()->toDateString()) }}"
                    class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            </div>
        </div>

        <div class="flex gap-2">
            <button type="submit"
                class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                <i class="fas fa-filter"></i> Terapkan
            </button>
            <a href="{{ route('laporan.export_pdf', request()->query()) }}"
                class="flex items-center gap-2 px-5 py-2.5 bg-rose-600 text-white text-sm font-semibold rounded-xl hover:bg-rose-700 transition-colors">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </form>

    {{-- Info rentang tanggal aktif --}}
    <p class="text-xs text-slate-400 mt-3">
        <i class="fas fa-calendar-range mr-1"></i>
        Menampilkan data dari
        <span class="font-semibold text-slate-600">{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}</span>
        sampai
        <span class="font-semibold text-slate-600">{{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</span>
    </p>
</div>

{{-- ===================== KARTU STATISTIK ===================== --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
        $stats = [
            ['label' => 'Total Barang', 'value' => $totalBarang, 'icon' => 'fa-box', 'color' => 'indigo', 'bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'ring' => 'bg-indigo-600'],
            ['label' => 'Stok Menipis', 'value' => $stokMenipis, 'icon' => 'fa-triangle-exclamation', 'color' => 'amber', 'bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'ring' => 'bg-amber-500'],
            ['label' => 'Total Permintaan', 'value' => $totalPermintaan, 'icon' => 'fa-hand-holding-dollar', 'color' => 'sky', 'bg' => 'bg-sky-50', 'text' => 'text-sky-600', 'ring' => 'bg-sky-500'],
            ['label' => 'Diserahkan', 'value' => $permintaanDiserahkan, 'icon' => 'fa-circle-check', 'color' => 'emerald', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'ring' => 'bg-emerald-500'],
        ];
    @endphp

    @foreach($stats as $s)
    <div class="stat-card bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl {{ $s['bg'] }} flex items-center justify-center shrink-0">
            <i class="fas {{ $s['icon'] }} text-xl {{ $s['text'] }}"></i>
        </div>
        <div>
            <p class="text-xs text-slate-500 font-medium">{{ $s['label'] }}</p>
            <p class="text-2xl font-bold text-slate-800">{{ number_format($s['value']) }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- ===================== ROW CHARTS ===================== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

    {{-- Chart Bar: Mutasi Masuk vs Keluar --}}
    <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <p class="section-title">Grafik Mutasi Barang (Masuk vs Keluar)</p>
        @if(count($chartLabels) > 0)
            <canvas id="chartMutasi" height="110"></canvas>
        @else
            <div class="flex flex-col items-center justify-center h-40 text-slate-400">
                <i class="fas fa-chart-bar text-4xl mb-2 opacity-30"></i>
                <p class="text-sm">Tidak ada data mutasi pada periode ini</p>
            </div>
        @endif
    </div>

    {{-- Chart Donut: Status Permintaan --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <p class="section-title">Status Permintaan</p>
        @if($statusPermintaan->sum() > 0)
            <canvas id="chartStatus" height="160"></canvas>
            <div class="mt-4 space-y-2">
                @php
                    $statusColors = ['menunggu'=>'#f59e0b','disetujui'=>'#10b981','ditolak'=>'#ef4444','diserahkan'=>'#6366f1'];
                    $statusLabel  = ['menunggu'=>'Menunggu','disetujui'=>'Disetujui','ditolak'=>'Ditolak','diserahkan'=>'Diserahkan'];
                @endphp
                @foreach($statusPermintaan as $status => $count)
                <div class="flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full inline-block" style="background:{{ $statusColors[$status] ?? '#94a3b8' }}"></span>
                        <span class="text-slate-600">{{ $statusLabel[$status] ?? ucfirst($status) }}</span>
                    </div>
                    <span class="font-semibold text-slate-800">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center h-40 text-slate-400">
                <i class="fas fa-chart-pie text-4xl mb-2 opacity-30"></i>
                <p class="text-sm">Tidak ada data permintaan</p>
            </div>
        @endif
    </div>
</div>

{{-- Chart Bar Horizontal: Barang per Kategori --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
    <p class="section-title">Jumlah Barang per Kategori</p>
    @if($barangPerKategori->count() > 0)
        <canvas id="chartKategori" height="60"></canvas>
    @else
        <div class="flex flex-col items-center justify-center h-24 text-slate-400">
            <p class="text-sm">Belum ada data kategori</p>
        </div>
    @endif
</div>

{{-- ===================== TABEL: TOP BARANG KELUAR ===================== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <p class="section-title">Top 5 Barang Paling Banyak Keluar</p>
        @if($topBarangKeluar->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-slate-500 uppercase tracking-wide border-b border-slate-100">
                        <th class="pb-3 text-left font-semibold">#</th>
                        <th class="pb-3 text-left font-semibold">Barang</th>
                        <th class="pb-3 text-right font-semibold">Jml Keluar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($topBarangKeluar as $i => $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3 text-slate-400 font-bold">{{ $i + 1 }}</td>
                        <td class="py-3">
                            <p class="font-semibold text-slate-700">{{ $item->barang->nama_barang ?? '-' }}</p>
                            <p class="text-xs text-slate-400">{{ $item->barang->kode_barang ?? '-' }}</p>
                        </td>
                        <td class="py-3 text-right">
                            <span class="font-bold text-rose-600">{{ number_format($item->total_keluar) }}</span>
                            <span class="text-xs text-slate-400 ml-1">{{ $item->barang->satuan ?? '' }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="flex flex-col items-center justify-center h-32 text-slate-400">
                <i class="fas fa-box-open text-3xl mb-2 opacity-30"></i>
                <p class="text-sm">Belum ada data barang keluar</p>
            </div>
        @endif
    </div>

    {{-- Barang Stok Kritis --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <p class="section-title mb-0" style="margin-bottom:0">Barang Stok Kritis</p>
            @if($stokMenipis > 0)
            <span class="text-xs font-semibold bg-rose-100 text-rose-600 px-3 py-1 rounded-full">
                {{ $stokMenipis }} item
            </span>
            @endif
        </div>
        @if($barangKritis->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-xs text-slate-500 uppercase tracking-wide border-b border-slate-100">
                        <th class="pb-3 text-left font-semibold">Barang</th>
                        <th class="pb-3 text-right font-semibold">Stok</th>
                        <th class="pb-3 text-right font-semibold">Min</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($barangKritis as $barang)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3">
                            <p class="font-semibold text-slate-700">{{ $barang->nama_barang }}</p>
                            <p class="text-xs text-slate-400">{{ $barang->kategori->nama_kategori ?? '-' }}</p>
                        </td>
                        <td class="py-3 text-right">
                            <span class="font-bold {{ $barang->stok_saat_ini == 0 ? 'text-rose-700' : 'text-amber-600' }}">
                                {{ $barang->stok_saat_ini }}
                            </span>
                        </td>
                        <td class="py-3 text-right text-slate-500">{{ $barang->stok_minimum }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <div class="flex flex-col items-center justify-center h-32 text-slate-400">
                <i class="fas fa-circle-check text-3xl mb-2 text-emerald-400 opacity-60"></i>
                <p class="text-sm">Semua stok dalam kondisi aman!</p>
            </div>
        @endif
    </div>
</div>

{{-- ===================== TABEL: RIWAYAT MUTASI ===================== --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <p class="section-title mb-0" style="margin-bottom:0">Riwayat Mutasi Barang</p>
        <span class="text-xs text-slate-400">{{ $mutasiTerbaru->total() }} transaksi</span>
    </div>

    @if($mutasiTerbaru->count() > 0)
    <div class="overflow-x-auto">
        <table id="tabelMutasi" class="w-full text-sm">
            <thead>
                <tr class="text-xs text-slate-500 uppercase tracking-wide border-b border-slate-100">
                    <th class="pb-3 text-left font-semibold">Tanggal</th>
                    <th class="pb-3 text-left font-semibold">Barang</th>
                    <th class="pb-3 text-left font-semibold">Tipe</th>
                    <th class="pb-3 text-right font-semibold">Jumlah</th>
                    <th class="pb-3 text-right font-semibold">Stok Sebelum</th>
                    <th class="pb-3 text-right font-semibold">Stok Sesudah</th>
                    <th class="pb-3 text-left font-semibold">Operator</th>
                    <th class="pb-3 text-left font-semibold">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($mutasiTerbaru as $mutasi)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="py-3 text-slate-500 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($mutasi->created_at)->format('d M Y') }}<br>
                        <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($mutasi->created_at)->format('H:i') }}</span>
                    </td>
                    <td class="py-3">
                        <p class="font-semibold text-slate-700">{{ $mutasi->barang->nama_barang ?? '-' }}</p>
                        <p class="text-xs text-slate-400">{{ $mutasi->barang->kode_barang ?? '-' }}</p>
                    </td>
                    <td class="py-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold badge-tipe-{{ $mutasi->tipe }}">
                            {{ ucfirst($mutasi->tipe) }}
                        </span>
                    </td>
                    <td class="py-3 text-right font-bold {{ in_array($mutasi->tipe, ['masuk','transfer']) ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ in_array($mutasi->tipe, ['masuk','transfer']) ? '+' : '-' }}{{ number_format($mutasi->jumlah) }}
                    </td>
                    <td class="py-3 text-right text-slate-600">{{ number_format($mutasi->stok_sebelum) }}</td>
                    <td class="py-3 text-right font-semibold text-slate-800">{{ number_format($mutasi->stok_sesudah) }}</td>
                    <td class="py-3 text-slate-600">{{ $mutasi->user->name ?? '-' }}</td>
                    <td class="py-3 text-slate-500 max-w-[180px] truncate" title="{{ $mutasi->keterangan }}">
                        {{ $mutasi->keterangan }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 flex justify-end">
        {{ $mutasiTerbaru->appends(request()->query())->links('pagination::tailwind') }}
    </div>
    @else
        <div class="flex flex-col items-center justify-center h-32 text-slate-400">
            <i class="fas fa-box-archive text-3xl mb-2 opacity-30"></i>
            <p class="text-sm">Tidak ada data mutasi pada periode ini</p>
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Toggle custom date range
    const periodeSelect = document.getElementById('periodeSelect');
    const customRange   = document.getElementById('customRange');
    if (periodeSelect) {
        periodeSelect.addEventListener('change', function () {
            customRange.classList.toggle('hidden', this.value !== 'custom');
        });
    }

    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#94a3b8';

    // ====== Chart Mutasi Bar ======
    @if(count($chartLabels) > 0)
    const ctxMutasi = document.getElementById('chartMutasi');
    if (ctxMutasi) {
        new Chart(ctxMutasi, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [
                    {
                        label: 'Masuk',
                        data: {!! json_encode($chartMasuk) !!},
                        backgroundColor: 'rgba(16,185,129,0.75)',
                        borderRadius: 6,
                        borderSkipped: false,
                    },
                    {
                        label: 'Keluar',
                        data: {!! json_encode($chartKeluar) !!},
                        backgroundColor: 'rgba(239,68,68,0.75)',
                        borderRadius: 6,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 12, padding: 16 } },
                    tooltip: { mode: 'index', intersect: false }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { color: '#f1f5f9' }, beginAtZero: true, ticks: { precision: 0 } }
                }
            }
        });
    }
    @endif

    // ====== Chart Status Donut ======
    @if($statusPermintaan->sum() > 0)
    const ctxStatus = document.getElementById('chartStatus');
    if (ctxStatus) {
        const statusData   = {!! json_encode($statusPermintaan->values()) !!};
        const statusLabels = {!! json_encode($statusPermintaan->keys()->map(fn($s) => ucfirst($s))) !!};
        const statusColors = ['#f59e0b', '#10b981', '#ef4444', '#6366f1'];

        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: statusColors.slice(0, statusData.length),
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: {
                        label: (ctx) => ` ${ctx.label}: ${ctx.raw} permintaan`
                    }}
                }
            }
        });
    }
    @endif

    // ====== Chart Kategori Horizontal Bar ======
    @if($barangPerKategori->count() > 0)
    const ctxKategori = document.getElementById('chartKategori');
    if (ctxKategori) {
        const palette = ['#6366f1','#8b5cf6','#ec4899','#f59e0b','#10b981','#06b6d4','#f97316','#3b82f6'];
        new Chart(ctxKategori, {
            type: 'bar',
            data: {
                labels: {!! json_encode($barangPerKategori->pluck('nama_kategori')) !!},
                datasets: [{
                    label: 'Jumlah Barang',
                    data: {!! json_encode($barangPerKategori->pluck('barangs_count')) !!},
                    backgroundColor: palette.slice(0, {{ $barangPerKategori->count() }}),
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: {
                        label: (ctx) => ` ${ctx.raw} jenis barang`
                    }}
                },
                scales: {
                    x: { grid: { color: '#f1f5f9' }, beginAtZero: true, ticks: { precision: 0 } },
                    y: { grid: { display: false } }
                }
            }
        });
    }
    @endif

});
</script>
@endpush