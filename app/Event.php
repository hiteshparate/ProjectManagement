<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function subphase(){
        return $this->belongsTo('App\Subphase');
    }
}
