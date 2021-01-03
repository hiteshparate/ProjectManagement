@extends('layouts.faculty')
@section('title')
Mentor Request
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>
@endsection
@section('content')
<div class="row" style="font-size: 12px">
    <div id="dt" class="col-lg-12" style="align-content: center">
        <!--<div class="card-box table-responsive">-->
        <table id="datatable" class="display table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="_textcentre" >Sr.No</th>
                    <th class="_textcentre">Student ID</th>
                    <th class="_textcentre">Project Topic</th>
                    <th class="_textcentre">Area Of Interest</th>
                    <th class="_textcentre">Project Type</th>
                    <th class="_textcentre">Project Start Date</th>
                    <th class="_textcentre">Group</th>
                    <th class="_textcentre">Off Campus Details </th>
                    <th class="_textcentre" style="width:100px">Action</th>
                    <th class="_textcentre">Edit Detail</th>

                </tr>
            </thead>

            <tbody style="font-size: 14px;align-content: center">
                <?php
                $c = 1;
                $off_data = null;
                foreach ($faculty_data as $data) {
                    foreach ($data as $d) {
                        ?>
                        <tr class="">
                            <td class="text-dark" align="center"><?php echo $c ?></td>
                            <td id="<?php echo $d->student_id ?>" class="text-dark id" align="center"><?php echo \App\Student::find($d->student_id)->username ?></td>
                            <td class="p_t text-dark" align="center"><input style="font-size:13px" hidden class=" p_topic" name="p_topic" value="<?php echo $d->project_topic ?>"><?php echo $d->project_topic ?></td>
                            <td class="p_a text-dark" align="center"><select style="font-size:13px"  hidden class="p_aoi" name="p_aoi">
                                    <option value="<?php echo $d->area_of_interest ?>"><?php
                                        $aoi_id = $d->area_of_interest;
                                        $aoi = \App\area_of_interest::find($aoi_id);
                                        echo $aoi->name;
                                        ?></option>
                                    <optgroup label="Area of Interest">
                                        <?php foreach ($area_of_interest as $aoi) {
                                            ?>
                                            <option value="<?php echo $aoi->id ?>"><?php echo $aoi->name ?></option>

                                        <?php }
                                        ?>
                                    </optgroup>
                                </select>
                                <?php
                                $aoi_id = $d->area_of_interest;
                                $aoi = \App\area_of_interest::find($aoi_id);
                                echo $aoi->name;
                                ?></td>
                            <td class="text-dark" align="center"><?php echo $d->project_type ?></td>
                            <td class="p_d text-dark" align="center"><input type="date"style="font-size:13px" hidden class=" p_date" name="p_date" value="<?php echo $d->project_start_date ?>"><?php echo $d->project_start_date ?></td>
                            <td class="text-dark" align="center"><?php
                                if ($d->groups()->where(['program_id' => $p_id, 'mentor_id' => $mentor])->first() != null) {
                                    echo $d->groups()->where(['program_id' => $p_id, 'mentor_id' => $mentor])->first()->name;
                                    //echo $tt->name;
                                } else {
                                    echo "";
                                }
                                ?></td>

                            <?php if ($d->off_campus_mentor_id != 0) {
                                ?>
                                <td  class="text-dark" align="center"><button id="<?php echo $d->off_campus_mentor_id ?>" class="off btn btn-primary" data-target="#offcampus_detail" data-toggle="modal" style="font-size: 13px;align-content: center">Details</button></td>
                            <?php } else {
                                ?>
                                <td></td>
                                <?php
                            }
                            ?>
                            <td class="text-dark" align="center">
                                <?php
                                if ($d->status == "pending") {
                                    ?>
                                    <button class="req_acc btn btn-primary" style="font-size: 13px;align-content: center">Accept</button>
                                    <button class="req_rej btn btn-danger"  style="font-size: 13px;align-content: center">Reject</button>
                                    <?php
                                } else {
                                    ?>
                                    Accepted
                                    <?php
                                }
                                ?>

                            </td>
                            <td class="text-dark" align="center">
                                <button class="edit_btn btn btn-primary"  style="font-size: 13px;align-content: center">Edit</button>
                                <button class="hidden save_btn btn btn-pink"  style="font-size: 13px;align-content: center">Save</button>
                            </td>

                        </tr>
                        <?php
                        $c = $c + 1;
                    }
                    ?>

                    <?php
                }
                ?>
            </tbody>



        </table>                
        <!--</div>-->
        <?php ?>
        <div class="modal" id="offcampus_detail" role="dialog" style="
             ">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="col-xs-12">
                            <p class="_textcentre" style="text-align: center">Off campus mentor Details</p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal_data">

                    </div>
                    <div class="modal-footer col-md-12">
                        <button type="button" class="font-13 center-block btn btn-primary" data-dismiss="modal">OK</button>
                    </div>
                </div>

            </div>
        </div>
        <div class="modal" id="group_modal" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="col-xs-12">
                            <p class="_textcentre" style="text-align: center">Assign Group</p>
                        </div>
                        <button id="group_dismiss" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal_data">

                        <div class="form-group ">
                            <label class="row font-13" >Available groups:</label>
                            <div class="row m-t-10" > 
                                <select id="group_select" name="role" class="form-control  font-13 " style="height: 35px">
                                    <option value="select">Create a new Group</option>
                                    <?php foreach ($groups as $g) {
                                        ?>
                                        <option value="<?php echo $g ?>"><?php echo $g ?></option>
                                    <?php }
                                    ?>

                                </select>  
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="g_name" class="row m-t-15 font-13">
                                Group Name :
                            </label>
                            <div id="g_name" class="row">
                                <input id="g_n" placeholder="Enter Group Name" name="g_name" class="form-control m-t-5 font-13">
                            </div>
                            <div >
                                <button id="submit_group" class="btn btn-primary pull-right m-t-15 font-13">Create Group/Add student</button>
                            </div>
                        </div>

                    </div>


                </div>

                <div>

                </div>

            </div>

        </div>
    </div>
</div>
</div>
@endsection
@section('js')
<script>
    window.mentor_id = <?php echo Auth::guard('faculty')->id() ?>
</script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.js"></script>
<!--<script src="{{ asset('js/plugins/editable/jquery.dataTables.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/plugins/editable/dataTables.bootstrap.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/plugins/editable/datatables.editable.init.js') }}" type="text/javascript"></script>-->
<script src="{{ asset('js/plugins/datatables.init.js') }}" type="text/javascript"></script>



<script src="{{ asset('js/faculty.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/mentor_request.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/plugins/select2.min.js') }}" type="text/javascript"></script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->


@endsection


