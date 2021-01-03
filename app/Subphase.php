<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subphase extends Model
{
    public function events(){
        return $this->hasMany('App\Event');
    }
    
    public function phase(){
        return $this->belongsTo('App\Phase');
    }
}
