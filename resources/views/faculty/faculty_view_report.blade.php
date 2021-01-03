@extends('layouts.no_leftbar_layout')
@section('title')
View Report
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>
@endsection

@section('content')

<div class="content">
    <div class="row">
        <div id="dt" class="col-lg-12" style="align-content: center">
            <div class="form-horizontal">
                <h1 class="text-center">Student Reports</h1>

                <div class="form-group m-t-10">
                    <div class="col-xs-3">
                        <p class="font-bold text-center" style="font-size: 14px" type="text">Student ID</p>
                    </div>
                    <div class="col-xs-9">
                        <input id="std_id" readonly name = "username" style="font-size: 16px" class="form-control" type="text" required="" value="<?php echo $std_id ?>">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-3">
                        <p class="font-bold text-center" style="font-size: 14px" type="text">Phase Name</p>
                    </div>
                    <div class="col-xs-9">
                        <input  readonly name = "phase_name" style="font-size: 16px" class="form-control" type="text"  value="<?php echo $phase_name ?>" >
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-3">
                        <p class="font-bold text-center" style="font-size: 14px" type="text">Subphase Name</p>
                    </div>
                    <div class="col-xs-9">
                        <input  readonly name = "subphase_name" style="font-size: 16px" class="form-control" type="text" value="<?php echo $subphase_name ?>" >
                    </div>
                </div>

                <table id="events" class="display table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="_textcentre" >Sr.No</th>
                            <th class="_textcentre">Event</th>
                            <th class="_textcentre">View Report</th>
                            <th class="_textcentre">View Plagiarism Report</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 15px;align-content: center">
                        <?php
                        $c = 1;
                        foreach ($event_show as $event) {
                            foreach ($event as $e) {
                                ?>
                                <tr>
                                    <td class="text-dark text-center"><?php echo $c ?></td>
                                    <td class="text-dark text-center"><?php echo $e->event->name ?></td>
                                    <td class="text-dark" align="center"><?php
                                        if ($e->status == "pending") {
                                            echo "Report not yet Submitted";
                                        } else if ($e->status == "submitted") {
                                            echo "Mentor has not accepted the report yet";
                                        } else {
                                            ?>
                                            <a target="_blank" href="/view_report/<?php echo $e->event_id . "/$e->student_id" ?>"><span>View Report</span></a>
                                        <?php }
                                        ?></td>
                                    <td class="text-dark" align="center">
                                        <?php 
                                        if($e->plagiarism_report != null){
                                            ?>
                                        <a target="_blank" href="/view_plag_report/<?php echo $e->event_id . "/$e->student_id" ?>"><span>View Plagiarism Report</span></a>
                                        <?php
                                        }
                                        ?>
                                        
                                    </td>
                                    
                                </tr>
                                        <?php
                                    }
                                    ?>
                            <?php
                            $c++;
                        }
                        ?>
                    </tbody>

                </table>

            </div>




        </div>

    </div>
    @if ($errors->any())
    <div class="alert alert-danger" style="font-size: 15px ">
        <ul class="">
            @foreach ($errors->all() as $error)
            <li class="">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
@endsection

@section('js')

<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.js" type="text/javascript"></script>

<script src="{{ asset('js/plugins/datatables.init.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/plugins/select2.min.js') }}" type="text/javascript"></script>


@endsection


