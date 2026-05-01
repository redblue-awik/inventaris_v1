<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;

class LaporanController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::sum('stok');
        $sedangDipinjam = Peminjaman::where('status', 'Dipinjam')->sum('jumlah');
        $tersedia = $totalBarang - $sedangDipinjam;
        $kategoriStok = Barang::selectRaw('kategori, SUM(stok) as total')->groupBy('kategori')->get();
        $peminjamanPerBarang = Peminjaman::with('barang')->get()->groupBy('barang.nama')->map->sum('jumlah');
        $totalPeminjaman = Peminjaman::count();
        $totalKembali = Peminjaman::where('status', 'Dikembalikan')->count();

        return view('laporan.index', compact(
            'totalBarang', 'sedangDipinjam', 'tersedia', 'kategoriStok',
            'peminjamanPerBarang', 'totalPeminjaman', 'totalKembali'
        ));
    }
}