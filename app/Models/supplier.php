<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class supplier extends Model
{
    public function barang()
    {
        return $this->hasMany(barang::class);
    }
}
