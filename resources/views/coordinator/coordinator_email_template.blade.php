@extends('layouts.coordinator')
@section('title')
Email Template
@endsection
@section('css')
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
@endsection
@section('content')
<div class="form-group row m-t-10">
    <div class="col-xs-2 text-center" >
        <p class="font-13 font-bold  ">To</p>
    </div>
    <div class="col-xs-10" >
        <?php 
        if(\Illuminate\Support\Facades\Session::has("email")){
            ?>
        <input name="to[]"  class="input-group font-13" type="email" multiple="" value="<?php 
        $email = \Illuminate\Support\Facades\Session::pull("email");
        for($a = 0 ; $a < sizeof($email) - 1 ; $a++){
            echo $email[$a].", ";
        }
        echo $email[sizeof($email) - 1];
        ?>">
        <?php
        }else{
            ?>
        <input name="to[]"  class="input-group font-13" type="email" multiple="">
        <?php
        }
        ?>
        
    </div>
    <div class="col-xs-2 text-center" >
        <p class="font-13 font-bold ">Cc</p>
    </div>
    <div class="col-xs-10" >
        <input name="cc[]"  class="input-group font-13" type="email" multiple="">
    </div>
    
    <div class="col-xs-2 text-center" >
        <p id="<?php echo $prg_id ?>" class="font-13 font-bold prg">Load previous template </p>
    </div>
    <div class="col-xs-10" >
        <select class="input-group font-13 subject_select">
            <option value="n">Select Template</option>
            <?php 
            foreach($templates as $tmp){
                ?>
            <option value="<?php echo $tmp->id ?>"><?php echo $tmp->subject?></option>
            <?php
            }
            ?>
        </select>
    </div>
    <div class="col-xs-2 text-center" >
        <p class="font-13 font-bold ">Subject</p>
    </div>
    <div class="col-xs-10" >
        <input name="subject"  id="sub" class="input-group font-13" type="text">
    </div>
    
</div>
<div id="summernote"></div>
<div class="form-group text-center">
<button class="save_mail btn btn-primary font-13">Save & Send</button>
    
</div>
@endsection
@section('js')
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
<script src="{{ asset('js/coordinator_email_template.js') }}" type="text/javascript"></script>
@endsection
