<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';
    protected $fillable = ['person_id', 'staff_code'];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_staff', 'staff_id', 'department_id');
    }
}
