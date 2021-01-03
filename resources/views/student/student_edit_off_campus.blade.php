@extends('layouts.student')

@section('title')
Edit Off Campus Details
@endsection

@section('css')
@endsection

@section('content')
<div class="content">
    <div class="row" style="font-size: 12px">
        <div id="dt" class="col-lg-12" style="align-content: center">
            <?php
            if ($off_data == null) {
                ?>
            <h1 class="text-center"> You are On Campus student.So
                you don't have off campus details.
            </h1>
                <?php
            } else {
                ?>
                <h4 class="text-center text-dark m-b-10">Edit Off campus Details</h4>

                <form class="form-horizontal" method="post" action="/edit_off_details">
                    {{ csrf_field() }}
                    <input name="ocm_id" hidden="" value="<?php echo $ocm_id?>">
                    <div class="edit_details" >
                    <div class="form-group">
                        <div class="col-xs-3">
                            <p class="font-bold text-center m-t-5" style="font-size: 14px" type="text">Off Campus Mentor Name</p>
                        </div>
                        <div class="col-xs-9">
                            <input   name = "ocm_name" style="font-size: 14px" class="form-control" type="text" required="" value="<?php echo $off_data->name ?>" >
                        </div>

                    </div>
                    <div class="form-group ">
                        <div class="col-xs-3">
                            <p class="font-bold text-center m-t-5" style="font-size: 14px" type="text">Off Campus Mentor Contact Number</p>
                        </div>
                        <div class="col-xs-9 ">
                            <input   name = "ocm_number" style="font-size: 14px" class="form-control" type="text" required="" value="<?php echo $off_data->contact_number ?>" >
                        </div>

                    </div>

                    <div class="form-group ">
                        <div class="col-xs-3">
                            <p class="font-bold text-center m-t-5" style="font-size: 14px" type="text">Company Name</p>
                        </div>
                        <div class="col-xs-9 ">
                            <input   name = "ocm_company" style="font-size: 14px" class="form-control" type="text" required="" value="<?php echo $off_data->company_name ?>" >
                        </div>

                    </div>

                    <div class="form-group ">
                        <div class="col-xs-3">
                            <p class="font-bold text-center m-t-5" style="font-size: 14px" type="text">Off Campus Mentor Email Address</p>
                        </div>
                        <div class="col-xs-9 ">
                            <input   name = "ocm_email" style="font-size: 14px" class="form-control" type="email" required="" value="<?php echo $off_data->email ?>" >
                        </div>

                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-purple  font-13 text-center edit_button" type="button">Edit Details</button>
                    </div>

                    <div class="form-group text-center">
                        <button hidden class="btn btn-flickr  font-13 text-center submit_button" type="submit">Submit Details</button>
                    </div>
                    </div>
                </form>
                <?php
            }
            ?>
            
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/student_edit_off_details.js') }}" type="text/javascript"></script>

@endsection
