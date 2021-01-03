@extends('layouts.coordinator')

@section('title')
Grading Students
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable.min.css') }}"/>
<!--<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>-->
<link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert.css') }}"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css"/>

@endsection

@section('content')
<div class="content">
    <div class="row">
        <div id="dt" class="col-lg-12 form-horizontal" style="align-content: center">

            <div class="form-group">
                <div class="col-xs-3">
                    <p class=" text-center font-13 font-bold m-t-5">Phase Name</p>  
                </div>
                <div class="col-xs-3">
                    <input id="<?php echo $current_phase->id ?>" readonly="" type="text" value="<?php echo $current_phase->phase_name ?>" class="p_name form-control font-13">
                </div>

                <div class="col-xs-3">
                    <p class=" text-center font-13 font-bold m-t-5">Subphase Name</p>  
                </div>
                <div class="col-xs-3">
                    <input id="<?php echo $current_subphase->id ?>" readonly="" type="text" value="<?php echo $current_subphase->name ?>" class="subp_name form-control font-13">
                </div>
            </div>
            <div class="form-group text-center ">
                <a href="/grading" class="btn btn-primary font-13">Change Phase and Subphase</a>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-pink pull-right m-b-10 final_grade_btn" style="font-size: 17px">Done Grading</button>                    
                </div>                
            </div>

            <table id="grading_table"  class="display table-responsive table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th class="_textcentre" >Sr.No</th>
                        <th class="_textcentre">Student ID</th>
                        <th class="_textcentre">Reports</th>
                        <th class="_textcentre">Mentor</th>
                        <th class="_textcentre">Mentor's Grades</th>
                        <th class="_textcentre">Committee's Grades</th>
                        <th class="_textcentre">Mentor's Comments</th>
                        <th class="_textcentre">Committee's Comments</th>
                        <th class="_textcentre">Grade</th>
                        <th class="_textcentre">Action</th>
                        <th class="_textcentre">Comment</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px;align-content: center">
                    <?php
                    $c = 1;
                    foreach ($students as $std) {
                        $s = \App\student_program::where(['student_id' => $std->student_id, 'program_id' => $prg_id])
                                ->where('status', '!=', 'completed')
                                ->first();
                        ?>
                        <tr>
                            <td id="<?php echo $std->id ?>" class="text-dark s_id" align="center"><?php echo $c ?></td>
                            <td id="<?php echo $std->student_id ?>" class="text-dark id" align="center"><?php echo App\Student::find($std->student_id)->username ?></td>
                            <td class="text-dark" align="center">
                                <a target="_black" href="/coordinator_view_report/<?php echo $std->id ?>" class="btn btn-primary view_reports font-13">
                                    View Reports
                                </a>
                            </td>
                            <td class="text-dark" align="center"><?php
                                if ($s->mentor_2 != null && $s->mentor_1 != null) {
                                    echo ("1) " . App\Faculty::find($s->mentor_1)->name . "<br>" . "2) " . App\Faculty::find($s->mentor_2)->name);
                                } else {
                                    if ($s->mentor_1 != null) {
                                        echo "1) " . App\Faculty::find($s->mentor_1)->name;
                                    }
                                }
                                ?></td>
                            <td class="text-dark" align="center">
                                <?php
                                $m1 = $std->mentor_grade()->where('mentor_id', $s->mentor_1)->first();
                                if ($s->mentor_2 != null) {
                                    $m2 = $std->mentor_grade()->where('mentor_id', $s->mentor_2)->first();
                                    if ($m1["isFinal"] == 1) {
                                        if ($m2["isFinal"] == 1) {
                                            echo ("1) " . $m1['grade'] . "<br>" . "2) " . $m2["grade"]);
                                        } else {
                                            echo ("1) " . $m1['grade'] . "<br>" . "2) ");
                                        }
                                    } else {
                                        if ($m2["isFinal"] == 1) {
                                            echo ("1) " . "<br>" . "2) " . $m2["grade"]);
                                        } else {
                                            echo ("1) " . "<br>" . "2) ");
                                        }
                                    }
                                } else {
                                    if($s->mentor_1 == null){
                                        echo "";
                                    }else{
                                        if($m1["isFinal"] == 1){
                                            echo "1) " . $m1["grade"];
                                        }else{
                                            echo "";
                                        }
                                        
                                    }
                                    
                                }
                                ?>
                            </td>
                            <td class="text-dark" align="center">
                                <?php
                                if ($s->committee_id == null) {
            
                                } else {
                                    if($com == 1){
                                        ?>
                                <button id="<?php echo $s->committee_id ?>" class="btn btn-primary committee_grade font-13 com_id" data-target="#committee_grade" data-toggle="modal" dstyle="font-size: 15px;align-content: center">View Grades</button>
                                <?php
                                    }else{
                                        
                                    }
                                }
                                ?>
                            </td>
                            <td class="text-dark" align="center">
                                <button class="btn btn-primary m_comment font-13" data-target="#mentor_comments" data-toggle="modal" dstyle="font-size: 15px;align-content: center">Comments</button>
                            </td>
                            <td class="text-dark" align="center">
                                <?php 
                                if($com == 1){
                                    ?>
                                <button class="btn btn-primary committee_comment font-13" data-target="#committee_comments" data-toggle="modal" dstyle="font-size: 15px;align-content: center">Comments</button>
                                <?php
                                }else{
                                    
                                }
                                ?>
                                
                            </td>
                            <td class="text-dark" align="center">
                                <?php
                                if ($std->isFinal == 1) {
                                    echo $std->final_grade;
                                } else {
                                    ?>
                                    <select class="grading">
                                        <?php
                                        if ($std->final_grade != null) {
                                            ?>
                                            <option value="<?php echo $std->final_grade ?>"><?php echo $std->final_grade ?></option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="select">Select Grade</option>
                                            <?php
                                        }
                                        foreach ($grades as $g) {
                                            ?>
                                            <option value="<?php echo $g->type ?>"><?php echo $g->type ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select><br>
                                    <button class="g_save m-t-5 btn btn-primary">Save</button>
                                    <button class="g_submit m-t-5 btn btn-dribbble">Submit</button>
                                    <?php
                                }
                                ?>

                            </td>
                            <td class="text-dark text-center" >
                                <?php
                                if ($std->is_fail == 1) {
                                    ?>
                                    <p class="font-13 font-bold"> failed</p>
                                    <?php
                                } else {
                                    ?>
                                    <button class="btn btn-danger fail_student" style="font-size: 13px">Fail</button>
                                    <?php
                                }
                                ?>

                            </td>
                            <td class="text-dark cm" align="center" style="width:250px">
                                <?php
                                if ($std == null || $std->comment == null) {
                                    ?>
                                    <textarea hidden class="m_c row font-13" placeholder="enter comments here" ></textarea>
                                    <button class="g_comment btn btn-primary">Comment</button>
                                    <button hidden="" class="btn btn-primary save_comment">Save</button>    
                                    <?php
                                } else {
                                    echo $std->comment;
                                }
                                ?>


                            </td>

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
                        <th class="_textcentre">Reports</th>
                        <th class="_textcentre">Mentor</th>
                        <th class="_textcentre">Mentor's Grades</th>
                        <th class="_textcentre">Committee's Grades</th>
                        <th class="_textcentre">Mentor's Comments</th>
                        <th class="_textcentre">Committee's Comments</th>
                        <th class="_textcentre">Grade</th>
                        <th class="_textcentre">Action</th>
                        <th class="_textcentre">Comment</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="modal" id="mentor_comments" role="dialog" style="">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="col-xs-12">
                            <p class="_textcentre" style="text-align: center">Mentor Comments</p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" >
                        <div class="form-horizontal" id="model_data">


                        </div>

                    </div>
                    <div class="modal-footer col-md-12">
                        <button type="button" class="font-13 center-block btn btn-primary" data-dismiss="modal">OK</button>
                    </div>

                </div>
            </div>

        </div>

        <div class="modal" id="committee_comments" role="dialog" style="">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="col-xs-12">
                            <p class="_textcentre" style="text-align: center">Committee Comments</p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" >
                        <div class="form-horizontal" id="committee_comment_data">


                        </div>

                    </div>
                    <div class="modal-footer col-md-12">
                        <button type="button" class="font-13 center-block btn btn-primary" data-dismiss="modal">OK</button>
                    </div>

                </div>
            </div>

        </div>

        <div class="modal" id="committee_grade" role="dialog" style="">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="col-xs-12">
                            <p class="_textcentre" style="text-align: center">Committee Grades</p>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" >
                        <div class="form-horizontal" id="committee_data">


                        </div>

                    </div>
                    <div class="modal-footer col-md-12">
                        <button type="button" class="font-13 center-block btn btn-primary" data-dismiss="modal">OK</button>
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
<script src="{{ asset('js/sweetalert.js') }}" type="text/javascript"></script>

<script src="{{ asset('js/coordinator_add_std_to_subphase.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/coordinator_grade_std.js') }}" type="text/javascript"></script>

<script type="text/javascript" src="{{ asset('js/datatable.min.js') }}"></script>
<!--<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.js" type="text/javascript"></script>-->

<!--<script src="{{ asset('js/plugins/datatables.init.js') }}" type="text/javascript"></script>-->
<script src="{{ asset('js/plugins/select2.min.js') }}" type="text/javascript"></script>

<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js" type="text/javascript"></script>


@endsection

