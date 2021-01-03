$(document).ready(function () {

    $('#committee_datatable').DataTable();
    $('#dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#committee_datatable_paginate').addClass("pull-right");
    $('#committee_datatable_filter').addClass("pull-right");
    $('#committee_datatable_filter').css("font-size", "13px");
    $('#committee_datatable_filter :input').css("font-size", "13px");
    $('#committee_datatable_length').find('.form-control').css("font-size", "13px");

    $(".final_committe").on("click", function () {
        $("#finalize_committee").html("");
        $("#multiselect1_to").html("");
        var btn = $(this);
        var std_prg_id = btn.parent().parent().find(".std_prg_id").attr("id");
        $.ajax({
            url: '/get_all_mentors',
            method: 'POST',
            data: {
                std_prg_id: std_prg_id,
                _token: Laravel.csrfToken,
            },
            success: function (data) {
                $("#multiselect1").html(data);
                $("#std_prg_id").val(std_prg_id);
                $('#multiselect1').multiselect({
                    search: {
                        left: '<input type="text" name="q" class="form-control font-13" placeholder="Search..." />',
                        right: '<input type="text" name="q" class="form-control font-13" placeholder="Search..." />',
                    },
                    fireSearch: function (value) {
                        return value.length > 0;
                    }
                });
            }
        });
    });

    
    $("#m2_close").on("click",function(){
       window.location.reload();
   });
   $('.edit_committee').on('click', function () {
       $("#multiselect2").html("");
       $("#multiselect2_to").html("");

       var btn = $(this);
       var std_prg_id = btn.parent().parent().find(".std_prg_id").attr("id");
       
       $.ajax({
           url: '/edit_committee_btech',
           method: 'POST',
           data: {
               std_prg_id: std_prg_id,
               _token: Laravel.csrfToken,
           },
           success: function (data) {
               console.log(data);

               $("#multiselect2").html(data[0]);
               $("#multiselect2_to").html(data[1]);
               $("#std_prg_id2").val(std_prg_id);
               $('#multiselect2').multiselect({
                   search: {
                       left: '<input type="text" name="q" class="form-control font-13" placeholder="Search..." />',
                       right: '<input type="text" name="q" class="form-control font-13" placeholder="Search..." />',
                   },
                   fireSearch: function (value) {
                       return value.length > 0;
                   }
               });
           }
       });


   });
   
   $(".view_committee").on("click",function(){
       var btn = $(this);
       var std_prg_id = btn.parent().parent().find(".std_prg_id").attr("id");
       $("#v_members").html("");
       $.ajax({
           url: '/view_committee',
           method: 'POST',
           data: {
               std_prg_id: std_prg_id,
               _token: Laravel.csrfToken,
           },
           success: function (data) {
               console.log(data);
               $("#v_members").html(data);
           }
       });
   });
});

