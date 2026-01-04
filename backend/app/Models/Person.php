<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = [
        'full_name',
        'date_of_birth',
        'gender',
        'phone',
        'address'
    ];
}
