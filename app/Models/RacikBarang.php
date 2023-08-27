<?php

namespace App\Models;

use App\Observers\BlameableObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RacikBarang extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'racik_barang';
    public $timestamps = false;

    public function barang(){
        return $this->belongsTo(Barang::class, 'id_barang', 'id');
    }
}
