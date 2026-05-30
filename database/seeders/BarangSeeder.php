<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\barang;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kategori_id' => 1,
                'supplier_id' => 1,
                'kode_barang' => '',
                'nama_barang' => 'Laptop Asus ROG',
                'satuan' => 'Unit',
                'stok_saat_ini' => 10,
                'stok_minimum' => 2,
                'lokasi_rak' => 'Rak A1',
                'kondisi' => 'baik',
            ],
            [
                'kategori_id' => 1,
                'supplier_id' => 1,
                'kode_barang' => '',
                'nama_barang' => 'Printer Canon',
                'satuan' => 'Unit',
                'stok_saat_ini' => 4,
                'stok_minimum' => 1,
                'lokasi_rak' => 'Rak B1',
                'kondisi' => 'rusak',
            ],
            [
                'kategori_id' => 2,
                'supplier_id' => 1,
                'kode_barang' => '',
                'nama_barang' => 'Meja Belajar',
                'satuan' => 'Unit',
                'stok_saat_ini' => 15,
                'stok_minimum' => 5,
                'lokasi_rak' => 'Gudang',
                'kondisi' => 'baik',
            ]
        ];

        foreach ($data as $item) {
            barang::create($item);
        }
    }
}