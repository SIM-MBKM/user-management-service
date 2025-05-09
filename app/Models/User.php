<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasUuids;

    protected $connection = 'user_management';
    protected $table = 'users';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'auth_user_id',
        'role_id',
        'email',
        'nrp',  //nrp data, can be viewed as NIP if role isn't MAHASISWA (some use cases view this as NIP or null valued)
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function directPermissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id')
            ->using(UserPermission::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role->name === 'ADMIN';
    }

    public function hasPermission(string $permission): bool
    {
        return $this->role->permissions()
            ->where('name', $permission)
            ->exists() ||
            $this->directPermissions()
            ->where('name', $permission)
            ->exists();
    }

    // public function getAllPermissions(): Collection
    // {
    //     return $this->role->permissions->merge($this->directPermissions())->unique('id');
    // }
}
