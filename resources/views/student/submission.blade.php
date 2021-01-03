@extends('layouts.student')
@section('title')
Events
@endsection
@section('css')
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href ="{{ asset('css/general_user.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert.css') }}"/>

@endsection
@section('content')
<div class="row" style="font-size: 12px">
    <div id="dt" class="col-lg-12" style="align-content: center">
        <!--<div class="card-box table-responsive">-->
        <table id="" class=" display table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="_textcentre" >Sr.No</th>
                    <th class="_textcentre">phase</th>
                    <th class="_textcentre">Subphase</th>
                    <th class="_textcentre">Event Name</th>
                    <th class="_textcentre">Event Description</th>
                    <th class="_textcentre">Deadline</th>
                    <th class="_textcentre">Keywords</th>
                    <th class="_textcentre">Report</th>
                    <th class="_textcentre">Plagiarism Report</th>
                    <th class="_textcentre">Upload</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                $c = 1;
                if ($sub_data != null) {
                    foreach ($sub_data as $sd) {
                        ?>
                        <tr>
                            <td class="text-dark" align="center"><?php echo $c ?></td>
                            <td class="text-dark s_event" id="<?php echo $sd[4]->id ?>" align="center"><?php echo $sd[0] ?></td>
                            <td class="text-dark" align="center"><?php echo $sd[1] ?></td>
                            <td class="text-dark" align="center"><?php echo $sd[2] ?></td>
                            <td class="text-dark" align="center"><?php echo $sd[3] ?></td>
                            <td class="text-dark" align="center"><?php
                                $d = date_create($sd[4]->end_date . " 23:59:59");
                                echo date_format($d, "d/m/Y H:i:s");
                                ?> 
                            </td>
                            <td class="row container">
                        <?php if ($sd[0] == "final_phase" && $sd[1] = "final_subphase" && $sd[2] = "final_report_submission" && $sd[4]->keywords_given == 0) {
                            ?>
                            <button type="button" class="btn btn-facebook report">Add Keywords</button>
                            <?php
                        }
                        ?>
                    </td>

                    <form file="true" enctype="multipart/form-data" action="store_report/<?php echo $sd[4]->event_id ?>" method="POST" class="col-xs-12 form-control form" <?php
                    if ($sd[4]->event->submission == 0) {
                        echo "hidden";
                    }
                    ?> >
                        <td class="text-dark">
                            {{ csrf_field() }}
                            <input class="form-group" type="file" name="report" style="width : 160px">

                        </td>
                        <td>
                            <input class="form-group" type="file" name="plag_report" style="width : 160px">
                        </td>
                        <td>
                            <button type="submit" id="btn_upload" class="btn btn-primary final_report">
                                Upload
                            </button>
                        </td>

                    </form>

                    
                    </tr>
                    <?php
                    $c = $c + 1;
                }
            }
            ?>
            </tbody>
        </table>                
        <!--</div>-->
        <?php if ($sub_data != null) {
            ?>
            <div class="alert alert-info" style="font-size: 15px">
                <ul>
                    <li><?php
                        $t1 = $sd[4]->event->subphase->file_name;
                        $t2 = $sd[4]->event->subphase->file_extension;
                        ?>File name should be <?php echo $student_id."_".$t1 . "." . $t2 ?></li>
                    <li>File size should be less then <?php echo $sd[4]->event->subphase->file_size ?>MB</li>
                </ul>
            </div>
        <div class="alert alert-danger" style="font-size: 15px">
                <ul>
                    <li>This is one time submission. Once done you can't resubmit.
                </ul>
            </div>
        <?php }
        ?>

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


@endsection
@section('js')
<script src="{{ asset('js/sweetalert.js') }}" type="text/javascript"></script>

<script src="{{ asset('js/plugins/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/student.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/plugins/datatables.init.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.js"></script>
@endsection
