@extends('layouts.faculty')

@section('title')
Nomination for committee
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>

@endsection
@section('content')

<div  class="container">	
    <ul  class="nav nav-pills  justify-content-center font-13 text-primary">
        <li class="active">
            <a  href="#send_request" data-toggle="tab">Nominate Subject Expert</a>
        </li>
        <li>
            <a href="#get_request" data-toggle="tab">Subject Expert requests</a>
        </li>
    </ul>

    <div class="tab-content ">
        <div class="tab-pane active m-t-10" id="send_request">
            <div class="row" style="font-size: 12px">
                <div id="m_dt" class="col-lg-12" style="align-content: center">

                    <table id="se_nominate" class="display table table-striped table-bordered dataTable" >
                        <thead>
                            <tr>
                                <th class="_textcentre" >Sr.No</th>
                                <th class="_textcentre">Student ID</th>
                                <th class="_textcentre">Area Of Interest</th>
                                <th class="_textcentre">Project Type</th>
                                <th class="_textcentre">Project Topic</th>
                                <th class="_textcentre">Request Status</th>
                                <th class="_textcentre">Nominee</th>
                            </tr>
                        </thead>

                        <tbody style="font-size: 14px;align-content: center">
                            <?php
                            $c = 1;
                            foreach ($students as $std) {
                                ?>
                                <tr>
                                    <td class="text-dark" align="center"><?php echo $c ?></td>
                                    <td id="<?php echo $std->id ?>" class="text-dark p_id" align="center"><?php echo App\Student::find($std->student_id)->username ?></td>
                                    <td class="text-dark" align="center"><?php $aoi_id = $std->area_of_interest;
                                                                                echo App\area_of_interest::find($aoi_id)->name?></td>
                                    <td id="" class="text-dark" align="center"><?php echo $std->project_type ?></td>
                                    <td id="" class="text-dark" align="center"><?php echo $std->project_topic ?></td>
                                    <td id="" class="text-dark status" align="center"><?php 
                                    if($std->subject_expert == null){
                                        echo "select nominee";
                                    }else if($std->is_final_se == 0){
                                        echo "pending";
                                    }else{
                                        echo "accepted";
                                    }
                                    ?></td>
                                    <td id="" class="text-dark" align="center">
                                        <?php
                                        if ($std->subject_expert != null) {
                                            echo \App\Faculty::find($std->subject_expert)->name;
                                        } else {
                                            ?>
                                            <select class="select2 nominations">
                                                <option value="n">Select Faculty</option>
                                                <?php
                                                foreach ($faculty as $f) {
                                                    ?>
                                                    <option value="<?php echo $f->id ?>"><?php echo $f->name ?> </option>
                                                <?php }
                                                ?>
                                            </select><br>
                                            <button class="nominate btn btn-pink m-t-5 font-weight-bold font-13">nominate</button>
                                            <?php
                                        }
                                        ?>

                                    </td>
                                </tr>
                                <?php
                                $c = $c + 1;
                            }
                            ?>

                        </tbody>



                    </table>



                </div>
            </div>
        </div>
        <div class="tab-pane m-t-10" id="get_request">
            <div class="row" style="font-size: 12px">
                <div id="c_dt" class="col-lg-12" style="align-content: center">

                    <table id="se_request" class="display table table-striped table-bordered dataTable" >
                        <thead>
                            <tr>
                                <th class="_textcentre">Sr.No</th>
                                <th class="_textcentre">Student ID</th>
                                <th class="_textcentre">Mentor Name</th>
                                <th class="_textcentre">Area Of Interest</th>
                                <th class="_textcentre">Project Type</th>
                                <th class="_textcentre">Project Topic</th>
                                <th class="_textcentre">Action</th>
                            </tr>
                        </thead>

                        <tbody style="font-size: 14px;align-content: center">
                            <?php
                            $c = 1;
                            foreach ($nomination_request as $se_req) {
                                ?>
                                <tr>
                                    <td id="" class="text-dark" align="center"><?php echo $c ?></td>
                                    <td id="<?php echo $se_req->id ?>" class="text-dark stdid" align="center"><?php echo App\Student::find($se_req->student_id)->username ?></td>
                                    <td id="" class="text-dark" align="center"><?php echo App\Faculty::find($se_req->mentor_1)->name ?></td>
                                    <td id="" class="text-dark" align="center"><?php $a_id =  $se_req->area_of_interest;
                                                                                        echo App\area_of_interest::find($a_id)->name;?></td>
                                    <td id="" class="text-dark" align="center"><?php echo $se_req->project_type ?></td>
                                    <td id="" class="text-dark" align="center"><?php echo $se_req->project_topic ?></td>
                                    <td id="" class="text-dark" align="center">
                                        <?php
                                        if ($se_req->is_final_se == 1) {
                                            echo "accepted";
                                        } else {
                                            ?>
                                            <button class="se_acc btn btn-primary font-13">accept</button>
                                            <button class="se_rej btn btn-danger font-13">reject</button>
                                            <?php
                                        }
                                        ?>


                                    </td>
                                </tr>
                                <?php
                                $c = $c + 1;
                            }
                            ?>

                        </tbody>



                    </table>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection
@section('js')
<script src="{{ asset('js/faculty_subject_expert_nomination.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.js"></script>
@endsection
