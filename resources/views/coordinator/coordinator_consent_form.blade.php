@extends('layouts.coordinator')
@section('title')
Consent Form Deadline
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>

@endsection
@section('content')

<div class="container">
    <ul  class="nav nav-pills  justify-content-center font-13 text-primary">
        <li class="active">
            <a  href="#set" data-toggle="tab">Set Deadline</a>
        </li>
        <li >
            <a href="#view" data-toggle="tab">View Deadline</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active m-t-10" id="set">

            <div class="row" style="font-size: 12px">
                <div id="dt" class="col-lg-12 form-horizontal" style="align-content: center">
                    <div class="card-box container" style="background-color: rgb(247,247,241)">
                        <form class="form-horizontal" method="POST" action="/student_consent_form_deadline">
                            {{ csrf_field() }}
                            <h2 class="text-center" >Set Consent form Deadline for students</h2>
                            <div class="form-group m-t-10">

                                <div class="col-xs-3">
                                    <p class="font-13 text-center font-bold m-t-10" > Start Date</p>
                                </div>
                                <div class="col-xs-3">
                                    <input type="date" class="form-control font-13 s_date font-bold" name="start_date">
                                </div>

                                <div class="col-xs-3">
                                    <p class="font-13 text-center font-bold m-t-10" for="end_date"> End Date</p>
                                </div>
                                <div class="col-xs-3">
                                    <input type="date" class="form-control font-13 e_date font-bold" name="end_date" >
                                </div>

                            </div>
                            <div class="row m-t-10 multiselect_deadline" >
                                <div class="col-xs-5">
                                    <select name="from[]"  id="multiselect1" class="form-control font-13 from_deadline_multi" size="10" multiple="multiple">

                                        <?php
                                        foreach ($stud_prog as $s_p) {
                                            ?>
                                            <option value="<?php echo $s_p->id ?>"><?php echo \App\Student::find($s_p->student_id)->username ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="col-xs-2 ">
                                    <button type="button" id="multiselect1_rightAll" class="btn btn-block m-t-40"><i class="glyphicon glyphicon-forward"></i></button>
                                    <button type="button" id="multiselect1_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
                                    <button type="button" id="multiselect1_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
                                    <button type="button" id="multiselect1_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
                                </div>

                                <div class="col-xs-5">
                                    <select name="to[]"  id="multiselect1_to" class="form-control font-13 to_deadline_multi" size="10" multiple="multiple"></select>
                                </div>
                            </div>
                            <div class="form-group text-center ">
                                <button  type="submit" class="btn btn-primary save_deadline" style="font-size: 15px">Save Deadline</button>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane m-t-10" id="view">
            <div class="row" style="font-size: 12px">
                <div id="p_dt" class="col-lg-12" style="align-content: center">
                    <table id="deadline_datatable" class="display table table-striped table-bordered" >
                        <thead>
                            <tr>
                                <th class="_textcentre" >Sr.No</th>
                                <th class="_textcentre">Student ID</th>
                                <th class="_textcentre">Consent Form Start Date</th>
                                <th class="_textcentre">Consent Form End Date</th>
                            </tr>
                        </thead>

                        <tbody style="font-size: 15px;align-content: center">
                            <?php
                            $c = 1;
                            foreach ($filled_form_std as $s_p) {
                                ?>
                                <tr>
                                    <td class="text-center" style="color: black;font-size: 13px"><?php echo $c ?></td>
                                    <td class="text-center" style="color: black;font-size: 13px"><?php echo \App\Student::find($s_p->student_id)->username ?></td>
                                    <td class="text-center " style="color: black;font-size: 13px"><?php echo $s_p->cf_start_date ?></td>
                                    <td class="text-center" style="color: black;font-size: 13px"><?php echo $s_p->cf_end_date ?></td>
                                </tr>
                                <?php
                                $c ++;
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
<script src="{{ asset('js/coordinator_consent_form_deadline.js') }}" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function ($) {
    $('#multiselect1').multiselect({
        search: {
            left: '<input type="text" name="q" class="form-control font-13" placeholder="Search..." />',
            right: '<input type="text" name="q" class="form-control font-13" placeholder="Search..." />',
        },
        fireSearch: function (value) {
            return value.length > 0;
        }
    });
});
</script>
<script src="{{ asset('js/multiselect.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('js/datatable.min.js') }}"></script>

@endsection