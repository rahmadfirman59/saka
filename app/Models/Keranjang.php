<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $table = 'keranjang';

    public function barang(){
        return $this->belongsTo(barang::class, 'id_barang', 'id');
    }

    public function obat_racik(){
        return $this->belongsTo(ObatRacik::class, 'id_racik', 'id');
    }
    
}
