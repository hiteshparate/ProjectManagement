@extends('layouts.admin')
@section('title')
Change Coordinator
@endsection

@section('css')
@endsection

@section('content')
<div class="row" style="font-size: 12px">
    <div id="admin_dt" class="col-lg-12" style="align-content: center">
        <form class="form-horizontal" method="POST" action="/change_coordinator">
            {{csrf_field()}}
            <div class="form-group row m-t-10">
                <div class="col-xs-3" >
                    <p style="font-size: 14px" class="m-t-5 font-bold text-center" >Select Program</p>
                </div>
                <div class="col-xs-9" >
                    <select name="prg_name" class="form-control select2 " style="font-size: 14px; height: 38px">
                        <option value="n">Select Program</option>
                        <?php
                        foreach ($program  as $prg){
                            ?>
                        <option value="<?php echo $prg->id?>"><?php echo $prg->name?></option>
                        
                        <?php
                        }
                        ?>
                        
                    </select>
                </div>
            </div>
            <div class="form-group row m-t-10">
                <div class="col-xs-3" >
                    <p style="font-size: 14px" class="m-t-5 font-bold text-center" >Select Coordinator</p>
                </div>
                <div class="col-xs-9" >
                    <select name="faculty_name" class="form-control select2 " style="font-size: 14px; height: 38px">
                        <option value="n">Select coordinator for program</option>
                        <?php
                        foreach ($faculty  as $f){
                            ?>
                        <option value="<?php echo $f->id?>"><?php echo $f->name?></option>
                        
                        <?php
                        }
                        ?>
                        
                    </select>
                </div>
            </div>
            <div class="form-group text-center m-t-5">
                <button class="btn btn-primary" style="font-size: 14px " type="submit">Change Coordinator</button>
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
@endsection

@section('js')
@endsection