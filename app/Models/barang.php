<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('barangs')]
#[Fillable(['id', 'kategori_id', 'supplier_id', 'kode_barang', 'nama_barang', 'satuan', 'stok_saat_ini', 'stok_minimum', 'lokasi_rak', 'kondisi'])]
class barang extends Model
{
    public function permintaan()
    {
        return $this->hasMany(permintaan::class);
    }

    public function mutasi_barang()
    {
        return $this->hasMany(mutasi_barang::class);
    }
}
