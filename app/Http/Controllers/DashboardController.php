<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\mutasi_barang as MutasiBarang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. KARTU STATISTIK
        $totalBarang = Barang::count();

        // Asumsi kolom 'tipe' berisi 'masuk' atau 'keluar'
        $barangMasuk = MutasiBarang::where('tipe', 'masuk')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('jumlah');

        $barangKeluar = MutasiBarang::where('tipe', 'keluar')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('jumlah');

        // Asumsi ada kolom 'stok_min' untuk batas peringatan
        $stokKritis = Barang::whereColumn('stok_saat_ini', '<=', 'stok_minimum')->count();

        // 2. DATA CHART & PERINGATAN STOK
        // Mengambil barang beserta relasi kategori untuk dikirim ke Javascript
        $chartData = Barang::with('kategori')
            ->get()
            ->map(function ($item) {
                return [
                    'nama' => $item->nama_barang, // Sesuaikan field nama barang
                    'kategori' => $item->kategori ? $item->kategori->nama_kategori : 'Lainnya',
                    'jumlah' => $item->stok_saat_ini,
                    'stok_min' => $item->stok_minimum ?? 5,
                ];
            });

        // 3. AKTIVITAS TERKINI (5 Transaksi Terakhir)
        $mutasiTerbaru = MutasiBarang::with(['barang', 'staff', 'referensi'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($mutasi) {
                $isMasuk = $mutasi->tipe === 'masuk';
                return [
                    'type' => $isMasuk ? 'in' : 'out',
                    'icon' => $isMasuk ? 'fa-arrow-down' : 'fa-arrow-up',
                    'color' => $isMasuk ? 'emerald' : 'red',
                    'title' => ($isMasuk ? 'Barang Masuk: ' : 'Barang Keluar: ') . ($mutasi->barang->nama_barang ?? 'Unknown'),
                    'meta' => 'Oleh ' . ($mutasi->staff->name ?? 'Sistem') . ' · ' . $mutasi->created_at->diffForHumans(),
                    'badge' => ($isMasuk ? '+' : '−') . $mutasi->jumlah . ' Unit',
                ];
            });

        // 4. STATUS STOK KATEGORI (Persentase)
        $totalStokKeseluruhan = Barang::sum('stok_saat_ini');
        $kategoriStok = Kategori::withSum('barang', 'stok_saat_ini')
            ->get()
            ->map(function ($kat) use ($totalStokKeseluruhan) {
                $persentase = $totalStokKeseluruhan > 0 ? round(($kat->barang_sum_stok_saat_ini / $totalStokKeseluruhan) * 100) : 0;

                // Random warna untuk visual (Bisa di-set statis di database jika ada)
                $colors = ['bg-blue-500', 'bg-emerald-500', 'bg-red-500', 'bg-amber-500', 'bg-purple-500'];

                return [
                    'label' => $kat->nama_kategori,
                    'pct' => $persentase,
                    'color' => $colors[array_rand($colors)],
                ];
            })
            ->sortByDesc('pct')
            ->take(5);

        // 5. USER LOGIN - JUMLAH MUTASI
        $user = Auth::user();
        $jumlahMutasiUser = MutasiBarang::where('user_id', $user->id)->count();
        $userInfo = [
            'nama' => $user->name,
            'departemen' => $user->departemen ?? '-',
            'mutasi' => $jumlahMutasiUser,
        ];

        return view('dashboard', [
            'totalBarang' => $totalBarang,
            'barangMasuk' => $barangMasuk,
            'barangKeluar' => $barangKeluar,
            'stokKritis' => $stokKritis,
            'chartData' => $chartData,
            'activities' => $mutasiTerbaru,
            'stocks' => $kategoriStok,
            'userInfo' => $userInfo,
        ]);
    }
}
