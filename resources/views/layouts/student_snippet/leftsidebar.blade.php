<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>
                <li>
                    <a href="<?php echo "/consent_form/".\Illuminate\Support\Facades\Session::get("program")?>" class="waves-effect"><i class="glyphicon glyphicon-hourglass"></i> <span style="font-size: 14px"> Consent Form </span> </a>
                </li>
                <li>
                    <a href="{{ url('view_project') }}" class="waves-effect"><i class="glyphicon glyphicon-book"></i> <span style="font-size: 14px"> View Project </span> </a>
                </li>
                <li>
                    <a href="{{ url('submission') }}" class="waves-effect"><i class="glyphicon glyphicon-file"></i> <span style="font-size: 14px"> Submission </span> </a>
                </li>
                <li>
                   <a href="{{ url('get_edit_off_campus') }}" class="waves-effect"><i class="glyphicon glyphicon-edit"></i> <span style="font-size: 14px">Edit Off campus Details </span> </a>

               </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>