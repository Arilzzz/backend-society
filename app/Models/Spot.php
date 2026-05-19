<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    public $timestamps = false;

    protected $guarded = ["id"];

    public function vaccines()
    {
        return $this->belongsToMany(
            Vaccine::class,
            'spot_vaccines',
            'spot_id',
            'vaccine_id'
        );
    }
    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class);
    }
    public function regional()
    {
        return $this->belongsTo(regional::class);
    }
}
