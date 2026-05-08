<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('mutasi_barangs')]
#[Fillable('id', 'barang_id', 'user_id', 'referensi_id', 'tipe', 'jumlah', 'stok_sebelum', 'stok_sesudah', 'keterangan')]
class mutasi_barang extends Model
{
    public function barang()
    {
        return $this->belongsTo(barang::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function referensi()
    {
        return $this->belongsTo(User::class, 'referensi_id');
    }
}
