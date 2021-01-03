@extends('layouts.coordinator')
@section('title')
Student Details
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.multiselect.css') }}"/>
<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/dataTables.responsive.css"/>-->
<link href="{{ asset('css/jquery.dataTables.css') }}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css"/>

@endsection
@section('content')
<div class="row" style="font-size: 12px">

    <div id="dt" class="col-lg-12" style="align-content: center">
        <!--<div class="card-box table-responsive">-->

        <div class="form-group">

            <select id="table_filter" class="multiselect-ui form-control shc" multiple="multiple">

                <option value="0" data-columnindex="0" checked="true">Sr.No</option>
                <option value="1" data-columnindex="1" checked="true">Student ID</option>
                <option value="3" data-columnindex="3" >Project Topic</option>
                <option value="4" data-columnindex="4" >Area Of Interest</option>
                <option value="5" data-columnindex="5" >Project Type</option>
                <option value="6" data-columnindex="6" >Mentor</option>
                <option value="7" data-columnindex="7" >Project Start Date</option>
                <option value="8" data-columnindex="8" >Duration Completed</option>
                <option value="9" data-columnindex="9" >Action</option>
                <option value="10" data-columnindex="10" >Edit Details</option>
                <option value="11" data-columnindex="11" >Off campus mentor name</option>
                <option value="12" data-columnindex="12" >Off campus mentor contact number</option>
                <option value="13" data-columnindex="13" >Company Name</option>
                <option value="14" data-columnindex="14" >Off campus mentor email</option>
            </select>

        </div>
        <div class="row">

            <div class="col-xs-12">
                <button class="m-b-10 send_mail btn btn-pink pull-right font-13">Send Mail</button>
            </div>

        </div>
        <table id="s_details_datatable" class="display table-responsive table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="_textcentre" >Sr.No</th>
                    <th class="_textcentre">Student ID</th>
                    <th hidden="">student_id</th>
                    <th class="_textcentre">Project Topic</th>
                    <th class="_textcentre">Area Of Interest</th>
                    <th class="_textcentre">Project Type</th>
                    <th class="_textcentre">Mentor</th>
                    <th class="_textcentre">Project StartDate</th>
                    <th class="_textcentre">Duration Completed (in month)</th>
                    <th class="_textcentre" style="width:100px">Action</th>
                    <th class="_textcentre">Edit Detail</th>
                    <th class="_textcentre">Off campus mentor name</th>
                    <th class="_textcentre">Off campus mentor contact number</th>
                    <th class="_textcentre">Company Name</th>
                    <th class="_textcentre">Off campus mentor email</th>
                </tr>
            </thead>

            <tbody style="font-size: 14px;align-content: center">
                <?php
                $c = 1;
                foreach ($students as $student) {
                    ?>
                    <tr>
                        <td id="<?php echo $student->id?>" class="text-dark std_prg_id" align="center"><?php echo $c ?></td>
                        <td id="<?php echo $student->student_id ?>" class="text-dark id" align="center"><?php echo \App\Student::find($student->student_id)->username ?></td>
                        <td hidden=""><?php echo $student->student_id ?></td>
                        <td class="p_t text-dark" align="center"><input style="font-size:13px" hidden class=" p_topic" name="p_topic" value="<?php echo $student->project_topic ?>"><?php echo $student->project_topic ?></td>
                        <td class="p_a text-dark" align="center"><select style="font-size:13px"  hidden class="p_aoi" name="p_aoi">
                                <?php
                                if ($student->area_of_interest != null) {
                                    ?>
                                    <option value="<?php echo $student->area_of_interest ?>"><?php
                                        $a = \App\area_of_interest::find($student->area_of_interest);
                                        echo $a->name;
                                        ?></option>
                                    <?php
                                }
                                ?>
                                <optgroup label="Area of Interest">
                                    <?php
                                    foreach ($area_of_interest as $aoi) {
                                        ?>
                                        <option value="<?php echo $aoi->id ?>"><?php echo $aoi->name ?></option>
                                        <?php
                                    }
                                    ?>
                                </optgroup>
                            </select>
                            <?php if($student->area_of_interest != null){
                                echo \App\area_of_interest::find($student->area_of_interest)->name;
                            }else{
                                echo "";
                            }
                            ?></td>
                        <td class="text-dark" align="center"><?php
                            if ($student->project_type != null) {
                                echo $student->project_type;
                            } else {
                                echo "";
                            }
                            ?></td>
                        <td class="text-dark" align="center"><?php
                            if ($student->mentor_1 != null) {
                                echo "1) " . App\Faculty::find($student->mentor_1)->name;
                            } else {
                                echo "";
                            }
                            ?><br><?php
                            if ($student->mentor_2 != null) {
                                echo "2) " . App\Faculty::find($student->mentor_2)->name;
                            }
                            ?></td>
                        <td class="p_d text-dark" align="center"><input type="date"style="font-size:13px" hidden class=" p_date" name="p_date" value="<?php echo $student->project_start_date ?>"><?php
                            if($student->project_start_date != null){
                                $date = date_create($student->project_start_date);
                                echo date_format($date, "d-m-Y");
                            }else{
                                echo "";
                            }
                            
                            ?></td>
                        <td class="text-dark" align="center"><?php
                            if ($student->project_start_date) {
                                $date1 = date("Y-m-d");
                                $date2 = $student->project_start_date;

                                $ts1 = strtotime($date1);
                                $ts2 = strtotime($date2);

                                $year1 = date('Y', $ts1);
                                $year2 = date('Y', $ts2);

                                $month1 = date('m', $ts1);
                                $month2 = date('m', $ts2);

                                $diff = abs((($year2 - $year1) * 12) + ($month2 - $month1));
                                echo $diff;
                            } else {
                                echo "";
                            }
                            ?></td>
                        <td class="text-dark" align="center">
                            <?php
                            if ($student->status == "pending") {
                                ?>
                                <button class="req_acc btn btn-primary" style="font-size: 13px;align-content: center">Accept</button>
                                <button class="req_rej btn btn-danger"  style="font-size: 13px;align-content: center">Reject</button>
                                <button class="remind_mentor btn btn-pink" style="font-size: 13px;align-content: center">Remind Mentor</button>
                                <?php
                            } else if ($student->status == "a") {
                                ?>
                                <button class="remind_btn btn btn-pink"  style="font-size: 13px;align-content: center">Remind Student</button>
                                <?php
                            } else {
                                ?>
                                Accepted
                                <?php
                            }
                            ?>

                        </td>
                        <td class="text-dark" align="center">
                            <?php
                            if ($student->status != "a") {
                                ?>
                                <button class="edit_btn btn btn-primary"  style="font-size: 13px;align-content: center">Edit</button>
                                <button class="hidden save_btn btn btn-pink"  style="font-size: 13px;align-content: center">Save</button>
                                <?php
                            } else {
                                echo "";
                            }
                            ?>                            
                        </td>
                        <?php
                        if ($student->off_campus_mentor_id == 0) {
                            ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <?php
                        } else {
                            $ocm_d = \App\off_campus_mentor::find($student->off_campus_mentor_id);
                            ?>
                            <td><?php echo $ocm_d->name ?></td>
                            <td><?php echo $ocm_d->contact_number ?></td>
                            <td><?php echo $ocm_d->company_name ?></td>
                            <td><?php echo $ocm_d->email ?></td>
                            <?php
                        }
                        ?>

                    </tr>
                    <?php
                    $c++;
                }
                ?>
            </tbody>

            <tfoot>
                <tr>
                    <th class="_textcentre" >Sr.No</th>
                    <th class="_textcentre">Student ID</th>
                    <th hidden="">student_id</th>
                    <th class="_textcentre">Project Topic</th>
                    <th class="_textcentre">Area Of Interest</th>
                    <th class="_textcentre">Project Type</th>
                    <th class="_textcentre">Mentor</th>
                    <th class="_textcentre">Project StartDate</th>
                    <th class="_textcentre">Duration Completed (in month)</th>
                    <th class="_textcentre" style="width:100px">Action</th>
                    <th class="_textcentre">Edit Detail</th>
                    <th class="_textcentre">Off campus mentor name</th>
                    <th class="_textcentre">Off campus mentor contact number</th>
                    <th class="_textcentre">Company Name</th>
                    <th class="_textcentre">Off campus mentor email</th>
                </tr>
            </tfoot>


        </table>                
        <!--</div>-->



    </div>
</div>
<!--</div>-->
@endsection
@section('js')
<script type="text/javascript">
    $(function () {
        $('.multiselect-ui').multiselect({
        });
    });
</script>

<script type="text/javascript" src="{{ asset('js/datatable.min.js') }}"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.js" type="text/javascript"></script>
<script src="{{ asset('js/dataTables.select.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/plugins/datatables.init.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/jquery.multiselect.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/coordinator_all_student.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/plugins/select2.min.js') }}" type="text/javascript"></script>

<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js" type="text/javascript"></script>

@endsection


