<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('peminjaman_barangs')]
#[Fillable(['id', 'barang_id', 'nama_peminjam', 'tanggal_pinjam', 'tanggal_kembali'])]
class peminjaman_barang extends Model
{
    public function barang()
    {
        return $this->belongsTo(barang::class);
    }
}
