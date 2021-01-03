@extends('layouts.coordinator')
@section('title')
Manage Subphase
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>
@endsection

@section('content')
<div class="container">
    <ul  class="nav nav-pills  justify-content-center font-13 text-primary">
        <li class="active">
            <a  href="#create" data-toggle="tab">Create Subphase</a>
        </li>
        <li>
            <a href="#view" data-toggle="tab">View Subphase</a>
        <li>
            <a href="#edit" data-toggle="tab">Edit Subphase</a>
        </li>

    </ul>

    <div class="tab-content ">
        <div class="tab-pane  m-t-10 active" id="create">
            <div class="row" style="font-size: 12px">
                <div id="m_dt" class="col-lg-12" style="align-content: center">
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="/add_subphase">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <select name="phase_select" class="form-control select2" required style="font-size: 13px; height: 38px">
                                        <option value="n">Select Phase</option>
                                        <?php
                                        foreach ($phases as $phase) {
                                            ?>
                                            <option value="<?php echo $phase->id ?>"><?php echo $phase->phase_name ?></option>
                                            <?php
                                        }
                                    ?>


                                </select>
                            </div>
                            <div class="subphase_detail">
                                <div class="form-group">
                                    <input name="subphase_name" type="text" class="form-control font-13" required="true" placeholder="Enter Sub Phase Name">
                                </div>
                                <div class="form-group">
                                    <input name="subphase_code" type="text" class="form-control font-13" required="true" placeholder="Enter Sub Phase Code">
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-4 m-t-5">
                                        <span class="font-bold" style="font-size: 14px" type="text">Grading Scheme</span>
                                    </div>
                                    <div class="col-xs-8">
                                        <select name="grading_type" class="form-control select2" style="font-size: 13px; height: 38px">
                                            <option value="n">Select Grading System</option>
                                            <?php
                                            foreach ($grade as $g) {
                                                ?>
                                                <option value="<?php echo $g->id ?>"><?php echo $g->name ?></option>
                                                <?php
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-4 ">
                                        <span class="font-bold" style="font-size: 14px" type="text">Evaluation Committee Needed?</span>
                                    </div>
                                    <div class="col-xs-4">
                                        <input class="yes" type="radio" id="committee_yes" value="1" name="committee">
                                        <label  for="committee_yes"> Yes </label>

                                    </div>
                                    <div class="col-xs-4 ">
                                        <input class="no" type="radio" id="committee_no" value="0" name="committee">
                                        <label  for="committee_no"> No </label>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-4 ">
                                        <span class="font-bold" style="font-size: 14px" type="text">Submission Needed?</span>
                                    </div>
                                    <div class="col-xs-4">
                                        <input class="submission_yes" type="radio" id="sumission_yes" value="1" name="submission">
                                        <label  for="sumission_yes"> Yes </label>

                                    </div>
                                    <div class="col-xs-4 ">
                                        <input class="submission_no" type="radio" id="sumission_no" value="0" name="submission">
                                        <label  for="sumission_no"> No </label>

                                    </div>
                                </div>
                                <div  hidden class="sub_cons">
                                    <div class="form-group">
                                        <div class="col-xs-4">
                                            <span class="font-bold" style="font-size: 14px" type="text">File Name constraint</span>
                                        </div>
                                        <div class="col-xs-8">
                                            <input name="file_name" type="text" class="form-control font-13" placeholder="File Name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-4">
                                            <span class="font-bold" style="font-size: 14px" type="text">File Size constraint(MB)</span>
                                        </div>
                                        <div class="col-xs-8">
                                            <input name="file_size" type="text" class="form-control font-13" placeholder="File Size">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-4">
                                            <span class="font-bold" style="font-size: 14px" type="text">File extension constraint</span>
                                        </div>
                                        <div class="col-xs-8">
                                            <input name="file_ext" type="text" class="form-control font-13"  placeholder="pdf , jpeg , doc , docx etc">
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary font-13 pull-right">Create Subphase</button>
                                </div>

                            </div>



                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane  m-t-10" id="view">
            <div class="row" style="font-size: 12px">
                <div id="z_dt" class="col-lg-12" style="align-content: center">

                    <table id="subphase_datatable" class="display table table-striped table-bordered" >
                        <thead>
                            <tr>
                                <th class="_textcentre" >Sr.No</th>
                                <th class="_textcentre">Programme</th>
                                <th class="_textcentre">Phase</th>
                                <th class="_textcentre">Subphase</th>
                                <th class="_textcentre">Code</th>
                                <th class="_textcentre">Grading System</th>
                                <th class="_textcentre">Committee needed for evaluation?</th>
                                <th class="_textcentre">Submission needed?</th>
                                <th class="_textcentre">File Size(MB)</th>
                                <th class="_textcentre">File extension</th>
                                </tr>
                        </thead>

                        <tbody style="font-size: 15px;align-content: center">
                            <?php
                            $c = 1;
                            foreach($subphase as $sub){
//                                $p_name = $sub[0]->phase()->phase_name;
                                foreach($sub as $s){
                                    ?>
                            <tr>
                                <td style="color: black;text-align: center"><?php echo $c ?></td>
                            <td style="color: black;text-align: center"><?php echo $prg_name ?></td>
                            <td style="color: black;text-align: center"><?php echo $s->phase->phase_name ?></td>
                            <td style="color: black;text-align: center"><?php echo $s->name ?></td>
                            <td style="color: black;text-align: center"><?php echo $s->code ?></td>
                            <td style="color: black;text-align: center"><?php echo App\grading_system::find($s->g_id)->name ?></td>
                            <td style="color: black;text-align: center"><?php if($s->evaluation_committee == 1){
                                echo "Yes";
                            }else{
                                echo "No";
                            } ?></td>
                            <td style="color: black;text-align: center"><?php if($s->submission == 1){
                                echo "Yes";
                            }else{
                                echo "No";
                            } ?></td>
                            <td style="color: black;text-align: center"><?php if($s->submission == 1){
                                echo $s->file_size;
                            }else{
                                echo "";
                            } ?></td>
                            <td style="color: black;text-align: center"><?php if($s->submission == 1){
                                echo $s->file_extension;
                            }else{
                                echo "";
                            } ?></td>
                            </tr>
                            <?php
                            $c++;
                                }
                            }
                            ?>

                        </tbody>



                    </table>                

                </div>
            </div>

        </div>
        <div class="tab-pane  m-t-10" id="edit">
            <div class="row" style="font-size: 12px">
                <div id="c_dt" class="col-lg-12" style="align-content: center">
                    <div class="panel-body">
                        <div class="form-group">
                            <select name="phase_choose" required class="phase_option form-control select2" style="font-size: 13px; height: 38px">
                                <option value="n">Select Phase</option>
                                <?php
                                foreach ($phases as $p) {
                                    ?>
                                    <option value="<?php echo $p->id ?>"><?php echo $p->phase_name ?> </option>
                                <?php }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <select name="subphase_choose" class="subphase_option form-control select2" required style="font-size: 13px; height: 38px">
                                <option value="n">Select SubPhase</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary font-13 pull-right edit_subphase m-b-10">Edit Subphase</button>
                        </div>

                        <div  hidden class="sub_detail">
                            <form class="form-horizontal" method="POST" action="/edit_save_subphase">
                                {{ csrf_field() }}
                                <input hidden class="hid_sub_id" name="sub_id">
                                <div class="form-group" >
                                    <input name="subphase_name" type="text" required="" class="edit_sub form-control font-13" >
                                </div>
                                <div class="form-group">
                                    <input name="subphase_code" required="" id="subphase_code" type="text" class="form-control font-13" >
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-4 m-t-5">
                                        <span class="font-bold" style="font-size: 14px" type="text">Grading Scheme</span>
                                    </div>
                                    <div class="col-xs-8">
                                        <select required="" id="grading_type" name="grading_type" class="form-control select2" style="font-size: 13px; height: 38px">
                                            <option value="n">Select Grading System</option>
                                            <?php
                                            foreach ($grade as $g) {
                                                ?>
                                                <option value="<?php echo $g->id ?>"><?php echo $g->name ?></option>
                                                <?php
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-4 ">
                                        <span class="font-bold" style="font-size: 14px" type="text">Evaluation Committee Needed?</span>
                                    </div>

                                    <div class="col-xs-4">
                                        <input class="yes"  type="radio" id="e_committee_yes" value="1" name="committee">
                                        <label  for="e_committee_yes"> Yes </label>

                                    </div>
                                    <div class="col-xs-4 ">
                                        <input class="no" type="radio" id="e_committee_no" value="0" name="committee">
                                        <label  for="e_committee_no"> No </label>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-4 ">
                                        <span class="font-bold" style="font-size: 14px" type="text">Submission Needed?</span>
                                    </div>
                                    <div class="col-xs-4">
                                        <input class="submission_yes" type="radio" id="e_submission_yes" value="1" name="submission">
                                        <label  for="e_submission_yes"> Yes </label>

                                    </div>
                                    <div class="col-xs-4 ">
                                        <input class="submission_no" type="radio" id="e_submission_no" value="0" name="submission">
                                        <label  for="e_submission_no"> No </label>

                                    </div>
                                </div>
                                <div  hidden class="sub_cons">
                                    <div class="form-group">
                                        <div class="col-xs-4">
                                            <span class="font-bold" style="font-size: 14px" type="text">File Name constraint</span>
                                        </div>
                                        <div class="col-xs-8">
                                            <input id="file_name" name="file_name" type="text" class="form-control font-13" placeholder="File Name">
                                        </div>
                                    </div>
                                    <div class="form-group m-t-5">
                                        <div class="col-xs-4">
                                            <span class="font-bold" style="font-size: 14px" type="text">File Size constraint(MB)</span>
                                        </div>
                                        <div class="col-xs-8">
                                            <input id="file_size" name="file_size" type="text" class="form-control font-13 m-t-5" placeholder="File Size">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-xs-4">
                                            <span class="font-bold" style="font-size: 14px" type="text">File extension constraint</span>
                                        </div>
                                        <div class="col-xs-8">
                                            <input id="file_ext" name="file_ext" type="text" class="form-control font-13 m-t-5"  placeholder="pdf , jpeg , doc , docx etc">
                                        </div>
                                    </div>

                                </div>
                                <div class="form-group" style="
                                     width: auto;
                                     height:  fit-content;
                                     text-align:  center;
                                     align-content:  center;
                                     align-self:  center;
                                     ">
                                    <input type="submit" class="wrapper btn btn-pink font-13 m-t-10" style="text-align: center;
                                           align-content: center;" value="Save Details">
                                </div>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection

@section('js')
<script src="{{ asset('js/coordinator_phase_subphase.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('js/datatable.min.js') }}"></script>

@endsection