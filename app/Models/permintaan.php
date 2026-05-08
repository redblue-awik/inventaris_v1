<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

#[Table('permintaans')]
#[Fillable('id', 'permohonan_id', 'disetujui_oleh', 'barang_id', 'no_permintaan', 'jumlah_diminta', 'jumlah_disetujui', 'keperluan', 'status')]
class permintaan extends Model
{
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
