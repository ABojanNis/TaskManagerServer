<?php

namespace App;

use App\Helpers\RoleTypes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = [
        'is_admin'
    ];

    public function getIsAdminAttribute()
    {
        if ($this->hasRole(RoleTypes::ADMIN)) {
            return true;
        } else {
            return false;
        }
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'user_tasks')->withPivot('status', 'expired_at');
    }
}
