@extends('layouts.admin')
@section('title')
Manage Grades
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>

@endsection

@section('content')
<div class="row" style="font-size: 12px">
    <div id="admin_view__dt" class="col-lg-12" style="align-content: center">
        <ul  class="nav nav-pills  justify-content-center font-13 text-primary">
            <li class="active">
                <a  href="#view_grades" data-toggle="tab">View Grades</a>
            </li>
            <li >
                <a href="#add_grades" data-toggle="tab">Add new Grades</a>
            </li>
        </ul>
        <div class="tab-content ">
            <div class="tab-pane active m-t-10" id="view_grades">
                <div class="row" style="font-size: 12px">
                    <div id="v_g_dt" class="col-lg-12" style="align-content: center">
                        <div class="panel-body">
                            <table id="grade_datatable" class="display table table-striped table-bordered" >
                                <thead>
                                    <tr>
                                        <th class="text-center font-bold" style="font-size: 15px" >Sr.No</th>
                                        <th class="text-center font-bold" style="font-size: 15px" >Grading system Name</th>
                                        <th class="text-center font-bold" style="font-size: 15px" >Grades</th>
                                    </tr>
                                </thead>

                                <tbody style="font-size: 15px;align-content: center">
                                    <?php
                                    $c = 1;
                                    foreach ($grade as $gr) {
                                        ?>
                                        <tr>
                                            <td class="_textcentre text-dark"><?php echo $c ?></td>
                                            <td class="_textcentre text-dark"><?php echo $gr["grading_system"]->name ?></td>
                                            <td class="_textcentre text-dark">
                                                <?php
                                                for ($a = 0; $a < sizeof($gr["grades"]) - 1; $a++) {
                                                    echo $gr["grades"][$a]->type . " , ";
                                                }
                                                echo $gr["grades"][sizeof($gr["grades"]) - 1]->type
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
            <div class="tab-pane m-t-10" id="add_grades">
                <div class="row" style="font-size: 12px">
                    <div id="c_g_dt" class="col-lg-12" style="align-content: center">
                        <form class="form-horizontal" action="/add_new_grading_system" method="POST">
                            <input hidden="" class="" name="value" value="" id="values">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <div class="col-xs-3 text-center" >
                                    <p class="font-13 font-bold m-t-5 ">Grading System Name</p>
                                </div>
                                <div class="col-xs-9" >
                                    <input name="grade_name"  class="form-control font-13" type="text">
                                </div>
                            </div>
                            <div class="form-group text-center m-t-10">
                                <h3 class="">Add Grade values</h3>

                            </div>
                            <div id="grades">
                                <div class="form-group " >
                                    <div class="col-xs-5 " >
                                        <p class="font-13 font-bold pull-right m-t-5">value 1</p>
                                    </div>
                                    <div class="col-xs-6" >
                                        <input class="input-sm font-13" name="grade_1" type="text">
                                    </div>
                                </div>    
                            </div>

                            <div class="form-group  text-center">
                                <button class="btn  add_grade" type="button"><i class="glyphicon glyphicon-plus" style="font-size: 20px"></i></button>
                            </div>
                            
                            <div class="form-group text-center">
                                <button class="btn btn-primary font-13" type="submit">Create New Grading System </button>
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
<script src="{{ asset('js/admin.js') }}" type="text/javascript"></script>

@endsection
