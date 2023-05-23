<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory, Blameable;
    protected $table = 'pasien';
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    } 
}
