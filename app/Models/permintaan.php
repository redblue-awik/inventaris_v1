<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('permintaans')]
#[Fillable('id', 'permohonan_id', 'disetujui_oleh', 'barang_id', 'no_permintaan', 'jumlah_diminta', 'jumlah_disetujui', 'keperluan', 'status')]
class permintaan extends Model
{
    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->no_permintaan)) {
                $date = now()->format('Ymd');
                $prefix = 'REQ' . $date;

                $lastPermintaan = self::where('no_permintaan', 'like', $prefix . '%')
                    ->orderBy('id', 'desc')
                    ->first();

                $number = $lastPermintaan ? intval(substr($lastPermintaan->no_permintaan, 11)) + 1 : 1;
                $model->no_permintaan = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function permohonan()
    {
        return $this->belongsTo(user::class, 'permohonan_id');
    }

    public function disetujui()
    {
        return $this->belongsTo(user::class, 'disetujui_oleh');
    }

    public function barang()
    {
        return $this->belongsTo(barang::class);
    }
}
