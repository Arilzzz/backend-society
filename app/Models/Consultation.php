<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    public $timestamps = false;

    protected $guarded = [
        'id'
    ];

    public function society()
    {
        return $this->belongsTo(Society::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Medical::class, 'doctor_id');
    }
}
