<?php

namespace App\Console\Commands;

use App\Models\Barang;
use App\Models\mutasi_barang;
use App\Models\Permintaan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DiserahkanOtomatis extends Command
{
    protected $signature = 'app:diserahkan-otomatis';

    protected $description = 'Mengubah status disetujui menjadi diserahkan dan mengurangi stok barang';

    public function handle()
    {
        $permintaans = Permintaan::where('status', 'disetujui')->get();

        foreach ($permintaans as $permintaan) {

            DB::transaction(function () use ($permintaan) {

                $barang = Barang::lockForUpdate()->find($permintaan->barang_id);

                $jumlah = $permintaan->jumlah_disetujui ?? $permintaan->jumlah_diminta;

                if ($barang->stok_saat_ini < $jumlah) {
                    $this->warn(
                        "Stok {$barang->nama_barang} tidak mencukupi untuk permintaan {$permintaan->no_permintaan}"
                    );
                    return;
                }

                $stokSebelum = $barang->stok_saat_ini;

                $barang->decrement('stok_saat_ini', $jumlah);

                $permintaan->update([
                    'status' => 'diserahkan'
                ]);

                // jika ada model MutasiBarang
                mutasi_barang::create([
                    'barang_id' => $barang->id,
                    'user_id' => $permintaan->disetujui_oleh,
                    'referensi_id' => $permintaan->permohonan_id,
                    'tipe' => 'keluar',
                    'jumlah' => $jumlah,
                    'stok_sebelum' => $stokSebelum,
                    'stok_sesudah' => $stokSebelum - $jumlah,
                    'keterangan' => $permintaan->keperluan,
                ]);
            });
        }

        $this->info('Semua permintaan berhasil diproses.');
    }
}