<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class student_subphase extends Model
{
    public function subphase(){
        return $this->belongsTo('App\Subphase');
    }
    public function mentor_grade(){
        return $this->hasMany('App\student_mentor_grade','subphase_std_id');
    }
}
