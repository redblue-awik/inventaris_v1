<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_supplier' => 'PT. Sumber Perkasa',
                'kontak_person' => 'Budi Santoso',
                'telepon' => '081234567890',
                'email' => 'supplier@example.com',
                'alamat' => 'Jl. Sudirman No.1',
            ],
        ];

        foreach ($data as $item) {
            supplier::create($item);
        }
    }
}
