$(document).ready(function(){
     $('.off').click(function(){
        var id = $(this).attr("id");
        $.ajax({
            url:"/get_off_campus_data",
            method:"POST",
            data:{
                id:id,
                _token: Laravel.csrfToken},
            success:function(data){
                $("#modal_data").html(data);
//                $("#offcampus_detail").modal("show");
            },
           
        });
    });
    
    $('#datatable').DataTable();
    $('#dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#datatable_paginate').addClass("pull-right");
    $('#datatable_filter').addClass("pull-right");
    $('#datatable_filter  :input').css("font-size","15px");
    $('#datatable_length').find('.form-control').css("font-size","13px");
   

    
});