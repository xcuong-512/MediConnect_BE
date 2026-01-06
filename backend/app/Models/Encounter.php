<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Encounter extends Model
{
    protected $fillable = ['appointment_id', 'notes'];

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
