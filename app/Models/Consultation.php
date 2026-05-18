<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'disease_history',
        'current_symptoms'
    ];

    public function society()
    {
        return $this->belongsTo(Society::class);
    }
}
