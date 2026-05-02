<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    protected $fillable = ['barang_id', 'peminjam', 'tgl_pinjam', 'tgl_kembali', 'jumlah', 'status'];
    protected $casts = ['tgl_pinjam' => 'date', 'tgl_kembali' => 'date'];
    public function barang() { return $this->belongsTo(Barang::class); }
}