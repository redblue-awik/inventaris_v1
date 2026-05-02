<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $fillable = ['nama', 'kategori', 'stok'];
    public function peminjamans() { return $this->hasMany(Peminjaman::class); }
}