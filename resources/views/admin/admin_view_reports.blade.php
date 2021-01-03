@extends('layouts.admin')
@section('title')
Student Projects
@endsection

@section('css')
@endsection

@section('content')

<div class="row">
    <div class="container col-xs-12 col-lg-12 col-md-12" style="font-size: 15px"> 
        <div class="tab-pane active m-t-10" id="create">
            <div class="row" style="font-size: 12px">
                <div id="m_dt" class="col-lg-12" style="align-content: center">
                    <div class="panel-body">
                        <form class="form-horizontal" method="post" action="/get_admin_reports">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <select name="program_select" class="form-control select2" required style="font-size: 13px; height: 38px">
                                    <option value="n">Select Program</option>
                                        <?php
                                        foreach($programs as $p){
                                            ?>
                                    <option value="<?php echo $p->id ?>"><?php echo $p->name ?></option>
                                    <?php
                                        }
                                        ?>


                                </select>
                            </div>

                            <div class="form-group ">
                                <button type="submit" class="reports btn btn-primary font-13 pull-right">View Reports</button>
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
</div>

@endsection

@section('js')
@endsection