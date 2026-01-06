<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Laravel\Sanctum\HasApiTokens;

class Account extends Authenticatable
{
    use HasApiTokens;

    protected $fillable = [
        'email',
        'password',
        'status'
    ];

    protected $hidden = [
        'password'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'account_roles');
    }

    public function person()
    {
        return $this->hasOne(Person::class);
    }
}
