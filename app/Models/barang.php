<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('barangs')]
#[Fillable(['id', 'kategori_id', 'supplier_id', 'kode_barang', 'nama_barang', 'satuan', 'stok_saat_ini', 'stok_minimum', 'lokasi_rak', 'kondisi'])]
class barang extends Model
{
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->kode_barang)) {
                $date = now()->format('Ymd');
                $prefix = 'BRG' . $date;

                $lastBarang = self::where('kode_barang', 'like', $prefix . '%')
                    ->orderBy('id', 'desc')
                    ->first();

                $number = $lastBarang ? intval(substr($lastBarang->kode_barang, 8)) + 1 : 1;
                $model->kode_barang = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function permintaan()
    {
        return $this->hasMany(permintaan::class);
    }

    public function mutasi_barang()
    {
        return $this->hasMany(mutasi_barang::class);
    }

    public function kategori()
    {
        return $this->belongsTo(kategori::class);
    }

    public function supplier()
    {
        return $this->belongsTo(supplier::class);
    }
}
