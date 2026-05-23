<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\permintaan;
use App\Models\barang;
use App\Models\User;

class PermintaanSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() < 2) {
            User::factory(5)->create();
        }

        $users = User::all()->pluck('id')->toArray();
        $barangs = barang::all();

        $statuses = ['menunggu', 'disetujui', 'ditolak', 'diserahkan'];

        foreach ($barangs as $barang) {
            for ($i = 0; $i < rand(1, 3); $i++) {
                $jumlah = rand(1, 5);
                $status = $statuses[array_rand($statuses)];
                $jumlah_disetujui = in_array($status, ['disetujui', 'diserahkan']) ? $jumlah : null;
                $disetujui_oleh = in_array($status, ['disetujui', 'diserahkan']) ? User::inRandomOrder()->first()->id : null;

                permintaan::create([
                    'permohonan_id' => $users[array_rand($users)],
                    'disetujui_oleh' => $disetujui_oleh,
                    'barang_id' => $barang->id,
                    'no_permintaan' => 'REQ' . str_pad((string) rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'jumlah_diminta' => $jumlah,
                    'jumlah_disetujui' => $jumlah_disetujui,
                    'keperluan' => fake()->sentence(),
                    'status' => $status,
                ]);
            }
        }
    }
}
