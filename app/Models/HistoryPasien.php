<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPasien extends Model
{
    use HasFactory;

    protected $table = 'history_pasien';
    protected $guarded = ['id'];

    public function pasien(){
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id');
    }
}
