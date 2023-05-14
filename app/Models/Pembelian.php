<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    use HasFactory;
    use Blameable;

    protected $table = 'pembelian';
    protected $guarded = ['id'];

    public function supplier(){
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id');
    }

    public function barang(){
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }

    public function transaksi(){
        return $this->belongsTo(MasterTransaksi::class, 'id_transaksi', 'id');
    }
}
