<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class sendMail_coordinator implements ShouldQueue
{
    protected $email,$subject,$to,$cc,$coord_name,$coord_email;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email,$subject,$to,$cc,$coord_name,$coord_email)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->to = $to;
        $this->cc = $cc;
        $this->coord_email = $coord_email;
        $this->coord_name = $coord_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::raw($this->email, function($m) {
                $m->subject($this->subject);
                $m->to($this->to);
                $m->from($this->coord_email, $this->coord_name);
                if ($this->cc[0] != null) {
                    $m->cc($this->cc);
                }
            });
    }
}
