
@extends('layouts.faculty')
@section('title')
Bid Requests
@endsection

@section('css')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.css"/>
<link href="{{ asset('css/jquery.dataTables.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

<div class="row">
    <div class="container col-xs-12 col-lg-12 col-md-12" style="font-size: 15px"> 

        <div class="tab-content m-t-20"> 
            <div class="tab-pane active row" id="mentor"> 
                <div id="dt" class="col-lg-12" style="align-content: center">
                    <div class="row">
                        <div class="col-xs-2">
                            <p class="font-bold text-center">Min. Limit for bid</p>
                        </div>
                        <div class="col-xs-2">
                            <input id="min" class="form-control font-bold font-13" disabled="" value="<?php echo $min_bid ?>">
                        </div>
                        <div class="col-xs-2">
                            <p class="font-bold text-center">Max. Limit for bid</p>
                        </div>
                        <div class="col-xs-2">
                            <input id="max" class="form-control font-bold font-13" disabled="" value="<?php echo $max_bid ?>">
                        </div>
                        <div class="col-xs-2">
                            <p class="font-bold text-center">No. of bids done</p>
                        </div>
                        <div class="col-xs-2">
                            <input id="tot" class="form-control font-bold font-13" disabled="" value="<?php echo $no_of_bid ?>">
                        </div>

                    </div>
                    <div class="form-group m-t-5">
                        <p class="font-bold text-center">To select multiple students for bidding just click on students and hit Bid Projects button
                        </p>
                    </div>
                    <table id="faculty_bid_datatable" class="display table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="_textcentre">Sr.No</th>
                                <th hidden=""></th>
                                <th class="_textcentre">Student ID</th>
                                <th class="_textcentre">Project Topic</th>
                                <th class="_textcentre">Area Of Interest</th>
                                <th class="_textcentre">Project Type</th>
                                <th class="_textcentre">Mentor</th>
                                <th class="_textcentre">Status</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 15px;align-content: center">
                            <?php
                            $c = 1;
                            foreach ($students as $std) {
                                ?>
                                <tr>
                                    <td class="text-dark text-center"><?php echo $c ?></td>
                                    <td hidden=""><?php echo $std->id ?></td>
                                    <td id="<?php echo $std->id ?>" class="text-dark text-center std_prg_id"><?php echo App\Student::find($std->student_id)->username ?></td>
                                    <td class="text-dark text-center"><?php echo $std->project_topic ?></td>
                                    <td class="text-dark text-center"><?php $a= $std->area_of_interest;
                                                                            echo App\area_of_interest::find($a)->name ?></td>
                                    <td class="text-dark text-center"><?php echo $std->project_type ?></td>
                                    <td class="text-dark text-center"><?php echo App\Faculty::find($std->mentor_1)->name ?></td>
                                    <td class="text-dark text-center">
                                        <?php
                                        $tmp = \App\bid_request::where(['std_prg_id' => $std->id, 'faculty_id' => $mentor])->first();
                                        if ($tmp == null) {
                                            echo "";
                                        } else {
                                            echo "done bidding";
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
                <div class="form-group text-center">
                    <button class="btn btn-primary font-weight-bold bid_projects" style="font-size: 15px">Bid Projects</button>
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
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.js"></script>
<script src="{{ asset('js/dataTables.select.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/faculty_bid_projects.js') }}" type="text/javascript"></script>

@endsection