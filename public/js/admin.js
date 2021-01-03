$(document).ready(function () {
    $('#admin_view_prg').DataTable();
    $('#admin_view__dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#admin_view_prg_paginate').addClass("pull-right");
    $('#admin_view_prg_filter').addClass("pull-right");
    $('#admin_view_prg_filter  :input').css("font-size", "15px");
    $('#admin_view_prg_length').find('.form-control').css("font-size", "13px");
    
    
    
    $('#fac_datatable').DataTable();
    $('#a_f_dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#fac_datatable_paginate').addClass("pull-right");
    $('#fac_datatable_filter').addClass("pull-right");
    $('#fac_datatable_filter  :input').css("font-size", "15px");
    $('#fac_datatable_length').find('.form-control').css("font-size", "13px");
    
    var value = 2;
    $('.add_grade').on('click', function () {
        $('<div class="form-group " ><div class="col-xs-5 " ><p class="font-13 font-bold pull-right m-t-5">value ' + value +'</p></div><div class="col-xs-6" ><input class="input-sm font-13" name="grade_'+value+'" type="text"></div>').appendTo(grades);
        $('#values').val(value);
        value++;
        return value;
    });
});