<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class sendMail implements ShouldQueue
{
    protected $email,$subject,$data,$view,$c_name,$c_email;

use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email,$subject,$data,$view,$c_name,$c_email)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->data = $data;
        $this->view = $view;
        $this->c_name = $c_name;
        $this->c_email = $c_email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->c_email == null){
           Mail::send($this->view,$this->data,function($m) {
            $m->subject($this->subject);
            $m->to($this->email);
        }); 
        }else{
            Mail::send($this->view,$this->data,function($m) {
            $m->subject($this->subject);
            $m->to($this->email);
            $m->from($this->c_email,$this->c_name);
        });
        }
        
    }
}
