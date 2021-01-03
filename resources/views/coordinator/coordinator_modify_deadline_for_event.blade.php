@extends('layouts.coordinator')
@section('title')
Edit Deadline for Event
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>

@endsection

@section('content')
<div class="container">
    <div class="row" style="font-size: 12px">
        <div id="dt" class="col-lg-12 form-horizontal" style="align-content: center">
            <form class="form-horizontal" action="/modify_student_deadline" method="POST">
                {{ csrf_field() }}
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
                <div class="hide_show" hidden="">
                    <div class="row form-group">
                        <div class="col-xs-3 text-center">
                            <p class="font-13 font-bold m-t-5">Event Start Date</p>
                        </div>
                        <div class="col-xs-3 text-center">
                            <input type="date" class="form-control font-13 " name = "start_date">

                        </div>
                        <div class="col-xs-3 text-center">
                            <p class="font-13 font-bold m-t-5">Event End Date</p>
                        </div>
                        <div class="col-xs-3 text-center">
                            <input type="date" class="form-control font-13 " name = "end_date">

                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-xs-5">
                            <select name="from[]" style="font-size: 13px" id="multiselect1" class="form-control" size="10" multiple="multiple">

                            </select>
                        </div>

                        <div class="col-xs-2 ">
                            <button type="button" id="multiselect1_rightAll" class="btn btn-block m-t-40"><i class="glyphicon glyphicon-forward"></i></button>
                            <button type="button" id="multiselect1_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
                            <button type="button" id="multiselect1_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
                            <button type="button" id="multiselect1_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
                        </div>

                        <div class="col-xs-5">
                            <select name="to[]" style="font-size: 13px" id="multiselect1_to" class="form-control" size="10" multiple="multiple"></select>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-primary font-13 font-bold " type="submit">Modify deadline for these students</button>
                    </div>




                </div>

            </form>


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
<script src="{{ asset('js/coordinator_modify_deadline.js') }}" type="text/javascript"></script>
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
