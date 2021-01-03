@extends('layouts.coordinator')
@section('title')
Manage Phases
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/stcombobox.css') }}"/>

@endsection
@section('content')
<div  class="container">	
    <ul  class="nav nav-pills  justify-content-center font-13 text-primary">
        <li class="active">
            <a  href="#create" data-toggle="tab">Create Phase</a>
        </li>
        <li >
            <a href="#view" data-toggle="tab">View Phase</a>
        </li>
        <li>
            <a href="#edit" data-toggle="tab">Edit Phase</a>
        </li>


    </ul>

    <div class="tab-content ">
        <div class="tab-pane active m-t-10" id="create">
            <div class="row" style="font-size: 12px">
                <div id="m_dt" class="col-lg-12" style="align-content: center">
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="/add_phase">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input id="phase" name="phase" type="text" class="form-control font-13" required="true" placeholder="phase name">
                            </div>

                            <div class="form-group ">
                                <button type="submit" class="phase btn btn-primary font-13 pull-right">Create Phase</button>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane m-t-10" id="view">
            <div class="row" style="font-size: 12px">
                <div id="p_dt" class="col-lg-12" style="align-content: center">

                    <table id="phase_datatable" class="display table table-striped table-bordered" >
                        <thead>
                            <tr>
                                <th class="_textcentre" >Sr.No</th>
                                <th class="_textcentre">Program</th>
                                <th class="_textcentre">Phase</th>
                            </tr>
                        </thead>

                        <tbody style="font-size: 15px;align-content: center">
                            <?php 
                            $c = 1;
                            foreach($phase as $p){
                                ?>
                            <tr>
                                <td class="_textcentre"><?php echo $c ?></td>
                                <td class="_textcentre"><?php echo $prg ?></td>
                                <td class="_textcentre"><?php echo $p->phase_name ?></td>
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
        <div class="tab-pane m-t-10" id="edit">
            <div class="row" style="font-size: 12px">
                <div id="e_dt" class="col-lg-12" style="align-content: center">
                    <div class="panel-body">
                            <div class="show_phase">
                                <div class="form-group">
                                    <select name="phase_select"  class="form-control select2 phase_op" required style="font-size: 13px; height: 38px">
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

                                <div class="form-group ">
                                    <button type="submit" class="edit_phase btn btn-primary font-13 pull-right">Edit Phase</button>
                                </div>
                            </div>
                     
                        <div hidden class="edit_p">
                            <form class="form-horizontal" method="POST" action="/edit_save_phase">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <input hidden name="phase_id" class="hid_phase_id" type="text">
                                </div>
                                <div  class="form-group">
                                    <input name="edit_phase_name" type="text" class="edit_in form-control font-13">
                                </div>
                                <div class="form-group" style="
                                     width: auto;
                                     height:  fit-content;
                                     text-align:  center;
                                     align-content:  center;
                                     align-self:  center;
                                     ">
                                    <input type="submit" class="wrapper btn btn-pink font-13 m-t-10" style="text-align: center;
                                           align-content: center;" value="Save Phase Details">
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