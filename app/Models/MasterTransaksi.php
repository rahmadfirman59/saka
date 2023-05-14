<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterTransaksi extends Model
{
    use HasFactory;
    use Blameable;
    protected $table = 'transaksi';
    protected $guarded = ['id'];
    
    function pembelian(){
        return $this->hasMany(Pembelian::class, 'id_transaksi');
    }
}
