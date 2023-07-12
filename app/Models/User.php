<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use \Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Blameable;

    protected $appends = ['created_at_desc'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'level',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    
    public function getCreatedAtDescAttribute(){
        return Carbon::parse($this->attributes['created_at'])->isoFormat('D MMMM Y');
    }

    public function user_created_by(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function user_updated_by(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
}
