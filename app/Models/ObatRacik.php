<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Blameable;

class ObatRacik extends Model
{
    use HasFactory, Blameable;
    protected $guarded = ['id'];
    protected $table = 'obat_racik';

    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'racik_barang', 'id_racik', 'id_barang')
            ->withPivot('jumlah');
    }

}
