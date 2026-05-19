<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regional extends Model
{
    protected $table = 'regionals';

    public function society()
    {
        return $this->belongsTo(Society::class);
    }

    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
