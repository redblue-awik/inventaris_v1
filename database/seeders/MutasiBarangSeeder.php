<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\mutasi_barang;
use App\Models\barang;
use App\Models\User;
use Carbon\Carbon;

class MutasiBarangSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() === 0) {
            User::factory(5)->create();
        }

        $users = User::all()->pluck('id')->toArray();
        $barangs = barang::all();
        $types = ['masuk', 'keluar', 'opname', 'transfer'];

        foreach ($barangs as $barang) {
            $stok = $barang->stok_saat_ini ?? 0;

            // create 1-4 mutasi per barang
            for ($i = 0; $i < rand(1, 4); $i++) {
                $type = $types[array_rand($types)];
                $jumlah = rand(1, 5);

                $stok_sebelum = $stok;

                if ($type === 'masuk' || $type === 'opname') {
                    $stok += $jumlah;
                } elseif ($type === 'keluar') {
                    $stok = max(0, $stok - $jumlah);
                } elseif ($type === 'transfer') {
                    // transfer treated like keluaran here
                    $stok = max(0, $stok - $jumlah);
                }

                $mutasi = mutasi_barang::create([
                    'barang_id' => $barang->id,
                    'user_id' => $users[array_rand($users)],
                    'referensi_id' => (rand(0,1) ? $users[array_rand($users)] : null),
                    'tipe' => $type,
                    'jumlah' => $jumlah,
                    'stok_sebelum' => $stok_sebelum,
                    'stok_sesudah' => $stok,
                    'keterangan' => 'Seeded mutasi ' . $type,
                    'created_at' => Carbon::now()->subDays(rand(0, 30)),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
