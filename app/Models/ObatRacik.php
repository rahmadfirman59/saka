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
    protected $appends = ['stok'];

    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'racik_barang', 'id_racik', 'id_barang')
            ->withPivot('jumlah');
    }

    public function getStokAttribute()
    {
        $result = $this->barangs->map(function ($item){
            return floor(($item->stok * $item->jumlah_pecahan + $item->sisa_pecahan) / $item->pivot->jumlah);
        });
        return min(json_decode($result));
        
    }

}
