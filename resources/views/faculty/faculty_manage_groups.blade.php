@extends('layouts.faculty')
@section('title')
Manage Groups
@endsection
@section('css')

@endsection
@section('content')

<div class="row" style="font-size: 12px">
    <div id="dt" class="col-lg-12" style="align-content: center">
        <!--<div class="card-box table-responsive">-->
        <table id="group_table" class="m-t-10 display table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="_textcentre" >Sr.No</th>
                    <th class="_textcentre">Group Name</th>
                   
                    <th class="_textcentre">Action</th>

                </tr>
            </thead>

            <tbody style="font-size: 15px;align-content: center">
                <?php
                $c = 1;
                foreach ($group as $g){
                ?>
                
                <tr>
                    <td class="text-dark" align="center"><?php echo $c ?></td>
                    <td id="<?php echo $g?>"class="text-dark id" align="center"><?php echo $g ?></td>
                    <td class="text-dark" align="center"><button id="gg" class="btn btn-danger delete_group" >Delete Group</button></td>
                    
                            
                    
                </tr>
                <?php
                $c= $c+1;
                }
                ?>
            </tbody>



        </table>                
        </div>
    </div>

@endsection
@section('js')

<script src="{{ asset('js/mentor_request.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/faculty.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/jq-3.2.1/dt-1.10.16/af-2.2.2/datatables.min.js"></script>

@endsection

