$(document).ready(function () {

    $('#committee_datatable').DataTable();
    $('#dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#committee_datatable_paginate').addClass("pull-right");
    $('#committee_datatable_filter').addClass("pull-right");
    $('#committee_datatable_filter').css("font-size", "13px");
    $('#committee_datatable_filter :input').css("font-size", "13px");
    $('#committee_datatable_length').find('.form-control').css("font-size", "13px");

    $(".se_reminder").on("click", function () {
        var btn = $(this);
        var prg_id = btn.parent().parent().find(".std_prg_id").attr("id");
        $.ajax({
            url: "/sub_exp_reminder",
            method: "POST",
            data: {
                std_prg_id: prg_id,
                _token: Laravel.csrfToken,
            },
            success: function () {
                swal({
                    type: 'success',
                    title: 'Mail Sent',
                    text: 'Reminder mail about subject expert nomination has been sent to mentor',
                    showCloseButton: true,
                });
                btn.parent().text("Mail sent");
            }
        });
    });
    
    $(".se_re").on("click",function(){
        var btn = $(this);
        var prg_id = btn.parent().parent().find(".std_prg_id").attr("id");
        $.ajax({
            url: "/subject_expert_reminder",
            method: "POST",
            data: {
                std_prg_id: prg_id,
                _token: Laravel.csrfToken,
            },
            success: function () {
                swal({
                    type: 'success',
                    title: 'Mail Sent',
                    text: 'Reminder mail to respond on subject expert request is sent',
                    showCloseButton: true,
                });
                btn.parent().text("Mail sent");
            }
        });
    });
    
    $(".acc_se").on("click",function(){
        var btn = $(this);
        var prg_id = btn.parent().parent().find(".std_prg_id").attr("id");
        $.ajax({
            url: "/accept_on_behalf__of_se",
            method: "POST",
            data: {
                std_prg_id: prg_id,
                _token: Laravel.csrfToken,
            },
            success: function () {
                swal({
                    type: 'success',
                    title: 'Accepted by coordinator',
                    text: 'Subject expert has been accepted by coordinator',
                    showCloseButton: true,
                });
                btn.parent().text("Accepted by coordinator");
                window.location.reload();
            }
        });
    });
    
    $(".rej_se").on("click",function(){
        var btn = $(this);
        var prg_id = btn.parent().parent().find(".std_prg_id").attr("id");
        $.ajax({
            url: "/reject_on_behalf__of_se",
            method: "POST",
            data: {
                std_prg_id: prg_id,
                _token: Laravel.csrfToken,
            },
            success: function () {
                swal({
                    type: 'success',
                    title: 'Rejected by coordinator',
                    text: 'Subject expert has been rejected by coordinator',
                    showCloseButton: true,
                });
                btn.parent().text("Rejected by coordinator");
            }
        });
    });
    
    
    

    $(".final_committe").on("click", function () {
        $("#finalize_committee").html("");
        $("#multiselect1_to").html("");
        var btn = $(this);
        var std_prg_id = btn.parent().parent().find(".std_prg_id").attr("id");
        $.ajax({
            url: '/get_bid_requests',
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
    
    $(".edit_mentor").on("click",function(){
        var btn = $(this);
        $.ajax({
            url:"/get_faculty_list",
            method: "POST",
            data: {
                _token: Laravel.csrfToken,
            },
            success: function(data){
                btn.parent().find(".save_mentor").removeAttr("hidden");
                btn.attr("hidden",true);
                var mentor = btn.parent().parent().find(".men");
                var subject_expert = btn.parent().parent().find(".se_td");
                mentor.html("");
                mentor.html(data);
                subject_expert.html("");
                subject_expert.html(data);
                
            }
        })
    });
    
    $(".save_mentor").on("click",function(){
        var btn = $(this);
        var std_prg_id = btn.parent().parent().find(".std_prg_id").attr("id");
        var tmp_men = btn.parent().parent().find(".men");
        var mentor = tmp_men.find(".faculty option:selected").val();
        var tmp_se = btn.parent().parent().find(".se_td");
        var subject_expert = tmp_se.find(".faculty option:selected").val();
        $.ajax({
            url:"/save_changed_mentor_subject_expert",
            method: "POST",
            data:{
                std_prg_id : std_prg_id,
                mentor: mentor,
                subject_expert: subject_expert,
                _token: Laravel.csrfToken,
            },
            success: function(data){
                if(data == "false"){
                    swal({
                        type: "error",
                        title: "Invalid Input",
                        text: "Please select mentor and/or subject expert for updation",
                    });
                }else{
                    swal({
                        type: "success",
                        title: "Saved",
                        text: "Changes have been saved and mail has been sent",
                    });
                }
                window.location.reload();
            }
        })
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
           url: '/edit_committee',
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
                   },
                   sort:false,
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