@extends('layouts.faculty')
@section('title')
Project Details
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.css"/>
@endsection

@section('content')

<div class="row">
    <div class="container col-xs-12 col-lg-12 col-md-12" style="font-size: 15px"> 
         
        <div class="tab-content m-t-20"> 
            <div class="tab-pane active row" id="mentor"> 
                <div id="dt" class="col-lg-12" style="align-content: center">
                    <!--<div class="card-box table-responsive">-->
                    <table id="datatable" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="_textcentre" >Sr.No</th>
                                <th class="_textcentre">Student ID</th>
                                <th class="_textcentre">Phase</th>
                                <th class="_textcentre">Subphase</th>
                                <th class="_textcentre">Event name</th>
                                <th class="_textcentre">Report</th>
                                <th class="_textcentre">Action</th>
                                <th class="_textcentre">Manual Upload</th>
                            </tr>
                        </thead>

                        <tbody style="font-size: 15px;align-content: center">
                            <?php
                            $c = 1;
                            $off_data = null;
                            foreach ($event_data as $student_event) {
                                foreach ($student_event as $event) {
                                    ?>
                                    <tr>
                                        <td class="text-dark" class="text-dark" align="center"><?php echo $c ?></td>
                                        <td id="<?php echo $event->student_id ?>" class="text-dark id" align="center"><?php echo \App\Student::find($event->student_id)->username ?></td>
                                        <td class="text-dark" align="center"><?php echo $event->event->subphase->phase->phase_name ?></td>
                                        <td class="text-dark" align="center"><?php echo $event->event->subphase->name ?></td>
                                        <td id="<?php echo $event->event->id ?>" class="e_id text-dark" align="center"><?php echo $event->event->name ?></td>
                                        <td class="text-dark" align="center"><?php
                                            if ($event->report_location == null) {
                                                echo "Report not yet Submitted";
                                            } else {
                                                ?>
                                                <a target="_blank" href="view_report/<?php echo $event->event_id . "/$event->student_id" ?>"><span>View Report</span></a>
                                            <?php }
                                            ?></td>
                                        <td class="text-dark" align="center">
                                            <?php
                                            if ($event->status == "submitted" && $event->isAccepted == 0) {
                                                ?>
                                                <button class="req_acc btn btn-primary" style="font-size: 15px;align-content: center">Accept</button>
                                                <button class="req_rej btn btn-danger"  style="font-size: 15px;align-content: center">Reject</button>
                                                <?php
                                            } else if ($event->status == "accepted" && $event->isAccepted == 1) {
                                                echo "Accepted";
                                            } else {
                                                echo "Report not yet Submitted";
                                            }
                                            ?>

                                        </td>
                                        <td class="text-dark">
                                            <div class="row container">
                                                <form file="true" enctype="multipart/form-data" action="store_report/<?php echo $event->event_id . "/$event->student_id" ?>"   method="POST" class="col-xs-12 form-control form" >
                                                    {{ csrf_field() }}
                                                    <input class="col-xs-9" type="file" name="report"><button type="submit" class="col-xs-3 btn btn-primary">Upload</button>
                                                    
                                                </form>
                                            </div>

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
                </div>
            </div> 
             

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
</div>

@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/sweetalert2.js') }}"></script>
<script src="{{ asset('js/faculty_project_details.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/faculty.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/plugins/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/plugins/datatables.init.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.js"></script>

@endsection