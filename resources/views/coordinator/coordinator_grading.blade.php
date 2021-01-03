@extends('layouts.coordinator')

@section('title')
Grading
@endsection

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>

@endsection

@section('content')
<div class="content">
    <div class="row">
        <div id="dt" class="col-lg-12" style="align-content: center">
            <form class="form-horizontal" action="/get_students_for_grade" method="post">
                {{csrf_field()}}
                <div class="form-group">
                    <select name="phase_choose" required class="phase_option form-control select2" style="font-size: 13px; height: 38px">
                        <option value="n">Select Phase</option>
                        <?php
                        foreach ($phases as $p) {
                            ?>
                            <option value="<?php echo $p->id ?>"><?php echo $p->phase_name ?> </option>

                            <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <select name="subphase_choose" class="subphase_option form-control select2" required style="font-size: 13px; height: 38px">
                        <option value="n">Select Subphase</option>

                    </select>
                </div>
                <div class="form-group text-center">
                    <button class="btn btn-primary font-13 text-center std_btn" type="submit">Grade Students</button>
                </div>

            </form>



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

<script type="text/javascript" src="{{ asset('js/datatable.min.js') }}"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.js" type="text/javascript"></script>

<script src="{{ asset('js/plugins/datatables.init.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/plugins/select2.min.js') }}" type="text/javascript"></script>


@endsection