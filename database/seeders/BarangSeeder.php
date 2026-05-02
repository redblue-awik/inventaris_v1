<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\barang;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nama_barang' => 'Laptop Asus ROG', 'jumlah' => 10, 'kondisi' => 'Baik'],
            ['nama_barang' => 'Proyektor Epson', 'jumlah' => 5, 'kondisi' => 'Baik'],
            ['nama_barang' => 'Kabel HDMI', 'jumlah' => 20, 'kondisi' => 'Baik'],
            ['nama_barang' => 'Mouse Logitech', 'jumlah' => 15, 'kondisi' => 'Baik'],
            ['nama_barang' => 'Keyboard Mechanical', 'jumlah' => 8, 'kondisi' => 'Baik'],
            ['nama_barang' => 'Monitor LG 24 inch', 'jumlah' => 6, 'kondisi' => 'Baik'],
            ['nama_barang' => 'Printer Canon', 'jumlah' => 4, 'kondisi' => 'Rusak'],
            ['nama_barang' => 'Scanner Epson', 'jumlah' => 3, 'kondisi' => 'Baik'],
        ];

        foreach ($data as $item) {
            barang::create($item);
        }
    }
}