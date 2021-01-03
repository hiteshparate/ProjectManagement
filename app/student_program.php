<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class student_program extends Model
{
    protected  $fillable = [
        'student_id','program_id','project_type','project_topic','mentor_1','mentor_2'
    ];
    
    public function groups(){
        return $this->hasMany('App\student_group','student_id','student_id');
    }
}
