<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\peminjaman_barang;
use App\Models\barang;
use Carbon\Carbon;

class PeminjamanBarangSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = barang::all();

        foreach ($barangs as $barang) {
            // tiap barang bikin 1-3 peminjaman
            for ($i = 0; $i < rand(1, 3); $i++) {

                $tanggalPinjam = Carbon::now()->subDays(rand(1, 10));
                $tanggalKembali = (rand(0,1))
                    ? $tanggalPinjam->copy()->addDays(rand(1,5))
                    : null;

                peminjaman_barang::create([
                    'barang_id' => $barang->id,
                    'nama_peminjam' => fake()->name(),
                    'tanggal_pinjam' => $tanggalPinjam,
                    'tanggal_kembali' => $tanggalKembali,
                ]);
            }
        }
    }
}