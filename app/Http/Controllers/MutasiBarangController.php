<?php

namespace App\Http\Controllers;

use App\Models\mutasi_barang as MutasiBarang;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MutasiBarangController extends Controller
{
    public function index()
    {
        $mutasiBarang = MutasiBarang::with(['barang', 'staff', 'referensi'])->latest()->get();
        $barangs = Barang::all(); 
        $users = User::all();

        return view('mutasi_barang', compact('mutasiBarang', 'barangs', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id'    => 'required|exists:barangs,id',
            'tipe'         => 'required|in:masuk,keluar,opname,transfer',
            'jumlah'       => 'required|integer|min:1',
            'referensi_id' => 'nullable|exists:users,id',
            'keterangan'   => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // 1. Ambil data barang (lockForUpdate mencegah bentrok data jika ada 2 user input bersamaan)
                $barang = Barang::lockForUpdate()->findOrFail($request->barang_id);
                
                $stok_sebelum = $barang->stok_saat_ini; // Sesuaikan dengan nama kolom di tabel barangs
                $jumlah = $request->jumlah;
                $stok_sesudah = $stok_sebelum;

                // 2. Kalkulasi logika penambahan/pengurangan
                if ($request->tipe === 'masuk') {
                    $stok_sesudah = $stok_sebelum + $jumlah;
                } elseif (in_array($request->tipe, ['keluar', 'transfer'])) {
                    if ($stok_sebelum < $jumlah) {
                        throw new \Exception('Stok barang tidak mencukupi untuk dikeluarkan.');
                    }
                    $stok_sesudah = $stok_sebelum - $jumlah;
                } else {
                    // Jika opname (stok fisik), kita asumsikan 'jumlah' adalah stok real/pengganti yang baru
                    // Jika di sistemmu opname itu selisihnya, ubah logikanya di sini.
                    $stok_sesudah = $jumlah; 
                }

                // 3. Catat ke tabel mutasi
                MutasiBarang::create([
                    'barang_id'    => $barang->id,
                    'user_id'      => Auth::id(),
                    'referensi_id' => $request->referensi_id,
                    'tipe'         => $request->tipe,
                    'jumlah'       => $jumlah,
                    'stok_sebelum' => $stok_sebelum,
                    'stok_sesudah' => $stok_sesudah,
                    'keterangan'   => $request->keterangan,
                ]);

                // 4. Update stok master barang
                $barang->update(['stok_saat_ini' => $stok_sesudah]);
            });

            return redirect()->back()->with('success', 'Mutasi barang berhasil dicatat.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        // PERINGATAN: Mengedit transaksi lama tidak akan mengupdate otomatis 'stok_sebelum' 
        // pada transaksi-transaksi yang terjadi SETELAH transaksi ini.
        
        $request->validate([
            'tipe'       => 'required|in:masuk,keluar,opname,transfer',
            'jumlah'     => 'required|integer|min:1',
            'keterangan' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $mutasi = MutasiBarang::findOrFail($id);
                $barang = Barang::lockForUpdate()->findOrFail($mutasi->barang_id);

                // 1. REVERT (Kembalikan efek mutasi lama)
                $stok_sementara = $barang->stok_saat_ini;
                if ($mutasi->tipe === 'masuk') {
                    $stok_sementara -= $mutasi->jumlah;
                } elseif (in_array($mutasi->tipe, ['keluar', 'transfer'])) {
                    $stok_sementara += $mutasi->jumlah;
                }

                // 2. APPLY (Terapkan efek mutasi baru)
                $stok_sesudah_baru = $stok_sementara;
                if ($request->tipe === 'masuk') {
                    $stok_sesudah_baru += $request->jumlah;
                } elseif (in_array($request->tipe, ['keluar', 'transfer'])) {
                    if ($stok_sementara < $request->jumlah) {
                        throw new \Exception('Update ditolak: Stok akhir akan menjadi minus.');
                    }
                    $stok_sesudah_baru -= $request->jumlah;
                } else {
                    $stok_sesudah_baru = $request->jumlah; // opname
                }

                // 3. Update tabel mutasi
                // Catatan: stok_sebelum kita biarkan sama agar riwayat awalnya tidak hilang, 
                // hanya update stok_sesudah, jumlah, dan tipe
                $mutasi->update([
                    'tipe'         => $request->tipe,
                    'jumlah'       => $request->jumlah,
                    'stok_sesudah' => $mutasi->stok_sebelum + ($stok_sesudah_baru - $stok_sementara), // Aproksimasi riwayat
                    'keterangan'   => $request->keterangan,
                ]);

                // 4. Update stok master barang
                $barang->update(['stok_saat_ini' => $stok_sesudah_baru]);
            });

            return redirect()->back()->with('success', 'Data mutasi berhasil diperbarui.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $mutasi = MutasiBarang::findOrFail($id);
                $barang = Barang::lockForUpdate()->findOrFail($mutasi->barang_id);

                // Revert stok sesuai tipe mutasi
                $stok_baru = $barang->stok_saat_ini;

                if ($mutasi->tipe === 'masuk') {
                    // Jika tipe masuk, kurangi stok
                    $stok_baru -= $mutasi->jumlah;
                } elseif (in_array($mutasi->tipe, ['keluar', 'transfer'])) {
                    // Jika tipe keluar/transfer, tambah stok
                    $stok_baru += $mutasi->jumlah;
                } else {
                    // Jika opname, gunakan stok_sebelum
                    $stok_baru = $mutasi->stok_sebelum;
                }

                // Update stok barang
                $barang->update(['stok_saat_ini' => $stok_baru]);

                // Hapus record mutasi
                $mutasi->delete();
            });

            return redirect()->back()->with('success', 'Mutasi berhasil dihapus dan stok di-revert.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}