<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;
    use Blameable;
    protected $table = 'dokter';
    protected $guarded = ['id'];

    public function user_created_by(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function user_updated_by(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
