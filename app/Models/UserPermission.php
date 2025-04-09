<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User
{
    use HasUuids;

    protected $connection = 'user_management';
    protected $table = 'user_permissions';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_id',
        'permission_id',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    // many to many relations
}
