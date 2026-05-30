<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\mutasi_barang as MutasiBarang;
use App\Models\Barang;
use App\Models\User;
use Carbon\Carbon;

class MutasiBarangSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach (Barang::all() as $barang) {

            // Barang masuk
            MutasiBarang::create([
                'barang_id' => $barang->id,
                'user_id' => $users->random()->id,
                'referensi_id' => null,
                'tipe' => 'masuk',
                'jumlah' => 20,
                'stok_sebelum' => 0,
                'stok_sesudah' => 20,
                'keterangan' => 'Pengadaan barang dari supplier',
                'created_at' => Carbon::now()->subDays(30),
            ]);

            // Barang keluar
            MutasiBarang::create([
                'barang_id' => $barang->id,
                'user_id' => $users->random()->id,
                'referensi_id' => $users->random()->id,
                'tipe' => 'keluar',
                'jumlah' => 5,
                'stok_sebelum' => 20,
                'stok_sesudah' => 15,
                'keterangan' => 'Permintaan barang untuk kegiatan pembelajaran',
                'created_at' => Carbon::now()->subDays(10),
            ]);

            // Opname
            MutasiBarang::create([
                'barang_id' => $barang->id,
                'user_id' => $users->random()->id,
                'referensi_id' => null,
                'tipe' => 'opname',
                'jumlah' => 1,
                'stok_sebelum' => 15,
                'stok_sesudah' => 14,
                'keterangan' => 'Penyesuaian stok hasil stock opname',
                'created_at' => Carbon::now()->subDays(3),
            ]);
        }
    }
}