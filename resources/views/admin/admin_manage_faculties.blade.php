@extends('layouts.admin')
@section('title')
Manage Faculties
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>
@endsection

@section('content')
<div class="row" style="font-size: 12px">
    <div id="admin_fac_dt" class="col-lg-12" style="align-content: center">
        <ul  class="nav nav-pills  justify-content-center font-13 text-primary">
            <li class="active">
                <a  href="#view_fauclties" data-toggle="tab">View Faculty</a>
            </li>
            <li >
                <a href="#add_faculties" data-toggle="tab">Add new Faculty</a>
            </li>
        </ul>
        <div class="tab-content ">
            <div class="tab-pane active m-t-10" id="view_fauclties">
                <div class="row" style="font-size: 12px">
                    <div id="a_f_dt" class="col-lg-12" style="align-content: center">
                        <div class="panel-body">
                            <table id="fac_datatable" class="display table table-striped table-bordered" >
                                <thead>
                                    <tr>
                                        <th class=" text-center" style="font-size: 15px; color: black" >Sr.No</th>
                                        <th class="text-center" style="font-size: 15px; color: black">Faculty ID</th>
                                        <th class="text-center" style="font-size: 15px; color: black">Faculty Name</th>
                                        <th class="text-center" style="font-size: 15px; color: black">Email</th>

                                    </tr>
                                </thead>

                                <tbody style="font-size: 15px;align-content: center">
                                    <?php
                                    $c = 1;
                                    foreach ($faculty as $f) {
                                        ?>
                                        <tr>
                                            <td class="text-dark text-center"><?php echo $c ?></td>
                                            <td class="text-dark text-center"><?php echo $f->username ?></td>
                                            <td class="text-dark text-center"><?php echo $f->name ?></td>
                                            <td class="text-dark text-center"><?php echo $f->email ?></td>



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
            <div class="tab-pane m-t-10" id="add_faculties">
                <div class="row" style="font-size: 12px">
                    <div id="a_c_dt" class="col-lg-12" style="align-content: center">
                        <div class="panel panel-default">
                            <div class="panel-heading font-13" style="text-align: center;color: black"><h2>Add Faculty</h2></div>
                            <div class="panel-body font-13">
                                <ul>
                                    <li>Please add CSV file containing Faculty ID, name and Faculty Email Address in the following format.</li>
                                    <li>Faculty ID, name , Daiict Email Address</li>
                                    <li>First line of this file should be like the one shown in following example </li>
                                    <li><b>Example</b></li>
                                    <div style="font-size: 15px">
                                        faculty_id , name , email<br>
                                        201401001 , abc , 201401001@daiict.ac.in
                                    </div>

                                </ul> <br>
                                <br>
                            </div>
                            <div class="text-center form-group">
                                <form class="form-horizontal" method="POST" action="/add_faculty" file="true" enctype='multipart/form-data'>
                                    {{ csrf_field() }}
                                    <div class="row col-xs-6">
                                        <div class="col-xs-6">
                                            <input style="font-size: 15px"type="file" name="add_fac_prog_csv" class="form-control">
                                        </div>
                                        <div class="col-xs-6">
                                            <button type="submit" class="btn btn-facebook btn-rounded  font-13 fa-10x m-t-5" >Add Faculty</button>
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
<script src="{{ asset('js/admin.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('js/datatable.min.js') }}"></script>

@endsection
