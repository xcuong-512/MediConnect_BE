<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone'
    ];

    public function staffSchedules()
    {
        return $this->hasMany(StaffSchedule::class);
    }
}
