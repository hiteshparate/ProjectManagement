@extends('layouts.student')
@section('title')
Projects
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/general_user.css') }}"/>

@endsection
@section('content')
<div class="row" style="font-size: 12px">
    <div id="dt" class="col-lg-12" style="align-content: center">
        <!--<div class="card-box table-responsive">-->
        <table id="datatable" class="display table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="_textcentre" >Sr.No</th>
                    <th class="_textcentre">Phase</th>
                    <th class="_textcentre">Subphase</th>
                    <th class="_textcentre">View Reports</th>
                    <!--<th class="_textcentre">Status</th>-->
                    
                    <th class="_textcentre">Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $c = 1;
                foreach ($std_data as $sd) {
                    ?>
                    <tr>
                        <td class="text-dark font-13" align="center"><?php echo $c ?></td>
                        <td class="text-dark font-13" align="center"><?php echo $sd["phase"] ?></td>
                        <td id="<?php echo $sd["std_sub"]->id ?>" class="font-13 id text-dark sub_std_id" align="center"><?php echo $sd["subphase"] ?></td>
                        <td class="text-dark font-13" align="center"><a target="_blank" href="/get_std_sub_report/<?php echo $sd["std_sub"]->id ?>" class="btn btn-primary font-13 std_view_report">View Reports</a></td>
                        
                        
                        
                        
                        <td class="text-dark font-13" align="center"><?php if($sd["std_sub"]->isFinal == 1){
                            echo $sd["std_sub"]->final_grade;
                        }else{
                            echo "Not Yet Graded";
                        } ?></td>
                    </tr>
                    <?php
                    $c = $c + 1;
                }
                ?>
            </tbody>
        </table>                
        <!--</div>-->
    </div>
</div>
@endsection
@section('js')
<script src="{{ asset('js/plugins/select2.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/student.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/plugins/datatables.init.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.js"></script>
@endsection
