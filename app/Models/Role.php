<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasUuids, SoftDeletes;

    protected $connection = 'user_management';
    protected $table = 'roles';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    protected $fillable = [
        'id',
        'name',
        'description',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    public function scopeDefault($query)
    {
        //TODO: temporary admin, kalau mau prod ganti ke MAHASISWA
        $defaultRole = env('DEFAULT_ROLE', 'MAHASISWA');
        return $query->where('name', $defaultRole);
    }
}
