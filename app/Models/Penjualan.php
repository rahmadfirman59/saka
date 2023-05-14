<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;
    use Blameable;
    protected $table = 'penjualan';
    protected $guarded = ['id'];

    public function dokter(){
        return $this->belongsTo(Dokter::class, 'dokter_id', 'id');
    }

    public function barang(){
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }

    public function transaksi(){
        return $this->belongsTo(MasterTransaksi::class, 'id_transaksi', 'id');
    }
}
