@extends('layouts.faculty')
@section('title')
Grading
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>

@endsection
@section('content')

<div  class="container">	
    <ul  class="nav nav-pills  justify-content-center font-13 text-primary">
        <li class="active">
            <a  href="#mentor" data-toggle="tab">As a mentor</a>
        </li>
        <li>
            <a href="#committee" data-toggle="tab">As a committee Member</a>
        </li>
    </ul>

    <div class="tab-content ">
        <div class="tab-pane active m-t-10" id="mentor">
            <div class="row" style="font-size: 12px">
                <div id="m_dt" class="col-lg-12" style="align-content: center">

                    <table id="mentor_datatable" class="display table table-striped table-bordered dataTable" >
                        <thead>
                            <tr>
                                <th class="_textcentre" >Sr.No</th>
                                <th class="_textcentre">Student ID</th>
                                <th class="_textcentre">Phase</th>
                                <th class="_textcentre">Subphase</th>
                                <th class="_textcentre">Reports</th>
                                <th class="_textcentre">Grade</th>
                                <th class="_textcentre">Comments</th>
                            </tr>
                        </thead>

                        <tbody style="font-size: 14px;align-content: center">
                            <?php
                            $c = 1;
                            foreach ($subphase as $subp) {
                                $std = null;
                                ?>
                                <tr>
                                    <td id="<?php echo $subp["sub"]->id ?>" class="text-dark s_id" align="center"><?php echo $c ?></td>
                                    <td id="<?php echo $subp["sub"]->student_id ?>" class="text-dark id" align="center"><?php echo \App\Student::find($subp["sub"]->student_id)->username ?></td>
                                    <td class="text-dark" align="center"><?php echo $subp["sub"]->subphase->phase->phase_name ?></td>
                                    <td class="text-dark" align="center"><?php echo $subp["sub"]->subphase->name ?></td>
                                    <td class="text-dark" align="center">
                                        <a href="/mentor_view_report/<?php echo $subp["sub"]->id ?>" target="_blank" class="btn btn-primary view_reports font-13">
                                            View Reports
                                        </a>
                                    </td>
                                    <td class="text-dark" align="center">
                                        <?php
                                        $std = App\student_mentor_grade::where(["subphase_std_id" => $subp["sub"]->id, "mentor_id" => $mentor])->first();
                                        if ($std != null && $std->isFinal == 1) {
                                            echo $std->grade;
                                        } else {
                                            ?>
                                            <select class="grading">
                                                <?php
                                                if ($std != null && $std->grade != null) {
                                                    ?>
                                                    <option value="<?php echo $std->grade ?>"><?php echo $std->grade ?></option>
                                                    <?php
                                                } else {
                                                    ?>
                                                    <option value="select">Select Grade</option>
                                                    <?php
                                                }
                                                foreach ($subp["grade"] as $g) {
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
                                    <td class="text-dark" align="center" style="width:250px">
                                        <?php
                                        if ($std == null || $std->comment == null) {
                                            ?>
                                            <textarea hidden class="m_c row font-13" placeholder="enter comments here" ></textarea>
                                            <button class="g_comment btn btn-primary" data-target="#mentor_comments" data-toggle="modal">Comment</button>
                                            <button hidden="" class="btn btn-primary save_comment">Save</button>
                                            <?php
                                        } else {
                                            echo $std->comment;
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
            </div>
        </div>
        <div class="tab-pane m-t-10" id="committee">
            <div class="row" style="font-size: 12px">
                <div id="c_dt" class="col-lg-12" style="align-content: center">

                    <table id="committee_datatable" class="display table table-striped table-bordered" >
                        <thead>
                            <tr>
                                <th class="_textcentre" >Sr.No</th>
                                <th class="_textcentre">Student ID</th>
                                <th class="_textcentre">Phase</th>
                                <th class="_textcentre">Subphase</th>
                                <th class="_textcentre">Report</th>
                                <th class="_textcentre">Grade</th>
                                <th class="_textcentre">Comments</th>

                            </tr>
                        </thead>

                        <tbody style="font-size: 15px;align-content: center">
                            <?php
                            $c = 1;
                            foreach ($committee_std as $cs) {
                                $std_com_id = App\student_program::where(["student_id" => $cs["sub"]->student_id,"program_id" => $program_id])->first()->committee_id;
                                $committee_faculty_id = \App\committee_faculty::where(['committee_id'=>$std_com_id.'faculty_id'])->first();
                                ?>
                            <tr>
                            <td id="<?php echo $cs["sub"]->id ?>" class="text-dark s_id" align="center"><?php echo $c ?></td>
                            <td id="<?php echo $committee_faculty_id->id ?>" class="text-dark com_id" align="center"><?php echo \App\Student::find($cs["sub"]->student_id)->username ?></td>
                            <td class="text-dark" align="center"><?php echo $cs["sub"]->subphase->phase->phase_name ?></td>
                            <td class="text-dark" align="center"><?php echo $cs["sub"]->subphase->name ?></td>
                            <td class="text-dark" align="center">
                                <a href="/mentor_view_report/<?php echo $cs["sub"]->id ?>" target="_blank" class="btn btn-primary view_reports font-13">
                                    View Reports
                                </a>
                            </td>

                            <td class="text-dark" align="center">
                                <?php
                                
                                $std = App\student_committee_grade::where(["subphase_std_id" => $cs["sub"]->id, "committee_faculty_id" => $committee_faculty_id->id])->first();
                                if ($std != null && $std->isFinal == 1) {
                                    echo $std->grade;
                                } else {
                                    ?>
                                    <select class="grading">
                                        <?php
                                        if ($std != null && $std->grade != null) {
                                            ?>
                                            <option value="<?php echo $std->grade ?>"><?php echo $std->grade ?></option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value="select">Select Grade</option>
                                            <?php
                                        }
                                        foreach ($cs["grade"] as $g) {
                                            ?>
                                            <option value="<?php echo $g->type ?>"><?php echo $g->type ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select><br>
                                    <button class="com_save m-t-5 btn btn-primary font-13">Save</button>
                                    <button class="com_submit m-t-5 btn btn-dribbble font-13">Submit</button>
                                    <?php
                                }
                                ?>
                            </td>
                            <td class="text-dark" align="center" style="width:250px">
                                <?php
                                if ($std == null || $std->comment == null) {
                                    ?>
                                    <textarea hidden class="m_c row font-13" placeholder="enter comments here" ></textarea>
                                    <button class="com_comment btn btn-primary font-13" data-target="#mentor_comments" data-toggle="modal">Comment</button>
                                    <button hidden="" class="btn btn-primary com_save_comment">Save</button>
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



                    </table>                

                </div>
            </div>

        </div>
    </div>
</div>

@endsection
@section('js')
<script src="{{ asset('js/faculty_grading.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.js"></script>
@endsection
