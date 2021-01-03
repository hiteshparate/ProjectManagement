var select = $("<option></option>")
        .attr("value", "n")
        .text("Select Subphase");
var select_event = $("<option></option>")
        .attr("value", "n")
        .text("Select Event");
$(document).ready(function () {
    $('.phase_option').on('change', function () {
        var option = this.value;
        $.ajax({
            url: "/get_phase_option",
            method: "POST",
            data: {
                _token: Laravel.csrfToken,
                phase_id: option,
            },
            success: function (data) {
                $(".subphase_option").children('option:not(:first)').remove();
                for (var i = 0; i < data.length; i++) {
                    $(".subphase_option").append($("<option></option>")
                            .attr("value", data[i]["id"])
                            .text(data[i]["name"]));
                }
            }
        });
    });

    $(".edit_phase_option").on("change", function () {
        $(".edit_event_option").empty().append(select_event);
        var option = this.value;
        $.ajax({
            url: "/get_phase_option",
            method: "POST",
            data: {
                _token: Laravel.csrfToken,
                phase_id: option,
            },
            success: function (data) {
                $(".edit_subphase_option").empty().append(select);
                for (var i = 0; i < data.length; i++) {
                    $(".edit_subphase_option").append($("<option></option>")
                            .attr("value", data[i]["id"])
                            .text(data[i]["code"]));
                }
                
            }
        });
        $(".edit_subphase_option").on("change", function () {
            var subphase = this.value;
            $.ajax({
                url: "/get_subphase_option",
                method: "POST",
                data: {
                    _token: Laravel.csrfToken,
                    subphase_id: subphase,
                },
                success: function (data) {
                    $(".edit_event_option").empty().append(select_event);
                    for (var i = 0; i < data.length; i++) {
                        $(".edit_event_option").append($("<option></option>")
                                .attr("value", data[i]["id"])
                                .text(data[i]["name"]));
                    }
                }
            });
        });
    });
    
    $('.edit_event').attr("disabled", "disabled");
    $('.edit_event_option').change(function () {
        if ($('.edit_subphase_option').val() != "n" && $('.edit_phase_option').val() != "n" && $(".edit_event_option").val() != "n") {
            console.log("GG");
            $('.edit_event').removeAttr("disabled");
        } else {
            console.log("WP");
            $('.edit_event').attr("disabled", "disabled");

        }
    });
    $('.edit_phase_option').change(function () {
        if ($('.edit_subphase_option').val() != "n" && $('.edit_phase_option').val() != "n" && $(".edit_event_option").val() != "n") {
            console.log("GG");
            $('.edit_event').removeAttr("disabled");
        } else {
            console.log("WP");
            $('.edit_event').attr("disabled", "disabled");

        }
    });
    $('.edit_subphase_option').change(function () {
        if ($('.edit_subphase_option').val() != "n" && $('.edit_phase_option').val() != "n" && $(".edit_event_option").val() != "n") {
            console.log("GG");
            $('.edit_event').removeAttr("disabled");
        } else {
            console.log("WP");
            $('.edit_event').attr("disabled", "disabled");

        }
    });
    
    var edit_event = $(".edit_event_detail");
    
    $(".edit_event").on("click",function(){
        $(".edit_event_detail").removeAttr("hidden");
        $('.edit_evnt').val($('.edit_event_option :selected').text());
        var event = $('.edit_event_option').val();
        if($(".edit_event_option option:selected").text() == "final_report_submission"){
            $(".edit_evnt").attr("readonly",true);
        }
        $.ajax({
            url: '/edit_event',
            method: 'POST',
            data: {
                event_id: event,
                _token: Laravel.csrfToken
            },
            success: function (data) {
//                console.log(data);
                $("#evnt_id").attr("value",event);
                $("#e_dis").val(data["description"]);
                $("#participants").val(data["role"]);
                if(data["submission"] == 1){
                    $("#e_submission_yes").prop("checked",true);
                }else{
                    $("#e_submission_no").prop("checked",true);
                }
                if(data["mail"] == 1){
                    $("#e_mail_yes").prop("checked",true);
                }else{
                    $("#e_mail_no").prop("checked",true);
                }
                $("#s_date").val(data["start_date"]);
                $("#e_date").val(data["end_date"]);
            }

        });
    });

    
    

    $('#event_datatable').DataTable();
    $('#c_dt').find('.row').css({"width": "100%", "margin": "0"});
    $('#event_datatable_paginate').addClass("pull-right");
    $('#event_datatable_filter').addClass("pull-right");
    $('#event_datatable_filter  :input').css("font-size", "15px");
    $('#event_datatable_length').find('.form-control').css("font-size", "13px");


});