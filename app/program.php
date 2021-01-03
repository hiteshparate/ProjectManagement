<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class program extends Model
{
    public function phases(){
        return $this->hasMany('App\Phase');
    }
}
