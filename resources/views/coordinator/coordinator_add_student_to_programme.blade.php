@extends('layouts.coordinator')
@section('title')
Add students to Program
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>
@endsection

@section('content')
<div class="content">
    <ul  class="nav nav-pills  justify-content-center font-13 text-primary">
        <li class="active">
            <a  href="#view_student" data-toggle="tab">View Students in the Program</a>
        </li>
        <li >
            <a href="#add_student" data-toggle="tab">Add Students to Program</a>
        </li>



    </ul>
    <div class="tab-content">
    <div class="tab-pane active m-t-10" id="view_student">
        <div class="row" style="font-size: 12px">
            <div id="p_dt" class="col-lg-12" style="align-content: center">

                <table id="program_datatable" class="display table table-striped table-bordered" >
                    <thead>
                        <tr>
                            <th class="_textcentre" >Sr.No</th>
                            <th class="_textcentre">Student ID</th>
                            <th class="_textcentre">Student Name</th>
                            <th class="_textcentre">Student Email</th>
                        </tr>
                    </thead>

                    <tbody style="font-size: 15px;align-content: center">
                        <?php
                        $c = 1;
                        foreach ($students as $s) {
                            ?>
                            <tr>
                                <td class="text-center" style="color: black;font-size: 13px"><?php echo $c ?></td>
                                <td class="text-center" style="color: black;font-size: 13px"><?php echo $s->username?></td>
                                <td class="text-center " style="color: black;font-size: 13px"><?php echo $s->name?></td>
                                <td class="text-center" style="color: black;font-size: 13px"><?php echo $s->email?></td>
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
        <div class="tab-pane  m-t-10" id="add_student">
            <div class="row">
                <div id="dt" class="col-lg-12" style="align-content: center">
                    <div class="panel panel-default">
                        <div class="panel-heading font-13" style="text-align: center;color: black"><h2>Add students to programme </h2></div>
                        <div class="panel-body font-13">
                            <ul>
                                <li>Please add CSV file containing StudentID,full name and Student Email Address in the following formet.</li>
                                <li>Student ID, name , Daiict Email Address</li>
                                <li>First line of this file should be like the one shown in following example </li>
                                <li><b>Example</b></li>
                                <div style="font-size: 15px">
                                    student_id , name , email<br>
                                    201401001 , abc , 201401001@daiict.ac.in<br>
                                    201401002 , pqr , 201401002@daiict.ac.in<br>
                                    201401003 , pqrs , 201401003@daiict.ac.in<br>
                                    201401004 , xyz , 201401004@daiict.ac.in<br>
                                </div>

                            </ul> <br>
                            <br>
                        </div>
                        <div class="text-center form-group">
                            <form class="form-horizontal" method="POST" action="/add_student" file="true" enctype='multipart/form-data'>
                                {{ csrf_field() }}
                                <div class="row col-xs-6">
                                    <div class="col-xs-6">
                                        <input style="font-size: 15px"type="file" name="add_prog_csv" class="form-control">
                                    </div>
                                    <div class="col-xs-6">
                                        <button type="submit" class="btn btn-facebook btn-rounded  font-13 fa-10x m-t-5" >Add Students</button>
                                    </div>
                                </div>

                            </form>


                        </div>  

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
<script src="{{ asset('js/coordinator_add_student_to_programme.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('js/datatable.min.js') }}"></script>


@endsection


