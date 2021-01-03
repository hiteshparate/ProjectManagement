@extends('layouts.coordinator')
@section('title')
Committee Management
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>
@endsection

@section('content')
<div class="container">
    <div class="row" style="font-size: 12px">
        <div id="dt" class="col-lg-12 form-horizontal" style="align-content: center">
            <div class="card-box container" style="background-color: rgb(247,247,241)">

                <h2 class="text-center" >Faculty Nomination Form for Subject Expert</h2>
                <div class="form-group m-t-10">

                    <div class="col-xs-3">
                        <p class="font-13 text-center font-bold m-t-10" > Start Date</p>
                    </div>
                    <div class="col-xs-3">
                        <input type="date" class="form-control font-13 s_date font-bold" name="start_date" <?php if ($program->se_n_start_date != null) {
   ?>
                               value="<?php echo $program->se_n_start_date ?>" disabled=""
                               <?php }
                               ?>>
                    </div>

                    <div class="col-xs-3">
                        <p class="font-13 text-center font-bold m-t-10" for="start_date"> End Date</p>
                    </div>
                    <div class="col-xs-3">
                        <input type="date" class="form-control font-13 e_date font-bold" name="end_date" <?php if ($program->se_n_end_date != null) {
                                   ?>
                                   value="<?php echo $program->se_n_end_date ?>" disabled=""
                               <?php }
                               ?>>
                    </div>

                </div>
                <div class="form-group text-center ">
                    <button class="btn btn-primary font-13 start_com" <?php
                    if ($program->se_n_start_date != null && $program->se_n_end_date != null) {
                        echo "hidden";
                    }
                    ?>>Start</button>
                    <button class="btn btn-pink font-13 edit_com" <?php
                    if ($program->se_n_start_date != null && $program->se_n_end_date != null) {
                        
                    } else {
                        echo "hidden";
                    }
                    ?>>Edit</button>
                    <button hidden="" class="btn btn-purple btn-rounded save_com" style="font-size: 15px">Save</button>


                </div>
            </div>
            <br><br><br><br>
            <div class="card-box container" style="background-color: rgb(247,247,241)">
                <h2 class="text-center" >Bidding for committee Member Form</h2>

                <div class="form-group m-t-10">

                    <div class="col-xs-3">
                        <p class="font-13 text-center font-bold m-t-10" > Start Date</p>
                    </div>
                    <div class="col-xs-3">
                        <input type="date" class="form-control font-13 font-bold bid_s_date" name="bid_start_date"
                        <?php if ($program->bid_start_date != null) {
                            ?>
                                   value="<?php echo $program->bid_start_date ?>" disabled=""
                               <?php }
                               ?>>
                    </div>

                    <div class="col-xs-3">
                        <p class="font-13 text-center font-bold m-t-10"> End Date</p>
                    </div>
                    <div class="col-xs-3">
                        <input type="date" class="form-control font-13 font-bold bid_e_date" name="bid_end_date" 
                        <?php if ($program->bid_end_date != null) {
                            ?>
                                   value="<?php echo $program->bid_end_date ?>" disabled=""
                               <?php }
                               ?>>
                    </div>

                </div>
                <div class="form-group">
                    <div class="col-xs-3">
                        <p class="font-13 text-center font-bold m-t-10" >Min. limit for Bid</p>
                    </div>
                    <div class="col-xs-3">
                        <input type="number" class="form-control font-13 font-bold bid_min_limit" name="bid_min_limit"
                        <?php if ($program->bid_min_limit != null) {
                            ?>
                                   value="<?php echo $program->bid_min_limit ?>" disabled=""
                               <?php }
                               ?>>
                    </div>
                    <div class="col-xs-3">
                        <p class="font-13 text-center font-bold m-t-10" >Max. limit for Bid</p>
                    </div>
                    <div class="col-xs-3">
                        <input type="number" class="form-control font-13 font-bold bid_max_limit" name="bid_max_limit"
                        <?php if ($program->bid_max_limit != null) {
                            ?>
                                   value="<?php echo $program->bid_max_limit ?>" disabled=""
                               <?php }
                               ?>>
                    </div>

                </div>
                <div class="form-group text-center ">
                    <button class="btn btn-primary font-13 bid_start"
                    <?php
                    if ($program->bid_start_date != null && $program->bid_end_date != null && $program->bid_min_limit != null && $program->bid_max_limit != null) {
                        echo "hidden";
                    }
                    ?>>Start</button> 

                    <button  class="btn btn-pink font-13 bid_edit"
                    <?php
                    if ($program->bid_start_date != null && $program->bid_end_date != null && $program->bid_min_limit != null && $program->bid_max_limit != null) {
                        
                    } else {
                        echo "hidden";
                    }
                    ?>>Edit</button>
                    <button hidden="" class="btn btn-purple btn-rounded bid_save" style="font-size: 15px">Save</button>


                </div>

            </div>

        </div>
    </div>

</div>
@endsection

@section('js')
<script src="{{ asset('js/coordinator_manage_committee.js') }}" type="text/javascript"></script>

@endsection