<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Society extends Model
{

    public $timestamps = false;

    protected $guarded = ['id'];

    public function regional()
    {
        return $this->belongsTo(Regional::class);
    }

    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }
    public function Vaccinations()
    {
        return $this->hasMany(Consultation::class);
    }
}
