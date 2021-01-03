<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>
                 <li>
                    <a href="{{ url('faculty_option') }}" class="waves-effect"><i class="glyphicon glyphicon-home"></i> <span style="font-size: 14px">Home</span> </a>
                </li>
                <li>
                    <?php
                    $p = \Illuminate\Support\Facades\Session::get('program');
                    ?>
                    <a href="<?php echo url("mentor_request/$p") ?>" class="waves-effect"><i class="glyphicon glyphicon-inbox"></i> <span style="font-size: 14px"> Mentor Request </span> </a>
                </li>
                <li>
                    <a href="{{ url('project_details') }}" class="waves-effect"><i class="glyphicon glyphicon-file"></i> <span style="font-size: 14px">Project Details </span> </a>
                </li>
                <li>
                    <a href="{{ url('manage_groups') }}" class="waves-effect"><i class="glyphicon glyphicon-user"></i> <span style="font-size: 14px">Manage Groups </span> </a>
                </li>
                <li>
                    <a href="{{ url('mentor_grading') }}" class="waves-effect"><i class="glyphicon glyphicon-star"></i> <span style="font-size: 14px">Grading </span> </a>
                </li>
                <?php
                $prg = \App\program::where('name', Session::get('program'))->first();
                if ($prg->committee_formation_type == 'mtech') {
                    if ($prg->se_n_start_date == null || $prg->se_n_end_date == null) {
                        
                    } else {
                        $curr_date = date('Y-m-d', strtotime(date('Y-m-d')));
                        $start_date = date('Y-m-d', strtotime($prg->se_n_start_date));
                        $end_date = date('Y-m-d', strtotime($prg->se_n_end_date));
                        if (($curr_date >= $start_date) && ($curr_date <= $end_date)) {
                            ?>
                            <li>
                                <a href="{{ url('committee_formation') }}" class="waves-effect"><i class="glyphicon glyphicon-user"></i> <span style="font-size: 14px">Committee Formation </span> </a>
                            </li>
                            <?php
                        }
                    }
                }
                ?>
                <?php
                $prg = \App\program::where('name', Session::get('program'))->first();
                if ($prg->committee_formation_type == 'mtech') {
                    if ($prg->bid_start_date == null || $prg->bid_end_date == null) {
                        
                    } else {
                        $curr_date = date('Y-m-d', strtotime(date('Y-m-d')));
                        $start_date = date('Y-m-d', strtotime($prg->bid_start_date));
                        $end_date = date('Y-m-d', strtotime($prg->bid_end_date));
                        if (($curr_date >= $start_date) && ($curr_date <= $end_date)) {
                            ?>
                            <li>
                                <a href="{{ url('bid_project') }}" class="waves-effect"><i class="glyphicon glyphicon-plus-sign"></i> <span style="font-size: 14px">Bid Project </span> </a>
                            </li>
                            <?php
                        }
                    }
                }
                ?>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>