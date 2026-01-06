<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = [
        'account_id',
        'full_name',
        'date_of_birth',
        'gender',
        'phone',
        'address',
        'avatar'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
