<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $fillable = [
        'encounter_id',
        'diagnosis',
        'treatment_plan',
        'notes'
    ];

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }
}
