<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'username',
        'password',
        'fullname',
        'email',
        'phone',
        'role_id',
        'created_at',
        'updated_at',
    ];

    protected $dates = ['deleted_at'];

    // relasi
    public function role()
    {
        return $this->belongsTo(Role::class); // setiap user hanya memiliki 1 role
    }

    public function orders()
    {
        return $this->hasMany(Order::class); // setiap user bisa memiliki banyak order
    }
}
