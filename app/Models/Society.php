<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Society extends Model
{

    protected $table = 'societies';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function regional()
    {
        return $this->belongsTo(Regional::class);
    }
}
