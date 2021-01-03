<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Classes;
use App\Faculty;
use App\coordinator;
use App\program;

class uspmes{
    public function sync_coordinator_password($program_id,$faculty_id){
        $fac = Faculty::find($faculty_id);
        $prg = program::find($program_id);
        $coord = coordinator::find($prg->coordinator_id);
        $coord->password = $fac->password;
        $coord->save();
    }
}
