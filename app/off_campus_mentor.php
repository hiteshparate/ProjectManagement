<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class off_campus_mentor extends Model
{
    protected $fillable = [
        'name','contact_number','email','company_name','project_duration','project_start_date',
    ];
}
