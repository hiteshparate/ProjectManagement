@extends('layouts.rc')
@section('title')
Manage Users
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert.css') }}"/>

@endsection

@section('content')
<div class="row" style="font-size: 12px">
    <div id="rcc_dt" class="col-lg-12" style="align-content: center">
        <ul  class="nav nav-pills  justify-content-center font-13 text-primary">
            <li class="active">
                <a  href="#view_users" data-toggle="tab">View Users</a>
            </li>
            <li >
                <a href="#add_user" data-toggle="tab">Add new User</a>
            </li>
        </ul>
        <div class="tab-content ">
            <div class="tab-pane active m-t-10" id="view_users">
                <div class="row" style="font-size: 12px">
                    <div id="rc_view_dt" class="col-lg-12" style="align-content: center">
                        <div class="panel-body">
                            <table id="rc_view_user_datatable" class="display table table-striped table-bordered" >
                                <thead>
                                    <tr>
                                        <th class=" text-center" style="font-size: 15px; color: black" >Sr.No</th>
                                        <th class="text-center" style="font-size: 15px; color: black">User ID</th>
                                        <th class="text-center" style="font-size: 15px; color: black">User Name</th>
                                        <th class="text-center" style="font-size: 15px; color: black">Email</th>
                                        <th class="text-center" style="font-size: 15px; color: black">Action</th>

                                    </tr>
                                </thead>

                                <tbody style="font-size: 15px;align-content: center">
                                    <?php
                                    $c = 1;
                                    foreach ($rc_users as $rc) {
                                        ?>
                                        <tr>
                                            <td class="text-dark text-center"><?php echo $c ?></td>
                                            <td class="text-dark text-center del_user" id="<?php echo $rc->id ?>"><?php echo $rc->username ?></td>
                                            <td class="text-dark text-center"><?php echo $rc->name ?></td>
                                            <td class="text-dark text-center"><?php echo $rc->email ?></td>
                                            <td class="text-dark text-center">
                                                <?php if ($rc->name != "rc_admin") {
                                                    ?>
                                                    <button class="btn btn-danger font-13 delete_user" >Delete</button>
                                                    <?php
                                                }
                                                ?>
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
            <div class="tab-pane m-t-10" id="add_user">
                <div class="row" style="font-size: 12px">
                    <div id="rc_add_dt" class="col-lg-12" style="align-content: center">
                        <div class="panel panel-default">
                            <div class="panel-heading font-13" style="text-align: center;color: black"><h2>Add users</h2></div>
                            <div class="panel-body font-13">
                                <ul>
                                    <li>Please add CSV file containing user ID, name and user Email Address in the following format.</li>
                                    <li>Faculty ID, name , Daiict Email Address</li>
                                    <li>First line of this file should be like the one shown in following example </li>
                                    <li><b>Example</b></li>
                                    <div style="font-size: 15px">
                                        user_id , name , email<br>
                                        201401001 , abc , 201401001@daiict.ac.in
                                    </div>

                                </ul> <br>
                                <br>
                            </div>
                            <div class="text-center form-group">
                                <form class="form-horizontal" method="POST" action="/add_rc_user" file="true" enctype='multipart/form-data'>
                                    {{ csrf_field() }}
                                    <div class="row col-xs-6">
                                        <div class="col-xs-6">
                                            <input style="font-size: 15px"type="file" name="add_rc_csv" class="form-control">
                                        </div>
                                        <div class="col-xs-6">
                                            <button type="submit" class="btn btn-facebook btn-rounded  font-13 fa-10x m-t-5" >Add users</button>
                                        </div>
                                    </div>

                                </form>


                            </div>  

                        </div>


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
<script src="{{ asset('js/rc_delete_users.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.js"></script>
<script src="{{ asset('js/sweetalert.js') }}" type="text/javascript"></script>

@endsection


