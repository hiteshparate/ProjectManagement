@extends('layouts.coordinator')
@section('title')
Committees
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/jquery.multiselect.css') }}"/>
<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.1/css/dataTables.responsive.css"/>-->

@endsection
@section('content')
<div class="row" style="font-size: 12px">

    <div id="dt" class="col-lg-12" style="align-content: center">
        <!--<div class="card-box table-responsive">-->


        <table id="committee_datatable" class="display table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="_textcentre" >Sr.No</th>
                    <th class="_textcentre">Student ID</th>
                    <th class="_textcentre">Project Topic</th>
                    <th class="_textcentre">Area Of Interest</th>
                    <th class="_textcentre">Project Type</th>
                    <th class="_textcentre">Mentor</th>
                    <th class="_textcentre">Subject Expert</th>
                    <th class="_textcentre">Bid members</th>
                    <th class="_textcentre">Committee Members</th>
                    <th class="_textcentre">Edit subject expert/ mentor</th>
                </tr>
            </thead>

            <tbody style="font-size: 14px;align-content: center">
                <?php
                $c = 1;
                foreach ($students as $student) {
                    ?>
                    <tr>
                        <td class="text-dark" align="center"><?php echo $c ?></td>
                        <td id="<?php echo $student["std_prg"]->id ?>" class="text-dark std_prg_id" align="center"><?php echo App\Student::find($student["std_prg"]->student_id)->username ?></td>
                        <td class="text-dark" align="center"><?php echo $student["std_prg"]->project_topic ?></td>
                        <td class="text-dark" align="center"><?php echo App\area_of_interest::find($student["std_prg"]->area_of_interest)->name ?></td>
                        <td class="text-dark" align="center"><?php echo $student["std_prg"]->project_type ?></td>
                        <td class="text-dark men" align="center"><?php
                            if ($student["std_prg"]->mentor_2 != null) {
                                echo "1) " . App\Faculty::find($student["std_prg"]->mentor_1)->name . "<br>" . "2) " . App\Faculty::find($student["std_prg"]->mentor_2)->name;
                            } else {
                                echo "1) " . App\Faculty::find($student["std_prg"]->mentor_1)->name;
                            }
                            ?></td>
                        <td class="text-dark se_td" align="center"><?php
                        if($student["std_prg"]->subject_expert == null){
                            ?>
                            <button class="btn btn-pink se_reminder font-weight-bold">Remind mentor</button>
                            <?php
                        }else{
                            if($student["std_prg"]->is_final_se == 0){
                                ?>
                                <button class="btn btn-pink se_re font-weight-bold">Remind subject Expert</button>
                                <button class="btn btn-primary acc_se font-weight-bold m-t-5">Accept on behalf of <?php echo \App\Faculty::find($student["std_prg"]->subject_expert)->name?></button>
                                <button class="btn btn-danger rej_se font-weight-bold m-t-5">Reject on behalf of <?php echo \App\Faculty::find($student["std_prg"]->subject_expert)->name?></button>
                                <?php
                            }else{
                                echo \App\Faculty::find($student["std_prg"]->subject_expert)->name;
                            }
                        }
                            ?></td>
                        <td class="text-dark" align="centre">
                            <?php
                            $c = 1;
                            foreach($student["faculty"] as $f){
                                echo $c.") ".\App\Faculty::find($f->faculty_id)->name."<br>";
                                $c++;
                            }
                            ?>
                        </td>
                        <td class="text-dark">

                            <button  <?php
                            if ($student["std_prg"]->committee_id != null) {
                                ?>
                                    hidden=""
                                    <?php
                                }
                                ?>
                                class="btn btn-primary font-13 final_committe" data-target="#committee_members" data-toggle="modal">Finalize Committee</button>
                                
                            <button  <?php
                            if ($student["std_prg"]->committee_id == null) {
                                ?>
                                    hidden=""
                                    <?php
                                }
                                ?> class="btn btn-primary font-13 edit_committee" data-target="#edit_committee_members" data-toggle="modal">Edit</button>
                                <button <?php
                            if ($student["std_prg"]->committee_id == null) {
                                ?>
                                    hidden=""
                                    <?php
                                }
                                ?> class="btn btn-primary font-13 view_committee" data-target="#view_committee_members" data-toggle="modal">View</button>
                        </td>
                        <td class="text-dark" style="text-align: center">
                            <button class="btn btn-pink font-13 edit_mentor">Edit</button>
                            <button hidden="" class="btn btn-primary font-13 save_mentor">Save</button>
                        </td>
                    </tr>
                    <?php
                    $c++;
                }
                ?>


            </tbody>



        </table>                
        <!--</div>-->



    </div>
    <div class="modal" id="committee_members" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-xs-12">
                        <p class="_textcentre" style="text-align: center">Select Committee Members</p>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/save_final_committee" method="POST" class="form-horizontal">
                        {{ csrf_field() }}
                        <input id="std_prg_id" hidden="" name="std_prg">
                        <div class="row m-t-10">
                            <div class="col-xs-5" >
                                <select name="from[]" id="multiselect1" class="form-control font-13" size="10" multiple="multiple">

                                </select>
                            </div>

                            <div class="col-xs-2 ">
                                <button type="button" id="multiselect1_rightAll" class="btn btn-block m-t-40"><i class="glyphicon glyphicon-forward"></i></button>
                                <button type="button" id="multiselect1_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
                                <button type="button" id="multiselect1_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
                                <button type="button" id="multiselect1_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
                            </div>

                            <div class="col-xs-5">
                                <select name="to[]" id="multiselect1_to" class="form-control font-13" size="10" multiple="multiple">

                                </select>
                            </div>
                        </div>
                        <div class="modal-footer col-md-12">
                            <button id="final_com" type="submit" class="font-13 center-block btn btn-primary">Final Committee</button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
     <div class="modal" id="view_committee_members" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-xs-12">
                        <p class="_textcentre" style="text-align: center">Committee Members</p>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <div class="row m-t-10 text-center" id="v_members">
                            <div class="form-group col-xs-12">
                                <p>Asim Benerjee</p>
                            </div>
                            <div class="form-group col-xs-12">
                                <p>Asim Benerjee</p>
                            </div>
                        </div>
                        <div class="modal-footer col-md-12">
                            <button type="button" class="font-13 center-block btn btn-primary" data-dismiss="modal">OK</button>
                        </div>
                </div>

            </div>

        </div>
    </div>
    <div class="modal" id="edit_committee_members" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-xs-12">
                        <p class="_textcentre" style="text-align: center">Edit Committee Members</p>
                    </div>
                    <button type="button" id="m2_close" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="/edit_final_committee" method="POST" class="form-horizontal">
                        {{ csrf_field() }}
                        <input id="std_prg_id2" hidden="" name="std_prg">

                        <div class="row m-t-10">
                            <div class="col-xs-5">
                                <select name="from[]" id="multiselect2" class="form-control font-13" size="10" multiple="multiple">

                                </select>
                            </div>

                            <div class="col-xs-2 ">
                                <button type="button" id="multiselect2_rightAll" class="btn btn-block m-t-40"><i class="glyphicon glyphicon-forward"></i></button>
                                <button type="button" id="multiselect2_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
                                <button type="button" id="multiselect2_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
                                <button type="button" id="multiselect2_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
                            </div>

                            <div class="col-xs-5">
                                <select name="to[]" id="multiselect2_to" class="form-control font-13" size="10" multiple="multiple">

                                </select>
                            </div>
                        </div>
                        <div class="modal-footer col-md-12">
                            <button type="submit" class="font-13 center-block btn btn-primary">Save Changes</button>
                        </div>
                    </form>

                </div>


            </div>

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
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.js"></script>

<script type="text/javascript" src="{{ asset('js/datatable.min.js') }}"></script>

<script src="{{ asset('js/plugins/datatables.init.js') }}" type="text/javascript"></script>

<script src="{{ asset('js/plugins/select2.min.js') }}" type="text/javascript"></script>

<script type="text/javascript">
jQuery(document).ready(function ($) {

});
</script>
<script src="{{ asset('js/committee.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/multiselect.js') }}" type="text/javascript"></script>


@endsection


