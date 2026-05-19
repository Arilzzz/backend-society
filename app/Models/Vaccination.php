<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    public $timestamps = false;

    protected $guarded = ["id"];

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
    public function vaccine()
    {
        return $this->belongsTo(Vaccine::class);
    }
    public function vacinator()
    {
        return $this->belongsTo(Medical::class);
    }
    public function regional()
    {
        return $this->belongsTo(Society::class);
    }
}
