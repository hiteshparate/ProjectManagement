<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    public function subphases(){
        return $this->hasMany('App\Subphase');
    }
    
    public function program(){
        return $this->belongsTo('App\program');
    }
}
