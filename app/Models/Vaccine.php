<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    public $timestamps = false;

    protected $guarded = ["id"];

    public function spots()
    {
        return $this->belongsToMany(
            Spot::class,
            'spot_vaccines',
            'vaccine_id',
            'spot_id'
        );
    }
}
