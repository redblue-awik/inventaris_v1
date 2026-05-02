<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('barangs')]
#[Fillable(['id', 'nama_barang', 'jumlah', 'kondisi'])]
class barang extends Model
{
    public function peminjaman_barang()
    {
        return $this->hasMany(peminjaman_barang::class);
    }
}
