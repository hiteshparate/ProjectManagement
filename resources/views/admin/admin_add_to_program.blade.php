@extends('layouts.admin')
@section('title')
Add Program
@endsection

@section('css')
@endsection

@section('content')
<div class="row" style="font-size: 12px">
    <div id="admin_dt" class="col-lg-12" style="align-content: center">
        <h3 class="text-center">Add New program</h3>

        <form class="form-horizontal" method="POST" action="/add_new_program">
            {{ csrf_field() }}
            <div class="form-group row m-t-10">
                <div class="col-xs-3" >
                    <p style="font-size: 14px" class="m-t-5 font-bold text-center">Program Name</p>
                </div>
                <div class="col-xs-9" >
                    <input name="p_name" style="font-size: 14px" class=" form-control" type="text">
                </div>
            </div>
            <div class="form-group row m-t-10">
                <div class="col-xs-3" >
                    <p style="font-size: 14px" class="m-t-5 font-bold text-center" >Coordinator Username</p>
                </div>
                <div class="col-xs-9" >
                    <input name="c_name" style="font-size: 14px" class="  form-control" type="text">
                </div>
            </div>
            <div class="form-group row m-t-10">
                <div class="col-xs-3" >
                    <p style="font-size: 14px" class="m-t-5 font-bold text-center" >Coordinator Email Address</p>
                </div>
                <div class="col-xs-9" >
                    <input name="c_mail" style="font-size: 14px" class="  form-control" type="text">
                </div>
            </div>
            <div class="form-group row m-t-10">
                <div class="col-xs-3" >
                    <p style="font-size: 14px" class="m-t-5 font-bold text-center" >Coordinator</p>
                </div>
                <div class="col-xs-9" >
                    <select name="faculty_name" class="form-control select2 " style="font-size: 14px; height: 38px">
                        <option value="n">Select Coordinator</option>
                        <?php
                        foreach ($faculty as $f) {
                            ?>
                            <option value="<?php echo $f->id ?>"><?php echo $f->name ?></option>

                            <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row m-t-10">
                <div class="col-xs-3" >
                    <p style="font-size: 14px" class="m-t-5 font-bold text-center" >Committee Formation Type</p>
                </div>
                <div class="radio radio-info radio-inline col-xs-4 font-13 m-l-10">

                    <input class="" type="radio" id="committee_formation_mtech" value="mtech" name="committee_formation">
                    <label class="font-bold" for="committee_formation_mtech"> M.Tech  </label>
                </div>

                <div class="radio radio-info radio-inline font-13">
                    <input class="" type="radio" id="committee_formation_btech" value="btech" name="committee_formation" >
                    <label class="" for="committee_formation_btech"> B.Tech </label>
                </div>
            </div>
            <div class="form-group" >
                <div class="col-xs-3" >
                    <p style="font-size: 14px" class="m-t-5 font-bold text-center" >Grading System</p>
                </div>
                <div class="col-xs-9" >
                    <select name="grading_type" class="form-control select2 " style="font-size: 14px; height: 38px">
                        <option value="n">Select Grading System</option>
                        <?php
                        foreach ($grades as $g) {
                            ?>
                            <option value="<?php echo $g->id ?>"><?php echo $g->name ?></option>

                            <?php
                        }
                        ?>
                        </optgroup>
                    </select>
                </div>
            </div>


            <div class="form-group text-center">
                <button  type="submit" class="btn btn-primary font-13 ">Create Program</button>
            </div>

        </form>

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
@endsection
