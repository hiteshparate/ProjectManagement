<head>
    <style>
/* width */
::-webkit-scrollbar {
    width: 5px;
}

/* Track */
::-webkit-scrollbar-track {
    background: #f1f1f1; 
}
 
/* Handle */
::-webkit-scrollbar-thumb {
    background: #888; 
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #555; 
}
</style>
</head>
<div class="left side-menu "  >
    <div class="sidebar-inner slimscrollleft temp" style="overflow-y: auto" >
        <!--- Divider -->
        <div id="sidebar-menu"  style="padding-top: 10px">
            <ul>

                

                <li>
                    <a href="{{ url('add_student_to_programme') }}" class="waves-effect"><i class="glyphicon glyphicon-plus"></i> <span style="font-size: 13px">Add Student To Program</span> </a>
                </li>
                <li>
                    <a href="{{ url('consent_form_deadline') }}" class="waves-effect"><i class="glyphicon glyphicon-cog"></i> <span style="font-size: 13px">Consent Form Deadline</span> </a>
                </li>
                <li>
                    <a href="{{ url('phase') }}" class="waves-effect"><i class="glyphicon glyphicon-file"></i> <span style="font-size: 13px">Phase</span> </a>
                </li>

                <li>
                    <a href="{{ url('subphase') }}" class="waves-effect"><i class="glyphicon glyphicon-file"></i> <span style="font-size: 13px">Subphase</span> </a>
                </li>
                <li>
                    <a href="{{ url('event') }}" class="waves-effect"><i class="glyphicon glyphicon-file"></i> <span style="font-size: 13px">Event</span> </a>
                </li>
                <li>
                    <a href="{{ url('add_student_to_subphase') }}" class="waves-effect"><i class="glyphicon glyphicon-plus"></i> <span style="font-size: 13px">Add Student To Subphase</span> </a>
                </li>
                <li>
                    <a href="{{ url('all_student') }}" class="waves-effect"><i class="glyphicon glyphicon-user"></i> <span style="font-size: 13px">Student Details</span> </a>
                </li>
                <li>
                    <a href="{{ url('grading') }}" class="waves-effect"><i class="glyphicon glyphicon-star"></i> <span style="font-size: 13px">Student Grading</span> </a>
                </li>
                <li>
                    <a href="{{ url('manage_faculties') }}" class="waves-effect"><i class="glyphicon glyphicon-user"></i> <span style="font-size: 13px">Manage Faculty</span> </a>
                </li>
                <?php
                $prg = App\program::find(Session::get('coord_prg'));
                if ($prg->committee_formation_type == 'mtech') {
                    ?>
                    <li>
                        <a href="{{ url('manage_committee_events') }}" class="waves-effect"><i class="glyphicon glyphicon-cog"></i><span style="font-size: 13px">Manage Committee Event</span> </a>
                    </li>
                    <li>
                        <a href="{{ url('committee') }}" class="waves-effect"><i class="glyphicon glyphicon-user"></i> <span style="font-size: 13px">Committees</span> </a>
                    </li>
                    <?php
                } else {
                    ?>
                    <li>
                        <a href="{{ url('create_committee') }}" class="waves-effect"><i class="glyphicon glyphicon-user"></i> <span style="font-size: 13px">Committees</span> </a>
                    </li>
                    <?php
                }
                ?>

                <li>
                    <a href="{{ url('email_template') }}" class="waves-effect"><i class="glyphicon glyphicon-send"></i> <span style="font-size: 13px">Email Template</span> </a>
                </li>
                <li>
                    <a href="{{ url('manage_deadline_for_event') }}" class="waves-effect"><i class="glyphicon glyphicon-cog"></i> <span style="font-size: 13px">Modify deadline for Event</span> </a>
                </li>
                
                <li>
                    <a href="{{ url('/view_backend_email_template') }}" class="waves-effect"><i class="glyphicon glyphicon-cog"></i> <span style="font-size: 13px">View Backend Email Template</span> </a>
                </li>


            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
