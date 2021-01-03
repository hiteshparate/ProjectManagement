@extends('layouts.coordinator')
@section('title')
 Add Students to Subphase
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css"/>


@endsection

@section('content')
<div class="container">
    <ul  class="nav nav-pills  justify-content-center font-13 text-primary">
        <li class="active">
            <a  href="#using_dropdown" data-toggle="tab">From List</a>
        </li>
        <li>
            <a href="#using_csv" data-toggle="tab">From CSV</a>
        </li>
        <li>
            <a href="#view_student" data-toggle="tab">View Students in Subphase</a>
        </li>


    </ul>

    <div class="tab-content ">
        <div class="tab-pane  m-t-10 active" id="using_dropdown">
            <div class="row" style="font-size: 12px">
                <div id="m_dt" class="col-lg-12" style="align-content: center">
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="/add_std_to_subphase">
                            {{ csrf_field() }}
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
                            <div class="form-group text-center" >
                                <button type="button" class="btn btn-primary phase_sub font-13">show students</button>
                            </div>

                            <div class="row">
                                <div class="col-xs-5">
                                    <select name="from[]" id="multiselect1" class="form-control from_select font-13" size="8" multiple="multiple" style="font-size: 13px">
                                        

                                    </select>
                                </div>

                                <div class="col-xs-2">
                                    <button type="button" id="multiselect1_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
                                    <button type="button" id="multiselect1_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
                                    <button type="button" id="multiselect1_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
                                    <button type="button" id="multiselect1_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
                                </div>

                                <div class="col-xs-5">
                                    <select name="to[]" id="multiselect1_to" class="form-control to_select font-13" size="8" multiple="multiple" style="font-size: 13px"></select>
                                </div>
                            </div>
                            <div class="form-group" style="
                                 width: auto;
                                 height:  fit-content;
                                 text-align:  center;
                                 align-content:  center;
                                 align-self:  center;
                                 ">
                                <button class="btn btn-primary add_students m-t-10" style="font-size: 15px" type="submit">Add Students</button>
                            </div>




                        </form>
                    </div>




                </div>
            </div>
        </div>
        <div class="tab-pane  m-t-10" id="using_csv">
            <div class="row" style="font-size: 12px">
                <div id="z_dt" class="col-lg-12" style="align-content: center">
                    <div class="panel-body">

                        <div class="panel-heading font-13" style="text-align: center;color: black"><h2>Add students to Subphase </h2></div>
                        <form class="form-horizontal" method="post" action="/add_std_to_subphase_csv" file="true" enctype='multipart/form-data'>
                            {{ csrf_field() }}
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
                                    <option value="n">Select Subphase</option>

                                </select>
                            </div>

                            <div class="panel-body" style="font-size: 15px">
                                <ul>
                                    <li>Please add CSV file containing only StudentID in the following formet.</li>
                                    <li>Student ID</li>
                                    <li>First line of this file should be exactly same as shown in following example </li>
                                    <li><b>Example</b></li>
                                    <div style="font-size: 15px">
                                        student_id <br>
                                        201401001<br>
                                        201401002<br>
                                        201401003<br>
                                        201401004
                                    </div>

                                </ul> <br>
                                <br>
                            </div>
                            <div class="form-group">
                                <div class="row col-xs-12">
                                    <div class="col-xs-6">
                                        <input style="font-size: 15px" type="file" name="add_subphase_csv" class="form-control">
                                    </div>
                                    <div class="col-xs-6">
                                        <button type="submit" class="btn btn-facebook btn-rounded  font-13 fa-10x m-t-5" >Add Students</button>
                                    </div>
                                </div>



                            </div>                           




                        </form>


                    </div>

                </div>
            </div>

        </div>
        <div class="tab-pane  m-t-10" id="view_student">
            <div class="row" style="font-size: 12px">
                <div id="p_dt" class="col-lg-12" style="align-content: center">
                    <div class="form-group">
                        <select id = "phase" name="phase_choose" required class="phase_opt form-control select2" style="font-size: 13px; height: 38px">
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
                        <select id = "subphase" name="subphase_choose" class="sub_opt form-control select2" required style="font-size: 13px; height: 38px">
                            <option value="n">Select Subphase</option>
                        </select>
                    </div>
                    <div class="form-group text-center" >
                        <button type="button" class="btn btn-primary font-13 see_std">Show students</button>
                    </div>
                    <div id="std_details">
                        
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
<script src="{{ asset('js/coordinator_add_std_to_subphase.js') }}" type="text/javascript"></script>

<script type="text/javascript">
jQuery(document).ready(function ($) {
    $('#multiselect1').multiselect();
});
</script>
<script src="{{ asset('js/multiselect.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('js/datatable.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.flash.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.print.min.js" type="text/javascript"></script>

@endsection