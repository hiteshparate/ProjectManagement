<?php

namespace App\Jobs;

use App\Student;
use App\Faculty;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\coordinator;
use App\resource_centre;
use App\admin;

class updatePasswordToken implements ShouldQueue {

    protected $user;

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Student $std = null, Faculty $fac = null, coordinator $cord = null, resource_centre $rc = null, admin $admin=null ) {
        if ($std != null) {
            $this->user = $std;
        } else if ($fac != null) {
            $this->user = $fac;
        }else if($cord != null){
            $this->user = $cord;
        }else if($rc != null){
            $this->user = $rc;
        }else if($admin != null){
            $this->user = $admin;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $str = md5(str_random(50));
        $this->user->forgot_password_key = $str;
        $this->user->save();
    }

}
