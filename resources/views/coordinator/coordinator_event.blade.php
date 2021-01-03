@extends('layouts.coordinator')

@section('title')
Events
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>
@endsection
@section('content')
<div class="container">
    <ul  class="nav nav-pills  justify-content-center font-13 text-primary">
        <li class="active">
            <a  href="#create" data-toggle="tab">Create Event</a>
        </li>
        <li>
            <a href="#view" data-toggle="tab">View Events</a>
        <li>
            <a href="#edit" data-toggle="tab">Edit Events</a>
        </li>

    </ul>

    <div class="tab-content ">
        <div class="tab-pane  m-t-10 active" id="create">
            <div class="row" style="font-size: 12px">
                <div id="m_dt" class="col-lg-12" style="align-content: center">
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="/add_event">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <select name="phase_select" class="phase_option form-control select2" required style="font-size: 13px; height: 38px">
                                    <option value="n">Select Phase</option>
                                    <?php
                                    foreach ($phase as $p) {
                                        ?>
                                        <option value="<?php echo $p->id ?>"><?php echo $p->phase_name ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <select name="subphase_select" class="subphase_option form-control select2" required style="font-size: 13px; height: 38px">
                                    <option value="n">Select subPhase</option>

                                </select>
                            </div>
                            <div class="event_detail">

                                <div class="form-group">
                                    <input name="event_name" type="text" class="form-control font-13" required="true" placeholder="Event Name">
                                </div>
                                <div class="form-group">
                                    <textarea name="event_des" type="text" class="form-control font-13" required="true" placeholder="Event Description"></textarea>
                                </div>

                                

                                <div class="form-group">
                                    <div class="col-xs-4 m-t-5">
                                        <span class="font-bold" style="font-size: 14px" type="text">Submission Needed?</span>
                                    </div>
                                    <div class="col-xs-4">
                                        <input class="submission_yes" type="radio" id="submission_yes" value="1" name="submission">
                                        <label for="submission_yes"> Yes </label>

                                    </div>
                                    <div class="col-xs-4 ">
                                        <input class="submission_no" type="radio" id="submission_no" value="0" name="submission">
                                        <label for="submission_no"> No </label>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-4 m-t-5">
                                        <span class="font-bold" style="font-size: 14px" type="text">Do you want to send mail?</span>
                                    </div>
                                    <div class="col-xs-4">
                                        <input class="send_mail" type="radio" id="mail_yes" value="1" name="send_mail">
                                        <label for="mail_yes"> Yes </label>

                                    </div>
                                    <div class="col-xs-4 ">
                                        <input class="send_mail" type="radio" id="mail_no" value="0" name="send_mail">
                                        <label for="mail_no"> No </label>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-4">
                                        <label class="font-bold m-t-5" style="font-size: 14px">Event Start Date</label>
                                    </div>
                                    <div class="col-xs-8">
                                        <input type="date" name="start_date" class="form-control font-13">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-4">
                                        <label class="font-bold m-t-5" style="font-size: 14px">Event End Date</label>
                                    </div>
                                    <div class="col-xs-8">
                                        <input type="date" name="end_date" class="form-control font-13">
                                    </div>
                                </div>



                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary font-13 pull-right">Create Event</button>
                                </div>

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane  m-t-10" id="view">
            <div class="row" style="font-size: 12px">
                <div id="c_dt" class="col-lg-12" style="align-content: center">

                    <table id="event_datatable" class="display table table-striped table-bordered" >
                        <thead>
                            <tr>
                                <th class="_textcentre" >Sr.No</th>
                                <th class="_textcentre">Programme</th>
                                <th class="_textcentre">Phase</th>
                                <th class="_textcentre">Subphase</th>
                                <th class="_textcentre">Event name</th>
                                <th class="_textcentre">Event Description</th>
                                <th class="_textcentre">Report Submission</th>
                                <th class="_textcentre">Send mail?</th>
                                <th class="_textcentre" style="width:100px">StartDate</th>
                                <th class="_textcentre" style="width:100px">End_Date</th>
                            </tr>
                        </thead>

                        <tbody style="font-size: 13px;align-content: center">
                            <?php
                            $c = 1;
                            foreach ($phase as $p) {
                                $subphases = $p->subphases;
                                foreach ($subphases as $s) {
                                    $events = $s->events;
                                    foreach ($events as $e) {
                                        ?>
                                        <tr>
                                            <td style="color: black;text-align: center"><?php echo $c ?></td>
                                            <td style="color: black;text-align: center"><?php echo $prg_name ?></td>
                                            <td style="color: black;text-align: center"><?php echo $p->phase_name ?></td>
                                            <td style="color: black;text-align: center"><?php echo $s->code ?></td>
                                            <td style="color: black;text-align: center"><?php echo $e->name ?></td>
                                            <td style="color: black;text-align: center"><?php echo $e->description ?></td>
                                            <td style="color: black;text-align: center"><?php echo $e->submission ?></td>
                                            <td style="color: black;text-align: center"><?php echo $e->mail ?></td>
                                            <td style="color: black;text-align: center"><?php echo date("d-m-Y",strtotime($e->start_date)) ?></td>
                                            <td style="color: black;text-align: center"><?php echo date("d-m-Y",strtotime($e->end_date)) ?></td>
                                        </tr>
                                        <?php
                                        $c++;
                                    }
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
                            <select name="phase_choose" required class="edit_phase_option form-control select2" style="font-size: 13px; height: 38px">
                                <option value="n">Select Phase</option>
                                <?php
                                foreach ($phase as $p) {
                                    ?>
                                    <option value="<?php echo $p->id ?>"><?php echo $p->phase_name ?> </option>
                                <?php }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <select name="subphase_select" class="edit_subphase_option form-control select2" required style="font-size: 13px; height: 38px">
                                <option value="n">Select Subphase</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <select name="event_choose" class="edit_event_option form-control select2" required style="font-size: 13px; height: 38px">
                                <option value="n">Select Event</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary font-13 pull-right edit_event m-b-10">Edit Event</button>
                        </div>

                        <div hidden class="edit_event_detail">
                            <form class="form-horizontal" method="POST" action="/edit_save_event">
                                {{ csrf_field() }}
                                
                                <input hidden name="e_id" id="evnt_id">

                                <div class="form-group">
                                    <input name="event_name" type="text" class="edit_evnt form-control font-13" required="true" placeholder="Event Name">
                                </div>
                                <div class="form-group">
                                    <textarea id="e_dis" name="event_des" type="text" class="form-control font-13" required="true" placeholder="Event Description"></textarea>
                                </div>

                               

                                <div class="form-group">
                                    <div class="col-xs-4 m-t-5">
                                        <span class="font-bold" style="font-size: 14px" type="text">Submission Needed?</span>
                                    </div>
                                    <div class="col-xs-4">
                                        <input class="submission_yes" type="radio" id="e_submission_yes" value="1" name="submission">
                                        <label for="e_submission_yes"> Yes </label>

                                    </div>
                                    <div class="col-xs-4 ">
                                        <input class="submission_no" type="radio" id="e_submission_no" value="0" name="submission">
                                        <label for="e_submission_no"> No </label>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-4 m-t-5">
                                        <span class="font-bold" style="font-size: 14px" type="text">Do you want to send mail?</span>
                                    </div>
                                    <div class="col-xs-4">
                                        <input class="send_mail m-t-5" type="radio" id="e_mail_yes" value="1" name="send_mail">
                                        <label for="e_mail_yes"> Yes </label>

                                    </div>
                                    <div class="col-xs-4 ">
                                        <input class="send_mail m-t-5" type="radio" id="e_mail_no" value="0" name="send_mail">
                                        <label for="e_mail_no"> No </label>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-xs-4">
                                        <label class="font-bold m-t-5" style="font-size: 14px">Event Start Date</label>
                                    </div>
                                    <div class="col-xs-8">
                                        <input id="s_date" type="date" name="start_date" class="form-control font-13 m-t-5">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-4">
                                        <label class="font-bold m-t-5" style="font-size: 14px">Event End Date</label>
                                    </div>
                                    <div class="col-xs-8">
                                        <input id="e_date" type="date" name="end_date" class="form-control font-13 m-t-5">
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
                                           align-content: center;" value="Save Event Details">
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
<script src="{{ asset('js/coordinator_event.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('js/datatable.min.js') }}"></script>

@endsection