@extends('layouts.coordinator')

@section('title')
Manage Faculties
@endsection

@section('content')
<div class="container">
    <ul  class="nav nav-pills  justify-content-center font-13 text-primary">
        <li class="active">
            <a  href="#view_faculty" data-toggle="tab">View Faculty</a>
        </li>
        <li>
            <a href="#add_faculty" data-toggle="tab">Add Faculty</a>
        </li>

    </ul>

    <div class="tab-content ">
        <div class="tab-pane  m-t-10 active" id="view_faculty">
            <div class="row" style="font-size: 12px">
                <div id="m_dt" class="col-lg-12" style="align-content: center">
                    <table id="faculties" class="display table table-striped table-bordered">
                        <input id="<?php echo $prg_id?>" class="prg_id" hidden="">
                        <thead>
                            <tr>
                                <th class="_textcentre" >Sr.No</th>
                                <th class="_textcentre">Faculty Name</th>
                                <th class="_textcentre">Faculty Email Address</th>
                                <th class="_textcentre">Action</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 15px;align-content: center">
                            <?php
                            $c = 1;
                            foreach ($added_faculties as $f) {
                                ?>
                                <tr>
                                    <td style="color: black;text-align: center"><?php echo $c ?></td>
                                    <td style="color: black;text-align: center" class="id" id="<?php echo $f->id?>"><?php echo $f->name ?></td>
                                    <td style="color: black;text-align: center"><?php echo $f->email ?></td>
                                    <td style="color: black;text-align: center">
                                        <button class="btn btn-danger font-13 delete_faculty">
                                            Delete
                                        </button>
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

        <div class="tab-pane  m-t-10" id="add_faculty">
            <div class="row" style="font-size: 12px">
                <div id="c_dt" class="col-lg-12" style="align-content: center">
                    <form class="form-horizontal" method="post" action="/add_faculties">
                        {{ csrf_field() }}
                        <div class="row m-t-10">
                            <div class="col-xs-5">
                                <select name="from[]" id="multiselect1" class="form-control" size="10" multiple="multiple">
                                    <?php
                                    foreach ($faculties as $f) {
                                        ?>
                                        <option class="font-13 font-bold" value="<?php echo $f->id ?>"><?php echo $f->name ?></option>
                                        <?php
                                    }
                                    ?>

                                </select>
                            </div>

                            <div class="col-xs-2 ">
                                <button type="button" id="multiselect1_rightAll" class="btn btn-block m-t-40"><i class="glyphicon glyphicon-forward"></i></button>
                                <button type="button" id="multiselect1_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
                                <button type="button" id="multiselect1_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
                                <button type="button" id="multiselect1_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
                            </div>

                            <div class="col-xs-5">
                                <select name="to[]" id="multiselect1_to" class="form-control" size="10" multiple="multiple"></select>
                            </div>
                        </div>

                        <div class=" text-center m-t-10">
                            <button class="btn btn-primary font-13" type="submit">Add Faculty to Program</button>
                        </div>
                    </form>


                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@section('js')
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#multiselect1').multiselect({
            search: {
                left: '<input type="text" name="q" class="form-control font-13" placeholder="Search..." />',
                right: '<input type="text" name="q" class="form-control font-13" placeholder="Search..." />',
            },
            fireSearch: function (value) {
                return value.length > 0;
            }
        });
    });
</script>
<script src="{{ asset('js/coordinator_manage_faculties.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/multiselect.js') }}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('js/datatable.min.js') }}"></script>
<script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.js" type="text/javascript"></script>
<script src="{{ asset('js/plugins/datatables.init.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/plugins/select2.min.js') }}" type="text/javascript"></script>


@endsection
