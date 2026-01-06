<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name', 'description', 'thumbnail'];

    public function staffs()
    {
        return $this->belongsToMany(Staff::class, 'department_staff', 'department_id', 'staff_id');
    }
}
