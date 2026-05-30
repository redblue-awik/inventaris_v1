<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permintaan;
use App\Models\Barang;
use App\Models\User;
use Carbon\Carbon;

class PermintaanSeeder extends Seeder
{
    public function run(): void
    {
        $barangs = Barang::all();
        $users = User::all();

        $keperluan = ['Kegiatan pembelajaran', 'Praktikum RPL', 'Administrasi kantor', 'Kebutuhan ruang guru', 'Persiapan ujian', 'Kegiatan ekstrakurikuler'];

        $nomor = 1;

        foreach ($barangs as $barang) {
            // Menunggu
            Permintaan::create([
                'permohonan_id' => $users->random()->id,
                'barang_id' => $barang->id,
                'no_permintaan' => 'REQ' . now()->format('Ymd') . str_pad($nomor++, 3, '0', STR_PAD_LEFT),
                'jumlah_diminta' => rand(1, 3),
                'jumlah_disetujui' => null,
                'disetujui_oleh' => null,
                'keperluan' => fake()->randomElement($keperluan),
                'status' => 'menunggu',
                'created_at' => Carbon::now()->subDays(rand(1, 15)),
            ]);

            // Disetujui
            Permintaan::create([
                'permohonan_id' => $users->random()->id,
                'barang_id' => $barang->id,
                'no_permintaan' => 'REQ' . now()->format('Ymd') . str_pad($nomor++, 3, '0', STR_PAD_LEFT),
                'jumlah_diminta' => 2,
                'jumlah_disetujui' => 2,
                'disetujui_oleh' => $users->random()->id,
                'keperluan' => fake()->randomElement($keperluan),
                'status' => 'disetujui',
                'created_at' => Carbon::now()->subDays(rand(1, 10)),
            ]);

            // Diserahkan
            Permintaan::create([
                'permohonan_id' => $users->random()->id,
                'barang_id' => $barang->id,
                'no_permintaan' => 'REQ' . now()->format('Ymd') . str_pad($nomor++, 3, '0', STR_PAD_LEFT),
                'jumlah_diminta' => 1,
                'jumlah_disetujui' => 1,
                'disetujui_oleh' => $users->random()->id,
                'keperluan' => fake()->randomElement($keperluan),
                'status' => 'diserahkan',
                'created_at' => Carbon::now()->subDays(rand(1, 5)),
            ]);
        }
    }
}
