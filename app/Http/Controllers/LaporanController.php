<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Permintaan;
use App\Models\mutasi_barang as MutasiBarang;
use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan utama.
     */
    public function index(Request $request)
    {
        // --- Filter Periode ---
        $periode = $request->get('periode', 'bulan_ini');
        [$startDate, $endDate] = $this->getDateRange($periode, $request);

        // --- Statistik Ringkasan ---
        $totalBarang       = Barang::count();
        $stokMenipis       = Barang::whereColumn('stok_saat_ini', '<=', 'stok_minimum')->count();
        $totalPermintaan      = Permintaan::whereBetween('created_at', [$startDate, $endDate])->count();
        $permintaanDiserahkan = Permintaan::where('status', 'diserahkan')
                                ->whereBetween('created_at', [$startDate, $endDate])->count();

        // --- Chart: Mutasi per Hari (7/30 hari terakhir) ---
        $mutasiHarian = MutasiBarang::selectRaw("DATE(created_at) as tanggal, tipe, SUM(jumlah) as total")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('tanggal', 'tipe')
            ->orderBy('tanggal')
            ->get()
            ->groupBy('tanggal');

        $chartLabels  = [];
        $chartMasuk   = [];
        $chartKeluar  = [];

        foreach ($mutasiHarian as $tanggal => $items) {
            $chartLabels[] = Carbon::parse($tanggal)->translatedFormat('d M');
            $masuk  = $items->where('tipe', 'masuk')->sum('total');
            $keluar = $items->where('tipe', 'keluar')->sum('total');
            $chartMasuk[]  = $masuk;
            $chartKeluar[] = $keluar;
        }

        // --- Chart: Barang per Kategori ---
        $barangPerKategori = Kategori::withCount('barang')
            ->having('barang_count', '>', 0)
            ->get();

        // --- Chart: Status Permintaan (Pie) ---
        // collect() memastikan selalu Collection meski tidak ada data
        $statusPermintaan = collect(
            Permintaan::selectRaw("status, COUNT(*) as total")
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('status')
                ->pluck('total', 'status')
        );

        // --- Tabel: Mutasi Terbaru ---
        $mutasiTerbaru = MutasiBarang::with(['barang', 'staff'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->paginate(10);

        // --- Tabel: Barang Stok Kritis ---
        $barangKritis = Barang::with('kategori')
            ->whereColumn('stok_saat_ini', '<=', 'stok_minimum')
            ->orderBy('stok_saat_ini')
            ->get();

        // --- Tabel: Top 5 Barang Paling Banyak Keluar ---
        $topBarangKeluar = MutasiBarang::with('barang')
            ->where('tipe', 'keluar')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("barang_id, SUM(jumlah) as total_keluar")
            ->groupBy('barang_id')
            ->orderByDesc('total_keluar')
            ->limit(5)
            ->get();

        return view('laporan.index', compact(
            'totalBarang', 'stokMenipis', 'totalPermintaan', 'permintaanDiserahkan',
            'chartLabels', 'chartMasuk', 'chartKeluar',
            'barangPerKategori', 'statusPermintaan',
            'mutasiTerbaru', 'barangKritis', 'topBarangKeluar',
            'startDate', 'endDate', 'periode'
        ));
    }

    /**
     * Export laporan ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $periode = $request->get('periode', 'bulan_ini');
        [$startDate, $endDate] = $this->getDateRange($periode, $request);

        $totalBarang         = Barang::count();
        $stokMenipis         = Barang::whereColumn('stok_saat_ini', '<=', 'stok_minimum')->count();
        $totalPermintaan      = Permintaan::whereBetween('created_at', [$startDate, $endDate])->count();
        $permintaanDiserahkan = Permintaan::where('status', 'diserahkan')
                                ->whereBetween('created_at', [$startDate, $endDate])->count();

        $mutasiTerbaru = MutasiBarang::with(['barang', 'staff'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        $barangKritis = Barang::with('kategori')
            ->whereColumn('stok_saat_ini', '<=', 'stok_minimum')
            ->orderBy('stok_saat_ini')
            ->get();

        $topBarangKeluar = MutasiBarang::with('barang')
            ->where('tipe', 'keluar')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("barang_id, SUM(jumlah) as total_keluar")
            ->groupBy('barang_id')
            ->orderByDesc('total_keluar')
            ->limit(10)
            ->get();

        // collect() memastikan selalu Collection meski query kosong / null
        $statusPermintaan = collect(
            Permintaan::selectRaw("status, COUNT(*) as total")
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('status')
                ->pluck('total', 'status')
        );

        $pdf = Pdf::loadView('laporan.pdf', compact(
            'totalBarang', 'stokMenipis', 'totalPermintaan', 'permintaanDiserahkan',
            'mutasiTerbaru', 'barangKritis', 'topBarangKeluar', 'statusPermintaan',
            'startDate', 'endDate', 'periode'
        ))->setPaper('a4', 'portrait');

        $filename = 'Laporan_Inventaris_' . Carbon::now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Helper: hitung rentang tanggal berdasarkan filter periode.
     */
    private function getDateRange(string $periode, Request $request): array
    {
        return match ($periode) {
            'hari_ini'   => [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()],
            'minggu_ini' => [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()],
            'bulan_lalu' => [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()],
            'tahun_ini'  => [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()],
            'custom'     => [
                Carbon::parse($request->get('start', now()->startOfMonth()))->startOfDay(),
                Carbon::parse($request->get('end', now()))->endOfDay(),
            ],
            default      => [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()],
        };
    }
}