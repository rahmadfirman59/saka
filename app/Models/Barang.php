<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Blameable;

class Barang extends Model
{
    use HasFactory;
    use Blameable;

    protected $table = 'barang';
    protected $guarded = ['id'];

    public function penjualan(){
        return $this->hasMany(Penjualan::class, 'id_barang', 'id');
    }

    public function pembelian(){
        return $this->hasMany(Pembelian::class, 'id_barang', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
