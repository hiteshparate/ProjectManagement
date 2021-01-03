@extends('layouts.admin')
@section('title')
Reports
@endsection
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable.min.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/datatable-editable/datatables.css') }}"/>
<style>
    tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
</style>
@endsection
@section('content')
<div class="row" style="font-size: 12px">
    <div id="dt" class="col-lg-12" style="align-content: center">
        <!--<div class="card-box table-responsive">-->

        <table id="reports" class="display table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="_textcentre" >Sr.No</th>
                    <th class="_textcentre">Student ID</th>
                    <th class="_textcentre">Project Topic</th>
                    <th class="_textcentre">Area Of Interest</th>
                    <th class="_textcentre">Project Type</th>
                    <th class="_textcentre">Key Words</th>
                    <th class="_textcentre">Report</th>
                </tr>
            </thead>

            <tbody style="font-size: 14px;align-content: center">
                <?php
                $c = 1;
                foreach ($data as $d) {
                    ?>
                    <tr class="">
                        <td class="text-dark" align="center"><?php echo $c ?></td>
                        <td id="<?php echo $d["student"]->student_id ?>" class="text-dark id" align="center"><?php echo \App\Student::find($d["student"]->student_id)->username ?></td>
                        <td class="p_t text-dark" align="center"><?php echo $d["student"]->project_topic ?></td>
                        <td class="p_a text-dark" align="center"><?php $a_id =  $d["student"]->area_of_interest;
                                                                       echo App\area_of_interest::find($a_id)->name ?></td>
                        <td class="text-dark" align="center"><?php echo $d["student"]->project_type ?></td>
                        <td class="text-dark" align="center"><?php echo $d["report"]->keywords ?></td>
                        <?php
                        if ($d["report"]->report_location != null) {
                            ?>
                            <td class="text-dark font-13" align="center"><a target="_blank" href="/admin_get_std_report/<?php echo $d["report"]->id ?>" class="btn btn-primary font-13 std_view_report">View Report</a></td>
                            <?php
                        }
                        ?>


                    </tr>
                    <?php
                    $c = $c + 1;
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="_textcentre" >Sr.No</th>
                    <th class="_textcentre">Student ID</th>
                    <th class="_textcentre">Project Topic</th>
                    <th class="_textcentre">Area Of Interest</th>
                    <th class="_textcentre">Project Type</th>
                    <th class="_textcentre">Key Words</th>
                    <th class="_textcentre">Report</th>
                </tr>
            </tfoot>




        </table>                
    </div>
</div>
</div>
@endsection
@section('js')
<script type="text/javascript" src="{{ asset('js/jquery.datatables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/datatable.min.js') }}"></script>
<!--<script src="{{ asset('js/plugins/datatables.init.js') }}" type="text/javascript"></script>-->
<script src="{{ asset('js/admin_report.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/plugins/select2.min.js') }}" type="text/javascript"></script>


@endsection


