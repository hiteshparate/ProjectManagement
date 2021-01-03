$(document).ready(function () {
    $('._off').on('click', function () {
        $('.off_hide').removeAttr("hidden");
        $('.off').attr("required", "");
    });
    $('._on').on('click', function () {
        $('.off_hide').attr("hidden", "");
        $('.off').removeAttr("required");
    });
    $('#datatable').DataTable();
    $('#dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#datatable_paginate').addClass("pull-right");
    $('#datatable_filter').addClass("pull-right");

    if ($('#btn_upload').attr('hidden') == "hidden") {
        $('#btn_upload').attr('disabled', 'disabled');
    } else {
        $('#btn_upload').removeAttr('disabled');
    }
    $('#btn_show').mousedown(function () {
        $('#show_p').attr('type', 'text');
    });
    $('#btn_show').mouseup(function () {
        $('#show_p').attr('type', 'password');
    });

    $('.report').on('click', function () {
        var btn = $(this);
        var s_event = btn.parent().parent().find(".s_event").attr("id");
        swal({
            title: "Key words",
            text: "Please enter key words of your report",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            inputPlaceholder: "key words"
        }, function (inputValue) {
            if (inputValue === false)
                return false;
            if (inputValue === "") {
                swal.showInputError("You need to write something!");
                return false;
            }
            $.ajax({
               url:'/get_final_keywords',
               method:"POST",
               data:{
                   s_event : s_event,
                   keywords : inputValue,
                   _token : Laravel.csrfToken
               },
               success:function(){
               swal({
                  type:'success',
                  title : 'Keywords are saved',
                  text : 'Your keywords have been saved successfully'
               });
               window.location.reload();
               }
                 
               
            });
    });
    });
});
