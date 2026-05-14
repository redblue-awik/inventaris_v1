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
                'kode_barang' => 'BRG001',
                'nama_barang' => 'Laptop Asus ROG',
                'satuan' => 'Unit',
                'stok_saat_ini' => 10,
                'stok_minimum' => 2,
                'kondisi_rak' => 'Rak A1',
                'kondisi' => 'baik',
            ],
            [
                'kategori_id' => 1,
                'supplier_id' => 1,
                'kode_barang' => 'BRG002',
                'nama_barang' => 'Printer Canon',
                'satuan' => 'Unit',
                'stok_saat_ini' => 4,
                'stok_minimum' => 1,
                'kondisi_rak' => 'Rak B1',
                'kondisi' => 'rusak',
            ],
        ];

        foreach ($data as $item) {
            barang::create($item);
        }
    }
}