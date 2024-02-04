<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'user_id', 'permission_id');
    }

    public function rolePermissions()
    {
        return $this->hasMany(RolePermission::class);
    }

    public function logs()
    {
        return $this->hasMany(UserLog::class);
    }

    public function isAdmin()
    {
        return $this->roles()->where('name', 'Admin')->exists();
    }

    public function isContentManager()
    {
        return $this->roles()->where('name', 'Content Manager')->exists();
    }

    public function isUser()
    {
        return $this->roles()->where('name', 'User')->exists();
    }

    public function assignRole(Role $role)
    {
        $this->roles()->attach($role);
    }

    public function assignRoles(array $roles)
    {
        // Get role IDs for the provided roles
        $roleIds = Role::whereIn('name', $roles)->pluck('id')->toArray();

        // Sync the roles
        $this->roles()->sync($roleIds);

        // Sync the associated permissions based on the new roles
        $permissionIds = RolePermission::whereIn('role_id', $roleIds)->pluck('permission_id')->toArray();
        $this->permissions()->sync($permissionIds);
    }
}
