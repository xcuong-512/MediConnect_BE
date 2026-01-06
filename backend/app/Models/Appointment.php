<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'staff_id',
        'branch_id',
        'scheduled_at',
        'status',
        'note'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
