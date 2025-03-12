<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id', 
        'role_id', 
        'nrp', 
        'status', 
        'last_login'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // public function receivedNotifications()
    // {
    //     return $this->hasMany(Notification::class, 'receiver_id');
    // }

    // public function sentNotifications()
    // {
    //     return $this->hasMany(Notification::class, 'sender_id');
    // }
}
