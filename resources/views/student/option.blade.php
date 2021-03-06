@extends('layouts.option')
@section('title')
Programme
@endsection
@section('content')
<div class="container">
    <div class="wrapper-page">
        <div class="card-box">
            <div class="panel-body">
                <div class="button-list">
                    <?php 
                    foreach($programs as $p){
                        ?>
                    <a href="<?php echo url("consent_form/$p->name") ?>"><button type="submit" class="m-b-10 btn btn-primary btn-lg waves-effect waves-light w-lg" style="margin:0 auto; display:block; font-size: 18px"><?php echo $p->name ?></button></a>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
