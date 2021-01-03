@extends('layouts.admin')
@section('title')
Manage Area Of Interest
@endsection

@section('css')

@endsection

@section('content')
<div class="row" style="font-size: 12px">
    <div id="admin_view__dt" class="col-lg-12" style="align-content: center">
        <ul  class="nav nav-pills  justify-content-center font-13 text-primary">
            <li class="active">
                <a  href="#view_aoi" data-toggle="tab">View Area of Interest</a>
            </li>
            <li>
                <a href="#add_aoi" data-toggle="tab">Add new Area Of Interest</a>
            </li>
        </ul>
        <div class="tab-content ">
            <div class="tab-pane active m-t-10" id="view_aoi">
                <div class="row" style="font-size: 12px">
                    <div id="view_aoi_dt" class="col-lg-12" style="align-content: center">
                        <div class="panel-body">
                            <table id="view_aoi_datatable" class="display table table-striped table-bordered" >
                                <thead>
                                    <tr>
                                        <th class="text-center font-bold" style="font-size: 15px" >Sr.No</th>
                                        <th class="text-center font-bold" style="font-size: 15px" >Area Of Interest</th>
                                        <th class="text-center font-bold" style="font-size: 15px" >Delete Area Of Interest</th>
                                        
                                    </tr>
                                </thead>

                                <tbody style="font-size: 15px;align-content: center">
                                    <?php
                                    $c = 1;
                                    foreach ($area_of_interest as $aoi) {
                                        ?>
                                        <tr>
                                            <td class="_textcentre text-dark text-center"><?php echo $c ?></td>
                                            <td class="_textcentre text-dark text-center aoi_id" id="<?php echo $aoi->id?>"><?php echo $aoi->name ?></td>     
                                            <td class="_textcentre text-dark text-center" >
                                                <button class="btn btn-danger font-13 delete_aoi ">Delete</button>
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
            <div class="tab-pane m-t-10" id="add_aoi">
                <div class="row" style="font-size: 12px">
                    <div id="add_aoi_dt" class="col-lg-12" style="align-content: center">
                        <form class="form-horizontal" action="/add_new_aoi" method="POST">
                            {{ csrf_field() }}
                            
                            <div class="form-group text-center m-t-10">
                                <h3 class="">Add New Area Of Interest</h3>

                            </div>
                            <div id="aois">
                                <div class="form-group " >
                                    <div class="col-xs-5 " >
                                        <p class="font-13 font-bold pull-right m-t-5">Area Of Interest 1</p>
                                    </div>
                                    <div class="col-xs-6" >
                                        <input class="input-sm font-13" name="aoi_1" type="text">
                                    </div>
                                </div>    
                            </div>

                            <div class="form-group  text-center">
                                <button class="btn  add_aoi" type="button"><i class="glyphicon glyphicon-plus" style="font-size: 20px"></i></button>
                            </div>
                            
                            <div class="form-group text-center">
                                <button class="btn btn-primary font-13" type="submit">Add New Area Of Interests </button>
                            </div>

                        </form>
                    </div>
                </div>

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
@endsection

@section('js')
<script type="text/javascript" src="{{ asset('js/datatable.min.js') }}"></script>
<script src="{{ asset('js/admin_manage_aoi.js') }}" type="text/javascript"></script>

@endsection