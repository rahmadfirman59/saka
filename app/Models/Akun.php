<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Blameable;

class Akun extends Model
{
    use Blameable;
    use HasFactory;
    protected $table = 'akun';
    protected $guarded = ['id'];
}
