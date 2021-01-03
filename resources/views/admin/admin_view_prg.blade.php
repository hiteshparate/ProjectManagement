@extends('layouts.admin')
@section('title')
View Programs
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>

@endsection

@section('content')
<div class="row" style="font-size: 12px">
    <div id="admin_view__dt" class="col-lg-12" style="align-content: center">
        <table id="admin_view_prg" class="display table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center " style="font-size: 15px; color: black" >Sr.No</th>
                    <th class="text-center" style="font-size: 15px; color: black">Program Name</th>
                    <th class="text-center" style="font-size: 15px; color: black">Coordinator Name</th>
                    <th class="text-center" style="font-size: 15px; color: black">Grading System</th>
                    <th class="text-center" style="font-size: 15px; color: black">Committee Formation Type</th>
                </tr>
            </thead>

            <tbody style="font-size: 14px;align-content: center">
                <?php
                $c = 1;
                foreach ($programs as $prg){
                    ?>
                    <tr>
                        <td class="text-dark font-13 " align="center"><?php echo $c ?></td>
                        <td class="text-dark font-13 " align="center" id="<?php echo $prg->id?>"><?php echo $prg->name ?></td>
                        <td class="text-dark font-13 " align="center"><?php $faculty_id = App\coordinator::find($prg->coordinator_id)->faculty_id;
                        echo \App\Faculty::find($faculty_id)->name;
                        ?></td>
                        <td class="text-dark font-13 " align="center"><?php echo App\grading_system::find($prg->grading_system)->name ?></td>
                        <td class="text-dark font-13 " align="center"><?php echo $prg->committee_formation_type ?></td>
                        
                            
                    </tr>
                    <?php
                    $c++;
                }
                ?>


            </tbody>



        </table>
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
