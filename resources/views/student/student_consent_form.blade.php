@extends('layouts.student')
@section('title')
Consent Form
@endsection
@section('css')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href ="{{ asset('css/general_user.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.css"/>
@endsection
@section('content')
<div class="content">
    <div class="row" <?php
    if ($consent_data != null && $consent_data->isRegistered == 0) {
        ?>
             hidden=""
             <?php
         }
         ?> style="font-size: 12px">
        <div id="dt" class="col-lg-12" style="align-content: center">
            <!--<div class="card-box table-responsive">-->
            <table id="datatable" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="_textcentre">ID</th>
                        <th class="_textcentre">Project Topic</th>
                        <th class="_textcentre">Mentor</th>
                        <th class="_textcentre">Project Type</th>
                        <th class="_textcentre">Area of Interest</th>
                        <?php
                        if ($consent_data->subject_expert != null) {
                            ?>
                        <th class="_textcentre">Subject Expert</th>
                            <?php
                        }
                        ?>
                        <th class="_textcentre">Acceptance Status</th>
                        <th class="_textcentre">Evaluation Committee</th>
                        <?php if ($consent_data->off_campus_mentor_id != 0) {
                            ?>
                            <th class="_textcentre">Off Campus Details</th>
                        <?php }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-dark font-13" class="text-dark" align="center"><?php echo $username ?></td>
                        <td class="text-dark font-13" align="center"><?php echo $consent_data->project_topic ?></td>
                        <td class="text-dark font-13" align="center"><?php
                            if ($consent_data->mentor_1 != null) {
                                echo App\Faculty::find($consent_data->mentor_1)->name;
                            }
                            ?></td>
                        <td class="text-dark font-13" align="center"><?php echo $consent_data->project_type ?></td>
                        <td class="text-dark font-13" align="center"><?php 
                        $aoi = $consent_data->area_of_interest;
                        if($aoi != null){
                            echo App\area_of_interest::find($aoi)->name;
                        }
                        
                                ?></td>
                        <?php
                        if ($consent_data->subject_expert != null) {
                            ?>
                        <td id="se_data" class="text-dark font-13" align="center"><?php echo App\Faculty::find($consent_data->subject_expert)->name ?></td>
                            <?php
                        }
                        ?>

                        <td class="text-dark font-13" align="center"><?php echo $consent_data->status ?></td>
                        <td class="text-dark font-13" align="center"><?php
                            if ($consent_data->committee_id == null) {
                                echo "Committee has not been formed";
                            } else {
                                $c_member = \App\committee_faculty::where('committee_id', $consent_data->committee_id)->get();
                                foreach ($c_member as $m) {
                                    echo App\Faculty::find($m->faculty_id)->name . "<br>";
                                }
                            }
                            ?></td>
                        <?php if ($consent_data->off_campus_mentor_id != 0) {
                            ?>
                            <td class="text-dark" align="center"><button class="btn btn-primary font-13" data-target="#offcampus_detail" data-toggle="modal" dstyle="font-size: 15px;align-content: center">Details</button></td>
                        <?php }
                        ?>
                    </tr>
                </tbody>
            </table>                
            <!--</div>-->
        </div>
    </div>
    <?php
    $today=date('Y-m-d');
    $today = date('Y-m-d', strtotime($today));


    $s_date = date('Y-m-d', strtotime($s_date));
    $e_date = date('Y-m-d', strtotime($e_date));
    
    ?>
    <div class="card-box center-block" style="width: 50%;align-content: center" <?php
    if (($today >= $s_date) && ( $today <= $e_date)) {
        
     
        if ($consent_data != null && $consent_data->isRegistered == 1) {
            ?>
                 hidden=""
                 <?php
             }
         }
         else{
             ?>
              hidden = ""
              <?php 
         }
         ?> >
        <div class="title">
            <b><h4 class="text-center font-weight-bold" >Consent Form</h4></b>
        </div>
        <div class="panel-body">
            <form class="form-horizontal m-t-20" style="font-size: 12px" action="/register" method="post">
                {{ csrf_field() }}

                <div class="form-group">
                    <div class="col-xs-12">
                        <input readonly name = "username" style="font-size: 16px" class="form-control" type="text" required="" value="<?php echo Auth::guard('student')->user()->username ?>">
                    </div>
                </div>

                <div class="form-group" style="font-size: 14px">
                    <div class="col-xs-12">
                        <input name = "project_topic" style="font-size: 16px" class="form-control" type="text" required="" placeholder="Project Topic *">
                    </div>
                </div>

                <div class="form-group" >
                    <div class="col-xs-12" >
                        <select name="area_of_interest" class="form-control select2" style="font-size: 14px; height: 38px">
                            <option value="n">Area of Interest *</option>
                            
                                <?php foreach ($area_of_interest as $aoi) {
                                ?>
                            
                                <option value="<?php echo $aoi->id ?>"><?php echo $aoi->name ?></option>
                            <?php }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" >
                    <div class="col-xs-12" >
                        <select name="mentor_1" class="form-control select2" style="font-size: 14px; height: 38px">
                            <option value="n">Mentor 1 *</option>
                            <optgroup label="Mentors">
                                <?php
                                foreach ($mentors as $m) {
                                    ?>
                                    <option value="<?php echo $m->id ?>"><?php echo $m->name ?></option>
                                    <?php
                                }
                                ?>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="form-group" >
                    <div class="col-xs-12" >
                        <select name="mentor_2" class="form-control select2" style="font-size: 14px; height: 38px">
                            <option value="m2">Mentor 2</option>
                            <optgroup label="Mentors">
                                <?php
                                foreach ($mentors as $m) {
                                    ?>
                                    <option value="<?php echo $m->id ?>"><?php echo $m->name ?></option>
                                    <?php
                                }
                                ?>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-xs-12">
                        <input style="font-size: 14px"  name="project_start_date" class="off form-control" type="text" onfocus="(this.type = 'date')" onblur="(this.type = 'text')"  required placeholder="Project Start Date  *">
                    </div>
                </div>
                <div class="form-group-lg">
                    <label class="form-horizontal">Project Type *</label><br>
                    <div class="radio radio-info radio-inline">

                        <input class="_on" type="radio" id="project_type_on" value="on_campus" name="project_type">
                        <label class="_on" for="project_type_on"> On campus </label>
                    </div>

                    <div class="radio radio-info radio-inline">
                        <input class="_off" type="radio" id="project_type_off" value="off_campus" name="project_type" >
                        <label class="_off" for="project_type_off"> Off campus </label>
                    </div>
                </div>

                <!---------------------------------------------------------------------------------------------------------------->
                <div hidden class="off_hide">
                    <div class="title m-t-10 m-b-10">
                        <b><h4 class="text-center font-weight-bold" >Off Campus Mentor Details</h4></b>
                    </div>
                    <div class="form-group row m-t-10">
                        <div class="col-xs-12" >
                            <input name="m_name" style="font-size: 14px" class=" off form-control" type="text" placeholder="Off Campus Mentor Name *">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-xs-12">
                            <input style="font-size: 14px" data-validation="alphanumeric" name="contact_number" class="off form-control" type="text" placeholder="Contact Number *">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-xs-12">
                            <input style="font-size: 14px" data-validation="email" name="email" class="off form-control" type="email" placeholder="Off campus Mentor Email *">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-xs-12">
                            <input style="font-size: 14px" name="company_name" class="off form-control" type="text" placeholder="Company Name *" >
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-xs-12">
                            <input style="font-size: 14px" name="project_duration" class="off form-control" type="text" placeholder="Project duration(months) *">
                        </div>
                    </div>


                </div>
                <!--                -------------------------------------------------------------------------------------------------------------->
                <div class="row center-block m-t-10">
                    <div class="col-md-4 center-block">
                        <span><input type="submit" class="btn btn-primary center-block" value="Register" name="register" style="font-size: 15px"></span>
                    </div>
                </div>

                <div class="form-group-lg">
                    <label class="form-group">
                        * fields are Required
                    </label>
                </div>

            </form>


        </div>

    </div>
    <?php
    if ($ocm != null) {
        ?>
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
                    <div class="modal-body">
                        <div class="form-group row m-t-10">
                            <div class="col-xs-5" >
                                <span name="m_name" class="font-bold" style="font-size: 14px" type="text">Off campus Mentor's Name</span>
                            </div>
                            <div class="col-xs-7" >
                                <span name="m_name" style="font-size: 14px" class=" off form-control" type="text" placeholder="Off Campus Mentor Name *"><?php echo $ocm->name ?></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-5" >
                                <span name="m_name" class="font-bold" style="font-size: 14px" type="text">Contact Number</span>
                            </div>
                            <div class="col-xs-7" >
                                <span name="m_name" style="font-size: 14px" class=" off form-control" type="text" placeholder="Off Campus Mentor Name *"><?php echo $ocm->contact_number ?></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-5" >
                                <span name="m_name" class="font-bold" style="font-size: 14px" type="text">Email</span>
                            </div>
                            <div class="col-xs-7" >
                                <span name="m_name" style="font-size: 14px" class=" off form-control" type="text" placeholder="Off Campus Mentor Name *"><?php echo $ocm->email ?></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-5" >
                                <span name="m_name" class="font-bold" style="font-size: 14px" type="text">Company Name</span>
                            </div>
                            <div class="col-xs-7" >
                                <span name="m_name" style="font-size: 14px" class=" off form-control" type="text" placeholder="Off Campus Mentor Name *"><?php echo $ocm->company_name ?></span>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer col-md-12">
                        <button type="button" class="font-13 center-block btn btn-primary" data-dismiss="modal">OK</button>
                    </div>
                </div>

            </div>
        </div>
        <?php
    }
    ?>

</div>
@if ($errors->any())
<div class="alert alert-danger" style="font-size: 15px ">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
</div>

@endsection
@section('js')

<script src="{{ asset('js/plugins/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/student.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/plugins/datatables.init.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.js"></script>
@endsection
