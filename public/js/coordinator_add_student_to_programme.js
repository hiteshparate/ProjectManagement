$(document).ready(function(){
    $('#program_datatable').DataTable();
    $('#p_dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#program_datatable_paginate').addClass("pull-right");
    $('#program_datatable_filter').addClass("pull-right");
    $('#program_datatable_filter  :input').css("font-size", "15px");
    $('#program_datatable_length').find('.form-control').css("font-size", "13px");
    
});