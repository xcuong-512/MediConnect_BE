<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'person_id',
        'medical_code'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
