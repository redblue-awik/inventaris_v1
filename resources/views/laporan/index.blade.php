{{-- resources/views/laporan/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Laporan')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-5 rounded-2xl shadow"><div class="text-blue-600 text-sm">Total Barang</div><div class="text-3xl font-bold">{{ $totalBarang }}</div></div>
        <div class="bg-white p-5 rounded-2xl shadow"><div class="text-amber-600 text-sm">Sedang Dipinjam</div><div class="text-3xl font-bold">{{ $sedangDipinjam }}</div></div>
        <div class="bg-white p-5 rounded-2xl shadow"><div class="text-emerald-600 text-sm">Tersedia</div><div class="text-3xl font-bold">{{ $tersedia }}</div></div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-5 rounded-2xl shadow"><h3 class="font-bold mb-3">Stok per Kategori</h3><canvas id="stokChart" height="200"></canvas></div>
        <div class="bg-white p-5 rounded-2xl shadow"><h3 class="font-bold mb-3">Peminjaman per Barang</h3><canvas id="pinjamChart" height="200"></canvas></div>
    </div>
    <div class="mt-6 bg-white p-5 rounded-2xl shadow"><h3 class="font-bold">Statistik Peminjaman</h3><div class="flex justify-between mt-2"><span>Total Peminjaman:</span><span>{{ $totalPeminjaman }}</span></div><div class="flex justify-between"><span>Selesai Dikembalikan:</span><span>{{ $totalKembali }}</span></div></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const kategoriLabels = @json($kategoriStok->pluck('kategori'));
    const kategoriData = @json($kategoriStok->pluck('total'));
    const pinjamLabels = @json($peminjamanPerBarang->keys());
    const pinjamData = @json($peminjamanPerBarang->values());
    new Chart(document.getElementById('stokChart'), { type: 'bar', data: { labels: kategoriLabels, datasets: [{ label: 'Jumlah Stok', data: kategoriData, backgroundColor: '#3b82f6' }] } });
    new Chart(document.getElementById('pinjamChart'), { type: 'bar', data: { labels: pinjamLabels, datasets: [{ label: 'Total Peminjaman (unit)', data: pinjamData, backgroundColor: '#f59e0b' }] } });
</script>
@endsection